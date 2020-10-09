<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class MaxTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function max(iterable $it){
		return Stream::fromIterable($it)->max();
	}


	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::max
	 * @dataProvider provideMaxData
	 *
	 * @param iterable $input
	 * @param $expected
	 */
	public function properlyReturnMax(iterable $input, $expected){
		$value = $this->max($input);
		$this->assertEquals($expected, $value);
	}


	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideMaxData(){
		return [
			[
				[1, 2, 3], // $input
				3, // $expected
			],
		];
	}

	public function provideFailureData(){
		return [
			[
				[],
				3,
			],
		];
	}
}
