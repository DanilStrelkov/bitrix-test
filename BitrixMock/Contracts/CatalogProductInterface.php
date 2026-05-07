<?php
namespace BitrixMock\Contracts;

interface CatalogProductInterface
{
    public function GetByID(int $id): array|false;
}
