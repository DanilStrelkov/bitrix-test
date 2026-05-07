<?php
namespace BitrixMock\Contracts;

interface IBlockElementInterface
{
    public function GetList(
        array $order,
        array $filter,
        mixed $groupBy,
        array $navParams,
        array $select
    ): DBResultInterface;
}
