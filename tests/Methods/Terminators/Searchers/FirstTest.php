<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Exceptions\NotFoundException;
use LazyCollection\Stream;

class FirstTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function first(iterable $it, callable $predicate = null){
		return Stream::fromIterable($it)->first($predicate);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover       \LazyCollection\Stream::first
	 * @dataProvider provideFirstData
	 *
	 * @param iterable $input
	 * @param callable|null $predicate
	 * @param $expected
	 */
	public function properlyReturnFirst(iterable $input, ?callable $predicate, $expected){
		$value = $this->first($input, $predicate);
		$this->assertEquals($expected, $value);
	}


	/**
	 * @test
	 * @cover \LazyCollection\Stream::first
	 * @dataProvider provideFirstFailData
	 *
	 * @param iterable $input
	 * @param callable|null $predicate
	 */
	public function failsProperlyIfThereIsNoItem(iterable $input, callable $predicate = null){
		$this->expectException(NotFoundException::class);
		$this->first($input, $predicate);
	}

	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideFirstData(){
		return [
			[
				[1], // $input
				null, // $predicate
				1, // $expected
			],
			[
				[1,2,3], // $input
				function($x){ return $x % 2 === 0; }, // $predicate
				2, // $expected
			],
		];
	}

	public function provideFirstFailData(){
		return [
			[
				[],
			],
			[
				[1, 3, 5],
				function($x){ return $x % 2 === 0; },
			],
		];
	}
}
