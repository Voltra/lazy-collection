<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class MinTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function min(iterable $it){
		return Stream::fromIterable($it)->min();
	}


	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::min
	 * @dataProvider provideMinData
	 *
	 * @param iterable $input
	 * @param $expected
	 */
	public function properlyReturnMin(iterable $input, $expected){
		$value = $this->min($input);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::min
	 * @dataProvider provideFailureData
	 *
	 * @param iterable $input
	 */
	public function properlyReturnNullIfEmpty(iterable $input){
		$value = $this->min($input);
		$this->assertNull($value);
	}


	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideMinData(){
		return [
			[
				[1, 2, 3], // $input
				1, // $expected
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
