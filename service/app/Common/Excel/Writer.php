<?php declare(strict_types=1);

namespace App\Common\Excel;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Author:Robert
 *
 * Class Writer
 * @package App\Common\Excel
 */
class Writer implements WriterInterface
{

    protected $header = [];

    protected $spreadsheet;

    protected $worksheet;

    protected $row = 1;


    /**
     * Author:Robert
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): WriterInterface
    {
        $this->worksheet->setTitle($title);
        return $this;
    }

    /**
     * Author:Robert
     *
     * @param array $row
     */
    public function writeRow(array $row): void
    {
        $column = 1;
        foreach ($row as $val) {
            $this->worksheet->setCellValueByColumnAndRow($column, $this->row, $val);
            $column++;
        }
        $this->row++;
    }

    /**
     *
     */
    public function __construct()
    {
        $this->instance();
    }

    /**
     * Author:Robert
     *
     * @return $this
     */
    public function instance(): WriterInterface
    {
        $this->spreadsheet = new Spreadsheet();
        $this->worksheet = $this->spreadsheet->getActiveSheet();
        return $this;
    }

    /**
     * 设置表头
     * Author:Robert
     *
     * @param array $header
     * @return $this
     */
    public function setHeader(array $header): WriterInterface
    {
        $this->header = $header;
        foreach ($this->header as $key => $value) {
            $this->worksheet->setCellValueByColumnAndRow($key + 1, $this->row, (string)$value);
        }
        $this->row++;
        return $this;
    }


    /**
     * Author:Robert
     *
     * @param string $dist
     * @param string $writeType
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function save(string $dist, string $writeType = 'Xlsx'): void
    {
        $writer = IOFactory::createWriter($this->spreadsheet, $writeType); //按照指定格式生成Excel文件
        $writer->save($dist);
        $this->spreadsheet->disconnectWorksheets();
        unset($this->spreadsheet);
        unset($this->worksheet);
    }

}
