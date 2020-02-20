<?php declare(strict_types=1);

namespace Currency\Handlers;

use Currency\CurrencyEnum;
use \Go\ParserReflection\ReflectionClass;

abstract class CurrencyAbstract
{
	/**
	 * Объект валюты
	 *
	 * @var \Currency\CurrencyEnum
	 */
	protected $currency;

	/**
	 * Получить из хранилища
	 *
	 * @return float|null
	 */
	abstract protected function get(): ?float;

	/**
	 * Записать в кеш
	 *
	 * @return bool
	 */
	abstract protected function saveToCache(float $currency): bool;

	/**
	 * CurrencyAbstract constructor.
	 *
	 * @param \Currency\CurrencyEnum $currency Валюта
	 */
	public function __construct(CurrencyEnum $currency)
	{
		$this->currency = $currency;
	}

	/**
	 * Основной метод получения валюты из кеша или запросом
	 */
	public function getPrice(): ?float
	{
		$ret_currency = null;
		$ret_currency = $this->get();
		if ($ret_currency === null) {
			$reflect = new ReflectionClass($this);
			$parent_instance = $reflect->getParentClass()->newInstance($this->currency);
			$ret_currency = $parent_instance->getPrice();
			//кешируем
			if (!$this->saveToCache($ret_currency)) {
				trigger_error('Error save cache');
			}
		}

		return $ret_currency;
	}
}