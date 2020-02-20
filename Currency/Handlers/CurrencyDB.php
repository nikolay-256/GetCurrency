<?php declare(strict_types=1);

namespace Currency\Handlers;

use Currency\CurrencyEnum;

class CurrencyDB extends CurrencyHTTPS
{
	protected static $fake_db_data = [];//массив псевдо базы данных

	protected function saveToCache(float $currency): bool
	{
		self::$fake_db_data[$this->currency->getKey()] = $currency;

		return true;
	}

	protected function get(): ?float
	{
		return self::$fake_db_data[$this->currency->getKey()] ?? null;
	}
}