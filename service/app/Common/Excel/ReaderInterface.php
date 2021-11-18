<?php declare(strict_types=1);

namespace App\Common\Excel;

/**
 * Interface ReaderInterface
 * @author Robert
 * @package App\Common\Excel
 */
interface ReaderInterface
{
    public function loadFile(string $dist): self;

    public function setSheet(int $sheet): self;

    public function setRules(array $rules = []): self;

    public function setRule(string $column, string $type): self;

    public function setStartRow(int $index): self;

    public function setStartCol(int $index): self;

    public function getHighestColumn(): string;

    public function getHighestRow(): int;

    public function forEach(\Closure $callable): void;
}
