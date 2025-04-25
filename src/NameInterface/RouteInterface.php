<?php

declare(strict_types=1);

namespace App\NameInterface;

interface RouteInterface
{
    public const string API_ORDER_ROUTE = '/orders';
    public const string API_ORDER_GET_SINGLE_ROUTE = '/{id}';
    public const string API_ORDER_GET_SINGLE_NAME = 'api_order_get_single';
    public const string API_ORDER_PATCH_SINGLE_ROUTE = '/{id}';
    public const string API_ORDER_PATCH_SINGLE_NAME = 'api_order_patch_single';
    public const string API_ORDER_POST_SINGLE_ROUTE = '/';
    public const string API_ORDER_POST_SINGLE_NAME = 'api_order_post_single';

}