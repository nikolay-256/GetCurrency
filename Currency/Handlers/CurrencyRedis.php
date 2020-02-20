<?php declare(strict_types=1);

namespace Currency\Handlers;

use Currency\CurrencyEnum;

class CurrencyRedis extends CurrencyDB
{
	public static $fake_redis_data = [];//массив псевдо редиса

	protected function saveToCache(float $currency): bool
	{
		self::$fake_redis_data[$this->currency->getKey()] = $currency;

		return true;
	}

	protected function get(): ?float
	{
		return self::$fake_redis_data[$this->currency->getKey()] ?? null;
	}
}