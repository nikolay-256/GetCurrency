<?php declare(strict_types=1);
namespace Test;
use Currency\CurrencyEnum;
use Currency\Handlers\CurrencyDB;
use Currency\Handlers\CurrencyRedis;
use PHPUnit\Framework\TestCase;

class TestCurrency extends TestCase
{
	/**
	 * Проверка рекурсивных вызовов конструкции получения валюты
	 */
	public function testCurrency()
	{
		$current_currency_usd = new CurrencyEnum(CurrencyEnum::USDRUB);
		$current_currency_eur = new CurrencyEnum(CurrencyEnum::EURRUB);
		$price = (new CurrencyRedis($current_currency_usd))->getPrice();//Начальное получение цен. База и редис пустые, вызывается класс https, в котором отдается рандомное число
		$this->assertSame(CurrencyRedis::$fake_redis_data[$current_currency_usd->getKey()]
			, CurrencyDB::$fake_db_data[$current_currency_usd->getKey()],
			"Сравнение фейковых хранилищ после первого получения цены"
			);
		$my_eur_price = 40.0;
		CurrencyRedis::$fake_redis_data[$current_currency_eur->getKey()] = $my_eur_price;//дальше редиса не пойдет получение данных
		$price = (new CurrencyRedis($current_currency_eur))->getPrice();
		$this->assertSame($price, $my_eur_price, "Сравнение получения цены евро после установки конкретной цены в редис");
		$this->assertSame(empty(CurrencyDB::$fake_db_data[$current_currency_usd->getKey()]), false, "По usd базу дергали и она НЕ пуста");
		$this->assertSame(empty(CurrencyDB::$fake_db_data[$current_currency_eur->getKey()]), true, "По eur базу не дергали и она пуста");
	}
}