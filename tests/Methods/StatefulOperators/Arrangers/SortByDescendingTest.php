<?php


namespace LazyCollection\Tests\Methods\StatefulOperators\Arrangers;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class SortByDescendingTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @param callable $key
	 * @return array
	 */
	public function sortByDescending(iterable $it, callable $key){
		return Stream::fromIterable($it)
			->sortByDescending($key)
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::sortByDescending
	 * @dataProvider provideSortByDescendingPayload
	 *
	 * @param iterable $input
	 * @param callable $key
	 * @param iterable $expected
	 */
	public function makeSureItSortsProperlyUsingTheKeyAndInDescendingOrder(iterable $input, callable $key, iterable $expected){
		$result = $this->sortByDescending($input, $key);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideSortByDescendingPayload(){
		return [
			[
				[1,-5,8],
				function($x){ return -4 * (4 - $x); },
				[8, 1, -5], // by [1 => -12, -5 => -36, 8 => 16]
			],
		];
	}
}
