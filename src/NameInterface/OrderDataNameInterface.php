<?php

declare(strict_types=1);

namespace App\NameInterface;

interface OrderDataNameInterface
{
    public const string ID = 'id';
    public const string CREATED_AT = 'cratedAt';
    public const string PRODUCT_ID = 'productId';
    public const string PRODUCT_NAME = 'productName';
    public const string PRICE = 'price';
    public const string QUANTITY = 'quantity';
    public const string TOTAL = 'total';
    public const string STATUS = 'status';
    public const string ITEMS = 'items';
}
