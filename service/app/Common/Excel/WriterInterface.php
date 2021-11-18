<?php declare(strict_types=1);

namespace App\Common\Excel;

interface WriterInterface
{


    public function setTitle(string $title): self;

    public function setHeader(array $header): self;

    public function writeRow(array $row): void;

    public function save(string $dist, string $writeType = 'Xlsx'): void;

}
