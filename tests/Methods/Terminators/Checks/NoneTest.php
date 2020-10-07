<?php


namespace LazyCollection\Tests\Methods\Terminators\Checks;


use LazyCollection\Stream;

class NoneTest extends CheckTest
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public static $method = "none";

	public function none(iterable $it, callable $predicate){
		return Stream::fromIterable($it)->none($predicate);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::none
	 * @dataProvider provideNoneData
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
	public function provideNoneData(){
		return [
			[
				[1, 2, 3],
				function($x){ return $x < 0; },
				true,
			],
			[
				[1, 2, 3],
				function($x){ return $x % 2 === 0; },
				false,
			],
		];
	}
}
