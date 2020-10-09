<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class MaxWithTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function maxWith(iterable $it, callable $comparator){
		return Stream::fromIterable($it)->maxWith($comparator);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::maxWith
	 * @dataProvider provideMaxWithData
	 *
	 * @param iterable $input
	 * @param callable $comparator
	 * @param $expected
	 */
	public function properlyComparesMax(iterable $input, callable $comparator, $expected){
		$value = $this->maxWith($input, $comparator);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::maxWith
	 * @dataProvider provideFailureData
	 *
	 * @param iterable $input
	 * @param callable $comparator
	 */
	public function properlyReturnsNullIfNoData(iterable $input, callable $comparator){
		$value = $this->maxWith($input, $comparator);
		$this->assertNull($value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideMaxWithData(){
		return [
			[
				[1, 2, 3],
				function($lhs, $rhs){ return $lhs <=> $rhs; },
				3,
			],
			[
				[1, 2, 3],
				function($lhs, $rhs){ return $rhs <=> $lhs; },
				1,
			],
		];
	}

	public function provideFailureData(){
		return [
			[
				[],
				function($lhs, $rhs){ return $lhs <=> $rhs; },
			],
		];
	}
}
