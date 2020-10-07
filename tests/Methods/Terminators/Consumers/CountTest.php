<?php


namespace LazyCollection\Tests\Methods\Terminators\Consumers;


use LazyCollection\Stream;

class CountTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function _count(iterable $it, callable $predicate = null){
		return Stream::fromIterable($it)
			->count($predicate);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @dataProvider provideArrays
	 *
	 * @param array $arr
	 */
	public function properlyCounts(array $arr){
		$expected = count($arr);
		$value = $this->_count($arr);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @dataProvider provideArrayWithPredicates
	 *
	 * @param array $arr
	 * @param callable $predicate
	 */
	public function properlyCountsWithPredicate(array $arr, callable $predicate){
		$expected = count(array_filter($arr, $predicate));
		$value = $this->_count($arr, $predicate);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @dataProvider provideArrayWithPredicates
	 *
	 * @param array $arr
	 * @param callable $predicate
	 */
	public function onlyCallsOncePerElement(array $arr, callable $predicate){
		$expected = count($arr);
		$callback = $this->createCallbackMock($this->exactly($expected), null, true);
		$this->_count($arr, $callback);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideArrays(){
		return [
			[
				[1,2,3],
			],
			[
				[],
			],
			[
				["key" => "value"],
			],
		];
	}

	public function provideArrayWithPredicates(){
		return [
			[
				[1, 2, 3],
				function($x){ return $x % 2 !== 0; },
			],
		];
	}
}
