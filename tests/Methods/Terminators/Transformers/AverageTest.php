<?php


namespace LazyCollection\Tests\Methods\Terminators\Transformers;


use LazyCollection\Stream;

class AverageTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function average(iterable $it){
		return Stream::fromIterable($it)->average();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::average
	 * @dataProvider provideAvgData
	 *
	 * @param iterable $input
	 * @param $expected
	 */
	public function properlyComputesAverage(iterable $input, $expected){
		$value = $this->average($input);
		$this->assertEquals($expected, $value);
//		$this->expectOutputString("");
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideAvgData(){
		return [
			[
				[],
				0,
			],
			[
				[1, 4, 4],
				3,
			],
			[
				[1, 2, 3],
				2,
			],
		];
	}
}
