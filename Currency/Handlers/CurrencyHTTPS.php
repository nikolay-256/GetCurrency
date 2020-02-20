<?php declare(strict_types=1);

namespace Currency\Handlers;

use Currency\CurrencyEnum;

class CurrencyHTTPS extends CurrencyAbstract
{
	protected function get(): ?float
	{
		//Запрос к сервису
		$ret_currency = rand(50, 150);
		if ($ret_currency === null) {
			throw new \Exception('Failed get currency on HTTPS');
		}

		return $ret_currency;
	}

	protected function saveToCache(float $currency): bool
	{
		return false;
	}
}