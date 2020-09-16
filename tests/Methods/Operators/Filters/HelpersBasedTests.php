<?php


namespace LazyCollection\Tests\Methods\Operators\Filters;


use LazyCollection\Tests\Globals\HelpersTest;
use LazyCollection\Tests\PHPUnit;

abstract class HelpersBasedTests extends PHPUnit
{
	/**
	 * @var HelpersTest
	 */
	protected $provider;

	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * HelpersBasedTests constructor.
	 * @param null $name
	 * @param array $data
	 * @param string $dataName
	 */
	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->provider = new HelpersTest($name, $data, $dataName);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
}
