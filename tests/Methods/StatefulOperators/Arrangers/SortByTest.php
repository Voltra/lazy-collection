<?php


namespace LazyCollection\Tests\Methods\StatefulOperators\Arrangers;


use LazyCollection\Stream;
use LazyCollection\Tests\Globals\HelpersBasedTests;

class SortByTest extends HelpersBasedTests
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @param callable $key
	 * @return array
	 */
	public function sortBy(iterable $it, callable $key){
		return Stream::fromIterable($it)
			->sortBy($key)
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::sortBy
	 * @dataProvider provideSortPayload
	 *
	 * @param iterable $input
	 * @param callable $key
	 * @param iterable $expected
	 */
	public function makeSureItSortsProperlyUsingTheKey(iterable $input, callable $key, iterable $expected){
		$result = $this->sortBy($input, $key);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideSortPayload(){
		return $this->provider->provideSortPayload();
	}
}
