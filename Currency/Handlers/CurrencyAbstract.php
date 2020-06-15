<?php declare(strict_types=1);

namespace Currency\Handlers;

use Currency\CurrencyEnum;
use \Go\ParserReflection\ReflectionClass;

abstract class CurrencyAbstract
{
	/**
	 * Currency Object
	 *
	 * @var \Currency\CurrencyEnum
	 */
	protected $currency;

	/**
	 * Get from storage
	 *
	 * @return float|null
	 */
	abstract protected function get(): ?float;

	/**
	 * Write to cache
	 *
	 * @return bool
	 */
	abstract protected function saveToCache(float $currency): bool;

	/**
	 * CurrencyAbstract constructor.
	 *
	 * @param \Currency\CurrencyEnum $currency Currency
	 */
	public function __construct(CurrencyEnum $currency)
	{
		$this->currency = $currency;
	}

	/**
	 * The main method of obtaining currency from the cache or request
	 */
	public function getPrice(): ?float
	{
		$ret_currency = null;
		$ret_currency = $this->get();
		if ($ret_currency === null) {
			$reflect = new ReflectionClass($this);
			$parent_instance = $reflect->getParentClass()->newInstance($this->currency);
			$ret_currency = $parent_instance->getPrice();
			//cache
			if (!$this->saveToCache($ret_currency)) {
				trigger_error('Error save cache');
			}
		}

		return $ret_currency;
	}
}