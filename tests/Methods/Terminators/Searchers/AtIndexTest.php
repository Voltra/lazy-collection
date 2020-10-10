<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Exceptions\NotFoundException;
use LazyCollection\Stream;

class AtIndexTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function atIndex(iterable $it, int $index){
		return Stream::fromIterable($it)->atIndex($index);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover       \LazyCollection\Stream::last
	 * @dataProvider provideAtIndexData
	 *
	 * @param iterable $input
	 * @param int $index
	 * @param $expected
	 */
	public function properlyReturnItemAtIndex(iterable $input, int $index, $expected){
		$value = $this->atIndex($input, $index);
		$this->assertEquals($expected, $value);
	}


	/**
	 * @test
	 * @cover       \LazyCollection\Stream::last
	 * @dataProvider provideAtIndexFailData
	 *
	 * @param iterable $input
	 * @param int $index
	 */
	public function failsProperlyIfThereIsNoItem(iterable $input, int $index){
		$this->expectException(NotFoundException::class);
		$this->atIndex($input, $index);
	}

	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideAtIndexData(){
		return [
			[
				[1], // $input
				0, // $index
				1, // $expected
			],
			[
				[1, 2, 3, 4, 5], // $input
				2, // $index
				3, // $expected
			],
			[
				[1, 2, 3, 4], // $input
				1, // $index
				2, // $expected
			],
		];
	}

	public function provideAtIndexFailData(){
		return [
			[
				[], // $input
				0, // $index
			],
			[
				[1, 3, 5],
				14,
			],
		];
	}
}
