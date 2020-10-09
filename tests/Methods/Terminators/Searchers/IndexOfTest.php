<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class IndexOfTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function indexOf(iterable $it, $elem){
		return Stream::fromIterable($it)
			->indexOf($elem);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::indexOf
	 * @dataProvider provideIndexOfData
	 *
	 * @param iterable $input
	 * @param $needle
	 * @param int $expected
	 */
	public function returnsCorrectIndexIfExist(iterable $input, $needle, int $expected){
		$value = $this->indexOf($input, $needle);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @covers \LazyCollection\Stream::indexOf
	 * @dataProvider provideFailureData
	 *
	 * @param iterable $input
	 * @param $needle
	 */
	public function returnsMinusOnIfDoesNotExist(iterable $input, $needle){
		$value = $this->indexOf($input, $needle);
		$expected = -1;
		$this->assertEquals($expected, $value);
	}


	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideIndexOfData(){
		return [
			[
				[1, 2, 3], // $input
				3, // $needle
				2, // $expected
			],
		];
	}

	public function provideFailureData(){
		return [
			[
				[], // $input
				3, // $needle
			],
			[
				[1, 8, 22],
				2,
			],
		];
	}
}
