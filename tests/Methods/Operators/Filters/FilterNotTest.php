<?php


namespace LazyCollection\Tests\Methods\Operators\Filters;


use LazyCollection\Helpers;
use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class FilterNotTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @param callable $predicate
	 * @return array
	 */
	public function filterNot(iterable $it, callable $predicate){
		return Stream::fromIterable($it)
			->filterNot($predicate)
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::filterNot
	 * @dataProvider provideDataSet
	 *
	 * @param iterable $it
	 * @param callable $predicate
	 * @param iterable $expected
	 */
	public function properlyFiltersOutElementsThatSatisfyThePredicate(iterable $it, callable $predicate, iterable $expected){
		$result = $this->filterNot($it, $predicate);
		$this->assertEquals($expected, $result);
	}


	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideDataSet(){
		return [
			[
				[1, 2, 3],
				function($e){ return $e % 2 === 0; },
				[1, 3],
			],
			[
				[1, 2, 3],
				function($e){ return $e % 2 !== 0; },
				[2],
			],
			[
				[1,2,3,4],
				[Helpers::class, "yes"],
				[],
			],
			[
				[1,2,3,4],
				[Helpers::class, "no"],
				[1,2,3,4],
			],
		];
	}
}
