<?php declare(strict_types=1);

namespace App\Common\Db\SQL;

use PhpMyAdmin\SqlParser\Components\Condition;
use PhpMyAdmin\SqlParser\Components\SetOperation;
use PhpMyAdmin\SqlParser\Parser as PhpMyAdminParser;
use PhpMyAdmin\SqlParser\Statements\DeleteStatement;
use PhpMyAdmin\SqlParser\Statements\InsertStatement;
use PhpMyAdmin\SqlParser\Statements\ReplaceStatement;
use PhpMyAdmin\SqlParser\Statements\SelectStatement;
use PhpMyAdmin\SqlParser\Statements\UpdateStatement;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Author:Robert
 *
 * Class Parser
 * @Bean()
 * @package App\Common\Db\SQL
 */
class Parser implements ParserInterface
{

    /**
     *
     */
    const INJECT_CONDITION_FIELD = 'tenantId';

    /**
     *
     */
    const EXCEPTION_CHARS = [self::INJECT_CONDITION_FIELD];

    //    const EXCEPTION_CHARS = ['tenantId', 'inner'];


    /**
     * Author:Robert
     *
     * @param SelectStatement $statement
     * @param int $tenantId
     * @return SelectStatement
     */
    protected function inner(SelectStatement $statement, int $tenantId): SelectStatement
    {
        if ($statement->join || sizeof($statement->from) > 1) {
            $table = ($statement->from[0])->table;
            $statement = $this->where($statement, $tenantId, $table.'.');
        }
        return $statement;
    }

    /**
     * Author:Robert
     *
     * @param $statement
     * @return bool
     */
    protected function hasInner($statement): bool
    {
        if ($statement->join || sizeof($statement->from) > 1) {
            return true;
        }
        return false;
    }

    /**
     * Author:Robert
     *
     * @param $statement
     * @return bool
     */
    protected function hasExist($statement): bool
    {
        if ($statement->expr && is_array($statement->expr)) {
            foreach ($statement->expr as &$expr) {
                if ($expr->function === 'EXISTS' && $expr->subquery === 'SELECT') {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Author:Robert
     *
     * @param SelectStatement $statement
     * @param int $tenantId
     * @return SelectStatement
     */
    protected function exist(SelectStatement $statement, int $tenantId): SelectStatement
    {
        if ($statement->expr && is_array($statement->expr)) {
            foreach ($statement->expr as &$expr) {
                if ($expr->function === 'EXISTS' && $expr->subquery === 'SELECT') {
                    if (preg_match("/\((.*)\)$/", $expr->expr, $subSql)) {
                        list($subQuery,) = $this->process($subSql[1], [], $tenantId);
                        $expr->expr = "exists($subQuery)";
                    }
                }
            }
        }
        return $statement;
    }

    /**
     * Author:Robert
     *
     * @param $statement
     * @param int $tenantId
     * @param string $tablePrefix
     */
    protected function where($statement, int $tenantId, string $tablePrefix = '')
    {
        if ($statement->where) {
            $statement->where[] = new Condition('AND');
        }

        $statement->where[] = new Condition($tablePrefix.self::INJECT_CONDITION_FIELD.' = '.$tenantId);
        return $statement;
    }

    /**
     * Author:Robert
     *
     * @param string $query
     * @param int $tenantId
     * @return bool|void
     */
    private function ignore(string $query, int $tenantId)
    {
        if ($tenantId <= 0) {
            return true;
        }
        foreach (self::EXCEPTION_CHARS as $char) {
            if (stripos($query, $char) !== false) {
                return true;
            }
        }
    }

    /**
     * Author:Robert
     *
     * @param UpdateStatement $statement
     * @return UpdateStatement
     */
    private function fixUpdateOffset(UpdateStatement $statement): UpdateStatement
    {
        if ($statement->limit && isset($statement->limit->offset) && $statement->limit->offset == 0) {
            $statement->limit = null;
        }
        return $statement;
    }

    /**
     * Author:Robert
     *
     * @param string $query
     * @param array $bindings
     * @param int $tenantId
     * @return array
     */
    public function process(string $query, array $bindings, int $tenantId): array
    {
        if ($this->ignore($query, $tenantId)) {
            return [$query, $bindings];
        }
        $parser = new PhpMyAdminParser($query);
        $statement = $parser->statements[0];
        if ($statement instanceof SelectStatement || $statement instanceof DeleteStatement || $statement instanceof UpdateStatement) {
            if ($statement instanceof SelectStatement) {
                if ($this->hasInner($statement)) {
                    $statement = $this->inner($statement, $tenantId);
                } elseif ($this->hasExist($statement)) {
                    $statement = $this->exist($statement, $tenantId);
                } else {
                    $statement = $this->where($statement, $tenantId);
                }
            } else {
                $statement = $this->where($statement, $tenantId);
            }
            if ($statement instanceof UpdateStatement) {
                $statement = $this->fixUpdateOffset($statement);
            }
            $query = $statement->build();
        } elseif ($statement instanceof InsertStatement || $statement instanceof ReplaceStatement) {
            if ($this->hasSet($statement)) {
                $statement = $this->set($statement, $tenantId);
            } elseif ($this->hasValues($statement)) {
                $statement = $this->values($statement, $tenantId);
            }
            $query = $statement->build();
        }
        return [$query, $bindings];

    }

    /**
     * Author:Robert
     *
     * @param $statement
     * @return bool
     */
    private function hasValues($statement): bool
    {
        if ($statement->values) {
            return true;
        }
        return false;
    }

    /**
     * Author:Robert
     *
     * @param $statement
     * @return bool
     */
    private function hasSet($statement): bool
    {
        if ($statement->set) {
            return true;
        }
        return false;
    }

    /**
     * Author:Robert
     *
     * @param $statement
     * @param int $tenantId
     * @return mixed
     */
    private function values($statement, int $tenantId)
    {
        if ($statement->values) {
            $statement->into->columns[] = 'tenantId';
            foreach ($statement->values as &$value) {
                $value->raw[] = $tenantId;
                $value->values[] = $tenantId;
            }
        }
        return $statement;

    }

    /**
     * Author:Robert
     *
     * @param $statement
     * @param int $tenantId
     * @return mixed
     */
    private function set($statement, int $tenantId)
    {
        if ($statement->set) {
            $statement->set[] = new SetOperation(self::INJECT_CONDITION_FIELD, $tenantId);
        }
        return $statement;
    }

}
