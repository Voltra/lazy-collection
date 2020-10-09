<?php


namespace LazyCollection\Tests\Methods\Operators\Limiters;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class TakeUntilTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @param callable $predicate
	 * @return array
	 */
	public function takeUntil(iterable $it, callable $predicate){
		return Stream::fromIterable($it)
			->takeUntil($predicate)
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::takeUntil
	 * @dataProvider provideTakeUntilData
	 *
	 * @param iterable $input
	 * @param callable $predicate
	 * @param iterable $expected
	 */
	public function properlyTakesElementUntilThePredicateIsFulfilled(iterable $input, callable $predicate, iterable $expected){
		$result = $this->takeUntil($input, $predicate);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideTakeUntilData(){
		return [
			[
				[1, 2, 3, 4, 5, 6],
				function($e){ return $e >= 5; },
				[1, 2, 3, 4],
			],
			[
				[2, 4, 3, 4],
				function($e){ return $e % 2 !== 0; },
				[2, 4],
			],
		];
	}
}
