<?php declare(strict_types=1);
namespace Test;
use Currency\CurrencyEnum;
use Currency\Handlers\CurrencyDB;
use Currency\Handlers\CurrencyRedis;
use PHPUnit\Framework\TestCase;

class TestCurrency extends TestCase
{
	/**
	 * Checking recursive calls of a currency receipt construct
	 */
	public function testCurrency()
	{
		$current_currency_usd = new CurrencyEnum(CurrencyEnum::USDRUB);
		$current_currency_eur = new CurrencyEnum(CurrencyEnum::EURRUB);
		$price = (new CurrencyRedis($current_currency_usd))->getPrice();//Initial pricing. The base and radish are empty, the https class is called, in which a random number is given
		$this->assertSame(CurrencyRedis::$fake_redis_data[$current_currency_usd->getKey()]
			, CurrencyDB::$fake_db_data[$current_currency_usd->getKey()],
			"Comparison of fake stores after the first price receipt"
			);
		$my_eur_price = 40.0;
		CurrencyRedis::$fake_redis_data[$current_currency_eur->getKey()] = $my_eur_price;//there will be no interaction beyond redis
		$price = (new CurrencyRedis($current_currency_eur))->getPrice();
		$this->assertSame($price, $my_eur_price, "Comparison of getting the euro price after setting a specific price in redis");
		$this->assertSame(empty(CurrencyDB::$fake_db_data[$current_currency_usd->getKey()]), false, "The base was pulled by usd and it is NOT empty");
		$this->assertSame(empty(CurrencyDB::$fake_db_data[$current_currency_eur->getKey()]), true, "On eur the base is not pulled and it is empty");
	}
}