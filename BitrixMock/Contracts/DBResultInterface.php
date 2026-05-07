<?php
namespace BitrixMock\Contracts;

interface DBResultInterface
{
    public function Fetch(): array|false;
    public function GetNext(): array|false;
}
