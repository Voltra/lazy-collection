<?php


namespace LazyCollection\Tests\Methods\Terminators\Checks;


use LazyCollection\Stream;

class AllTest extends CheckTest
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public static $method = "all";

	public function all(iterable $it, callable $predicate){
		return Stream::fromIterable($it)->all($predicate);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::all
	 * @dataProvider provideAllData
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
	public function provideAllData(){
		return [
			[
				[1, 2, 3],
				function($x){ return $x % 2 !== 0; },
				false,
			],
			[
				[1, 2, 3],
				function($x){ return $x < 5; },
				true,
			],
		];
	}
}
