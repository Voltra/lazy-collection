<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class ContainsTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function contains_(iterable $it, $needle){
		return Stream::fromIterable($it)->contains($needle);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::contains
	 * @dataProvider provideContainsData
	 *
	 * @param iterable $input
	 * @param $needle
	 * @param bool $expected
	 */
	public function properlyChecksIfItContainsTheNeedle(iterable $input, $needle, bool $expected){
		$value = $this->contains_($input, $needle);
		$this->assertEquals($expected, $value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideContainsData(){
		return [
			[
				[1, 2, 3], // $input
				1, // $needle
				true, // $expected
			],
			[
				[1, 2, 3],
				4,
				false,
			],
			[
				[],
				42,
				false,
			],
			[
				[0],
				"0",
				false,
			],
		];
	}
}
