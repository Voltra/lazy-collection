<?php


namespace LazyCollection\Tests\Methods\Operators\Filters;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class FilterTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @param callable $predicate
	 * @return array
	 */
	public function filter(iterable $it, callable $predicate){
		return Stream::fromIterable($it)
			->filter($predicate)
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::filter
	 * @dataProvider provideFilterData
	 *
	 * @param array $input
	 * @param callable $predicate
	 * @param array $expected
	 */
	public function predicateIsCalledOncePerElement(array $input, callable $predicate, array $expected){
		$count = count($input);
		$callback = $this->createCallbackMock($this->exactly($count), null, false);
		$this->filter($input, $callback);
	}

	/**
	 * @test
	 * @covers \LazyCollection\Stream::filter
	 * @dataProvider provideFilterData
	 *
	 * @param array $input
	 * @param callable $predicate
	 * @param array $expected
	 */
	public function filtersProperlyUsingThePredicate(array $input, callable $predicate, array $expected){
		$result = $this->filter($input, $predicate);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideFilterData(){
		return [
			[
				[1, 2, 3],
				function($e){ return $e % 2 === 0; },
				[2],
			]
		];
	}
}
