<?php declare(strict_types=1);
require_once 'vendor/autoload.php';
use Currency\CurrencyEnum;
use Currency\Handlers\CurrencyRedis;
use MyCLabs\Enum\Enum;

$current_currency = new CurrencyEnum(CurrencyEnum::USDRUB);

echo (new CurrencyRedis($current_currency))->getCurrency();