<?php
use PHPUnit\Framework\TestCase;

class CDBResultTest extends TestCase
{
    public function test_fetch_returns_items_sequentially(): void
    {
        $items = [['ID' => 1, 'NAME' => 'A'], ['ID' => 2, 'NAME' => 'B']];
        $result = new CDBResult($items);

        $this->assertSame(['ID' => 1, 'NAME' => 'A'], $result->Fetch());
        $this->assertSame(['ID' => 2, 'NAME' => 'B'], $result->Fetch());
    }

    public function test_fetch_returns_false_when_exhausted(): void
    {
        $result = new CDBResult([['ID' => 1]]);
        $result->Fetch();
        $this->assertFalse($result->Fetch());
    }

    public function test_get_next_is_alias_of_fetch(): void
    {
        $result = new CDBResult([['ID' => 5]]);
        $this->assertSame(['ID' => 5], $result->GetNext());
        $this->assertFalse($result->GetNext());
    }
}
