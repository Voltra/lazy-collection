<?php


namespace LazyCollection\Tests\Methods\Operators\Filters;


use LazyCollection\Stream;
use LazyCollection\Tests\Globals\HelpersBasedTests;

class InstanceOfTest extends HelpersBasedTests
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @param string $class
	 * @return array
	 */
	public function instanceOf(iterable $it, string $class){
		return Stream::fromIterable($it)
			->instanceOf($class)
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
		$count = $isInstance ? 1 : 0;
		$result = $this->instanceOf([$value], $class);
		$this->assertCount($count, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideClassInstanceCheck(){
		return $this->provider->provideClassInstanceCheck();
	}
}
