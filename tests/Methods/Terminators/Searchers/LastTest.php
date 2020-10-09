<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Exceptions\NotFoundException;
use LazyCollection\Stream;

class LastTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function last(iterable $it, callable $predicate = null){
		return Stream::fromIterable($it)->last($predicate);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover       \LazyCollection\Stream::last
	 * @dataProvider provideLastData
	 *
	 * @param iterable $input
	 * @param callable|null $predicate
	 * @param $expected
	 */
	public function properlyReturnLast(iterable $input, ?callable $predicate, $expected){
		$value = $this->last($input, $predicate);
		$this->assertEquals($expected, $value);
	}


	/**
	 * @test
	 * @cover \LazyCollection\Stream::last
	 * @dataProvider provideLastFailData
	 *
	 * @param iterable $input
	 * @param callable|null $predicate
	 */
	public function failsProperlyIfThereIsNoItem(iterable $input, callable $predicate = null){
		$this->expectException(NotFoundException::class);
		$this->last($input, $predicate);
	}

	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideLastData(){
		return [
			[
				[1], // $input
				null, // $predicate
				1, // $expected
			],
			[
				[1, 2, 3, 4], // $input
				null, // $predicate
				4, // $expected
			],
			[
				[1,2,3], // $input
				function($x){ return $x % 2 !== 0; }, // $predicate
				3, // $expected
			],
		];
	}

	public function provideLastFailData(){
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
