<?php declare(strict_types=1);

namespace App\Common\Excel;

use App\Common\Excel\Rule\DateRole;
use App\Common\Excel\Rule\DatetimeRole;
use App\Common\Excel\Rule\DoubleRole;
use App\Common\Excel\Rule\IntegerRole;
use App\Common\Excel\Rule\StringRole;
use App\Common\Excel\Rule\TimeRole;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Swoft\Bean\Annotation\Mapping\Bean;
use Closure;

/**
 * Class Reader
 * @author Robert
 * @Bean()
 * @package App\Common\Excel
 */
class Reader implements ReaderInterface
{
    protected $dist;

    protected $fileType;
    protected $sheet = 0;
    protected $startRow = 1;
    protected $startCol = 'A';
    protected $rules = [];

    private $spreadsheet;
    private $currSheet;

    const DATETIME_ROLE = 'DATETIME';
    const DATE_ROLE = 'DATE';
    const TIME_ROLE = 'TIME';
    const STRING_ROLE = 'STRING';
    const INTEGER_ROLE = 'INTEGER';
    const DOUBLE_ROLE = 'DOUBLE';

    const RULE_FILTER = [
        self::DATETIME_ROLE => DatetimeRole::class,
        self::DATE_ROLE => DateRole::class,
        self::TIME_ROLE => TimeRole::class,
        self::INTEGER_ROLE => IntegerRole::class,
        self::STRING_ROLE => StringRole::class,
        self::DOUBLE_ROLE => DoubleRole::class,
    ];

    const CSV_INPUT_ENCODING = 'GBK';
    const CSV_DELIMITER = ',';

    /**
     * @param string $dist
     * @return ReaderInterface
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @author Robert
     */
    public function loadFile(string $dist): ReaderInterface
    {
        $this->dist = $dist;
        $this->fileType = IOFactory::identify($this->dist);
        $spreadsheet = IOFactory::createReader($this->fileType);
        $spreadsheet->setReadDataOnly(true);
        $spreadsheet->setLoadSheetsOnly(true);
        if ($this->fileType === 'Csv') {
            $spreadsheet->setInputEncoding(self::CSV_INPUT_ENCODING);
            $spreadsheet->setDelimiter(self::CSV_DELIMITER);
        }
        $this->spreadsheet = $spreadsheet->load($this->dist);
        $this->spreadsheet->garbageCollect();
        $this->setSheet($this->sheet);

        return $this;
    }

    /**
     * @param int $sheet
     * @return $this
     * @author Robert
     */
    public function setSheet(int $sheet): ReaderInterface
    {
        $this->sheet = $sheet;
        $this->currSheet = $this->spreadsheet->getSheet($this->sheet);
        return $this;
    }

    /**
     * @param array $rules
     * @return $this
     * @author Robert
     */
    public function setRules(array $rules = []): ReaderInterface
    {
        foreach ($rules as $column => $type) {
            $this->setRule($column, $type);
        }
        return $this;
    }

    /**
     * @param string $column
     * @param string $type
     * @return $this
     * @author Robert
     */
    public function setRule(string $column, string $type): ReaderInterface
    {
        $this->rules[$column] = $type;
        return $this;
    }

    /**
     * @param int $index
     * @return $this
     * @author Robert
     */
    public function setStartRow(int $index): ReaderInterface
    {
        $this->startRow = $index;
        return $this;
    }

    /**
     * @param int $index
     * @return $this
     * @author Robert
     */
    public function setStartCol(int $index): ReaderInterface
    {
        $this->startCol = $index;
        return $this;
    }

    /**
     * @param string $column
     * @return bool
     * @author Robert
     */
    private function hasRule(string $column): bool
    {
        return isset($this->rules[$column]);
    }

    /**
     * @param string $column
     * @return string
     * @author Robert
     */
    private function getRule(string $column): string
    {
        return $this->rules[$column] ?? '';
    }

    /**
     * @return string
     * @author Robert
     */
    public function getHighestColumn(): string
    {

        return $this->currSheet->getHighestColumn();
    }

    /**
     * @return int
     * @author Robert
     */
    public function getHighestRow(): int
    {
        return $this->currSheet->getHighestRow();
    }


    /**
     * @param Closure $callable
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @author Robert
     */
    public function forEach(Closure $callable): void
    {
        $maxColumn = $this->getHighestColumn();
        $maxRow = $this->getHighestRow();
        for ($row = $this->startRow; $row <= $maxRow; $row++) {
            $line = [];
            for ($column = 'A'; $column <= $maxColumn; $column++) {
                $value = $this->currSheet->getCell($column.$row)->getValue();
                if ($this->hasRule($column)) {
                    $filter = self::RULE_FILTER[$this->getRule($column)] ?? '';
                    $value = $filter ? (new $filter())->format($value, $this->fileType, $column, $row) : $value;
                }
                $line[$column] = $value;
            }
            $isEnd = $row == $maxRow || (!trim((string)current($line)) && (!isset($line['B']) || !$line['B']));
            $callable($line, $isEnd, $row);
            if ($isEnd) {
                break;
            }
        }
        $this->spreadsheet->disconnectWorksheets();
        unset($this->currSheet);
        unset($this->spreadsheet);
    }

    /**
     * @param Closure $callable
     * @param int $per
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @author Robert
     */
    public function chunk(Closure $callable, int $per = 10): void
    {
        $index = 1;
        $rows = [];
        $this->forEach(static function ($line, $isEnd, $row) use (&$index, $per, &$rows, $callable) {
            $rows[] = [$line, $row];
            if ($index % $per === 0 || $isEnd) {
                $callable($rows, $isEnd);
                $rows = [];
            }
            $index++;
        });
    }


}
