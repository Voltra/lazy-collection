<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class MinWithTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function minWith(iterable $it, callable $comparator){
		return Stream::fromIterable($it)->minWith($comparator);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::minWith
	 * @dataProvider provideMinWithData
	 *
	 * @param iterable $input
	 * @param callable $comparator
	 * @param $expected
	 */
	public function properlyComparesMin(iterable $input, callable $comparator, $expected){
		$value = $this->minWith($input, $comparator);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::minWith
	 * @dataProvider provideFailureData
	 *
	 * @param iterable $input
	 * @param callable $comparator
	 */
	public function properlyReturnsNullIfNoData(iterable $input, callable $comparator){
		$value = $this->minWith($input, $comparator);
		$this->assertNull($value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideMinWithData(){
		return [
			[
				[1, 2, 3],
				function($lhs, $rhs){ return $lhs <=> $rhs; },
				1,
			],
			[
				[1, 2, 3],
				function($lhs, $rhs){ return $rhs <=> $lhs; },
				3,
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
