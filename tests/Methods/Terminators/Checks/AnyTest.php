<?php


namespace LazyCollection\Tests\Methods\Terminators\Checks;


use LazyCollection\Stream;

class AnyTest extends CheckTest
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public static $method = "any_";

	public function any_(iterable $it, callable $predicate){
		return Stream::fromIterable($it)->any($predicate);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::any
	 * @dataProvider provideAnyData
	 *
	 * @param iterable $input
	 * @param callable $predicate
	 * @param bool $expected
	 */
	public function properlyChecksWithPredicate(iterable $input, callable $predicate, bool $expected){
		parent::properlyChecksWithPredicate($input, $predicate, $expected);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideAnyData(){
		return [
			[
				[1, 2, 3],
				function($x){ return $x < 0; },
				false,
			],
			[
				[1, 2, 3],
				function($x){ return $x % 2 === 0; },
				true,
			],
			[
				[4, 6, 10],
				function($x){ return $x % 2 === 0; },
				true,
			],
		];
	}
}
