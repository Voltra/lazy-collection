<?php


namespace LazyCollection\Tests\Methods\Terminators\Checks;


use LazyCollection\Stream;

class NotAllTest extends CheckTest
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public static $method = "notAll";

	public function notAll(iterable $it, callable $predicate){
		return Stream::fromIterable($it)->notAll($predicate);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::notAll
	 * @dataProvider provideNotAllData
	 *
	 * @param iterable $input
	 * @param callable $predicate
	 * @param bool $expected
	 */
	public function properlyChecksWithPredicate(iterable $input, callable $predicate, bool $expected)
	{
		parent::properlyChecksWithPredicate($input, $predicate, $expected);
	}


	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideNotAllData(){
		return [
			[
				[1, 2, 3],
				function($x){ return $x < 0; },
				true,
			],
			[
				[1, 2, 3],
				function($x){ return $x % 2 === 0; },
				true,
			],
			[
				[4, 6, 10],
				function($x){ return $x % 2 === 0; },
				false,
			],
		];
	}
}
