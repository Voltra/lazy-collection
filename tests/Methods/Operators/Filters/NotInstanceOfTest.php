<?php


namespace LazyCollection\Tests\Methods\Operators\Filters;


use LazyCollection\Stream;
use LazyCollection\Tests\Globals\HelpersBasedTests;

class NotInstanceOfTest extends HelpersBasedTests
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @param string $class
	 * @return array
	 */
	public function notInstanceOf(iterable $it, string $class){
		return Stream::fromIterable($it)
			->notInstanceOf($class)
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::instanceOf
	 * @dataProvider provideClassInstanceCheck
	 *
	 * @param $value
	 * @param string $class
	 * @param bool $isInstance
	 */
	public function properlyFilterInstances($value, string $class, bool $isInstance){
		$count = $isInstance ? 0 : 1;
		$result = $this->notInstanceOf([$value], $class);
		$this->assertCount($count, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideClassInstanceCheck(){
		return $this->provider->provideClassInstanceCheck();
	}
}
