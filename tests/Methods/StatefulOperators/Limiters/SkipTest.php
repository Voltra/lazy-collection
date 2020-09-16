<?php


namespace LazyCollection\Tests\Methods\StatefulOperators\Limiters;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class SkipTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @param int $maxAmount
	 * @return array
	 */
	public function skip(iterable $it, int $maxAmount){
		return Stream::fromIterable($it)
			->skip($maxAmount)
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::skip
	 * @dataProvider provideTakeData
	 *
	 * @param iterable $input
	 * @param int $maxAmount
	 * @param iterable $expected
	 */
	public function makeSureItSkipsUpToTheMaxAmount(iterable $input, int $maxAmount, iterable $expected){
		$result = $this->skip($input, $maxAmount);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideTakeData(){
		return [
			[
				[1, 2, 3, 4],
				2,
				[3, 4],
			],
			[
				[],
				2,
				[],
			],
			[
				[1],
				3,
				[],
			],
			[
				[1,2,3,4],
				4,
				[],
			],
		];
	}
}
