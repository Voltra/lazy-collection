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
	 * @covers \LazyCollection\Stream::indexOfFirst
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
}
