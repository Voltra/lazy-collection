<?php


namespace LazyCollection\Tests\Methods\StatefulOperators\Arrangers;


use LazyCollection\Stream;

class SortDescendingTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @return array
	 */
	public function sortDescending(iterable $it){
		return Stream::fromIterable($it)
			->sortDescending()
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers       \LazyCollection\Stream::sortDescending
	 * @dataProvider provideSortDescendingPayload
	 *
	 * @param iterable $input
	 * @param iterable $expected
	 */
	public function makeSureItSortsInDescendingOrder(iterable $input, iterable $expected){
		$result = $this->sortDescending($input);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideSortDescendingPayload(){
		return [
			[
				[1, 4, 3],
				[4, 3, 1],
			],
		];
	}
}
