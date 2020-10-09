<?php


namespace LazyCollection\Tests\Methods\Operators\Limiters;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class SkipWhileTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @param callable $predicate
	 * @return array
	 */
	public function skipWhile(iterable $it, callable $predicate){
		return Stream::fromIterable($it)
			->skipWhile($predicate)
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::skipWhile
	 * @dataProvider provideSkipWhileData
	 *
	 * @param iterable $input
	 * @param callable $predicate
	 * @param iterable $expected
	 */
	public function properlySkipsElementWhileThePredicateIsFulfilled(iterable $input, callable $predicate, iterable $expected){
		$result = $this->skipWhile($input, $predicate);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideSkipWhileData(){
		return [
			[
				[1, 2, 3, 4, 5, 6],
				function($e){ return $e < 5; },
				[5, 6],
			],
			[
				[2, 4, 3, 4],
				function($e){ return $e % 2 === 0; },
				[3, 4],
			],
		];
	}
}
