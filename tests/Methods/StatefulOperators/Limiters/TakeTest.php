<?php


namespace LazyCollection\Tests\Methods\StatefulOperators\Limiters;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class TakeTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @param int $maxAmount
	 * @return array
	 */
	public function take(iterable $it, int $maxAmount){
		return Stream::fromIterable($it)
			->take($maxAmount)
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::take
	 * @dataProvider provideTakeData
	 *
	 * @param iterable $input
	 * @param int $maxAmount
	 * @param iterable $expected
	 */
	public function makeSureItTakesUpToTheMaxAmount(iterable $input, int $maxAmount, iterable $expected){
		$result = $this->take($input, $maxAmount);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideTakeData(){
		return [
			[
				[1,2,3,4],
				2,
				[1,2],
			],
			[
				[],
				2,
				[],
			],
			[
				[1],
				3,
				[1],
			],
			[
				[1,2,3,4],
				4,
				[1,2,3,4],
			],
		];
	}
}
