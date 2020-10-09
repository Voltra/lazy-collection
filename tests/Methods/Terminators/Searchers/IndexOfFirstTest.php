<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class IndexOfFirstTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function indexOfFirst(iterable $it, callable $predicate){
		return Stream::fromIterable($it)->indexOfFirst($predicate);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::indexOfFirst
	 * @dataProvider provideIndexOfFirstData
	 *
	 * @param iterable $input
	 * @param callable $predicate
	 * @param int $expected
	 */
	public function returnsProperIndex(iterable $input, callable $predicate, int $expected){
		$value = $this->indexOfFirst($input, $predicate);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::indexOfFirst
	 * @dataProvider provideFailureData
	 *
	 * @param iterable $input
	 * @param callable $predicate
	 */
	public function returnsMinusOneOnFailure(iterable $input, callable $predicate){
		$value = $this->indexOfFirst($input, $predicate);
		$expected = -1;
		$this->assertEquals($expected, $value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideIndexOfFirstData(){
		return [
			[
				[1, 2, 3], // $input
				function($x){ return $x < 4; }, // $predicate
				0, // $expected
			],
		];
	}

	public function provideFailureData(){
		return [
			[
				[], // $input
				function($x){ return true; }, // $predicate
			],
			[
				[1, 3, 5], // $input
				function($x){ return $x % 2 === 0; }, // $predicate
			],
		];
	}
}
