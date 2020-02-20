<?php declare(strict_types=1);
namespace Test;
use Currency\CurrencyEnum;
use Currency\Handlers\CurrencyDB;
use Currency\Handlers\CurrencyRedis;
use PHPUnit\Framework\TestCase;

class TestCurrency extends TestCase
{
	/**
	 * �������� ����������� ������� ����������� ��������� ������
	 */
	public function testCurrency()
	{
		$current_currency_usd = new CurrencyEnum(CurrencyEnum::USDRUB);
		$current_currency_eur = new CurrencyEnum(CurrencyEnum::EURRUB);
		$price = (new CurrencyRedis($current_currency_usd))->getPrice();//��������� ��������� ���. ���� � ����� ������, ���������� ����� https, � ������� �������� ��������� �����
		$this->assertSame(CurrencyRedis::$fake_redis_data[$current_currency_usd->getKey()]
			, CurrencyDB::$fake_db_data[$current_currency_usd->getKey()],
			"��������� �������� �������� ����� ������� ��������� ����"
			);
		$my_eur_price = 40.0;
		CurrencyRedis::$fake_redis_data[$current_currency_eur->getKey()] = $my_eur_price;//������ ������ �� ������ ��������� ������
		$price = (new CurrencyRedis($current_currency_eur))->getPrice();
		$this->assertSame($price, $my_eur_price, "��������� ��������� ���� ���� ����� ��������� ���������� ���� � �����");
		$this->assertSame(empty(CurrencyDB::$fake_db_data[$current_currency_usd->getKey()]), false, "�� usd ���� ������� � ��� �� �����");
		$this->assertSame(empty(CurrencyDB::$fake_db_data[$current_currency_eur->getKey()]), true, "�� eur ���� �� ������� � ��� �����");
	}
}