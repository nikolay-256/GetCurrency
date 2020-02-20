<?php declare(strict_types=1);

namespace Currency\Handlers;

use Currency\CurrencyEnum;

class CurrencyRedis extends CurrencyDB
{
	protected $fake_redis_data = [];//массив псевдо редиса

	protected function saveToCache(float $currency): bool
	{
		$this->ake_redis_data[$this->currency->getKey()] = $currency;

		return true;
	}

	protected function get(): ?float
	{
		return $ake_redis_data[$this->currency] ?? null;
	}
}