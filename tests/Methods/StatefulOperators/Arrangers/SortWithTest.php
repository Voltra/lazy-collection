<?php


namespace LazyCollection\Tests\Methods\StatefulOperators\Arrangers;


use LazyCollection\Stream;
use LazyCollection\Tests\Globals\HelpersBasedTests;

class SortWithTest extends HelpersBasedTests
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @param callable $comparator
	 * @return array
	 */
	public function sortWith(iterable $it, callable $comparator){
		return Stream::fromIterable($it)
			->sortWith($comparator)
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::sortWith
	 * @dataProvider provideSortWithPayload
	 *
	 * @param iterable $input
	 * @param callable $comparator
	 * @param iterable $expected
	 */
	public function makeSureItUniquesByTheKey(iterable $input, callable $comparator, iterable $expected){
		$result = $this->sortWith($input, $comparator);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideSortWithPayload(){
		return $this->provider->provideSortWithPayload();
	}
}
