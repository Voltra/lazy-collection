<?php


namespace LazyCollection\Tests\Methods\StatefulOperators\Arrangers;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class SortTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @return array
	 */
	public function sort(iterable $it){
		return Stream::fromIterable($it)
			->sort()
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers       \LazyCollection\Stream::sort
	 * @dataProvider provideSortPayload
	 *
	 * @param iterable $input
	 * @param iterable $expected
	 */
	public function makeSureItSorts(iterable $input, iterable $expected){
		$result = $this->sort($input);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideSortPayload(){
		return [
			[
				[1,4,3],
				[1,3,4],
			],
		];
	}
}
