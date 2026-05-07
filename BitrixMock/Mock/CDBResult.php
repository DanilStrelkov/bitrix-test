<?php
use BitrixMock\Contracts\DBResultInterface;

class CDBResult implements DBResultInterface
{
    private array $items;
    private int $position = 0;

    public function __construct(array $items)
    {
        $this->items = array_values($items);
    }

    public function Fetch(): array|false
    {
        if ($this->position >= count($this->items)) {
            return false;
        }
        return $this->items[$this->position++];
    }

    public function GetNext(): array|false
    {
        return $this->Fetch();
    }
}
