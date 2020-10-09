<?php


namespace LazyCollection\Tests\Methods\Operators\Limiters;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class TakeWhileTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @param callable $predicate
	 * @return array
	 */
	public function takeWhile(iterable $it, callable $predicate){
		return Stream::fromIterable($it)
			->takeWhile($predicate)
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::takeWhile
	 * @dataProvider provideTakeWhileData
	 *
	 * @param iterable $input
	 * @param callable $predicate
	 * @param iterable $expected
	 */
	public function properlyTakesElementWhileThePredicateIsFulfilled(iterable $input, callable $predicate, iterable $expected){
		$result = $this->takeWhile($input, $predicate);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideTakeWhileData(){
		return [
			[
				[1, 2, 3, 4, 5, 6],
				function($e){ return $e < 5; },
				[1, 2, 3, 4],
			],
			[
				[2, 4, 3, 4],
				function($e){ return $e % 2 === 0; },
				[2, 4],
			],
		];
	}
}
