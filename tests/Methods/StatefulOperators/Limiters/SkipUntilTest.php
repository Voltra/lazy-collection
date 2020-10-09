<?php


namespace LazyCollection\Tests\Methods\StatefulOperators\Limiters;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class SkipUntilTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @param callable $predicate
	 * @return array
	 */
	public function skipUntil(iterable $it, callable $predicate){
		return Stream::fromIterable($it)
			->skipUntil($predicate)
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::skipUntil
	 * @dataProvider provideSkipUntilData
	 *
	 * @param iterable $input
	 * @param callable $predicate
	 * @param iterable $expected
	 */
	public function properlySkipsElementUntilThePredicateIsFulfilled(iterable $input, callable $predicate, iterable $expected){
		$result = $this->skipUntil($input, $predicate);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideSkipUntilData(){
		return [
			[
				[1, 2, 3, 4, 5, 6],
				function($e){ return $e >= 5; },
				[5, 6],
			],
			[
				[2, 4, 3, 4],
				function($e){ return $e % 2 !== 0; },
				[3, 4],
			],
		];
	}
}
