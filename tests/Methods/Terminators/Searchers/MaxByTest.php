<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class MaxByTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function maxBy(iterable $it, callable $mapper){
		return Stream::fromIterable($it)->maxBy($mapper);
	}


	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::maxBy
	 * @dataProvider provideMaxByData
	 *
	 * @param iterable $input
	 * @param callable $mapper
	 * @param $expected
	 */
	public function properlyReturnsMax(iterable $input, callable $mapper, $expected){
		$value = $this->maxBy($input, $mapper);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @covers \LazyCollection\Stream::maxBy
	 * @dataProvider provideFailureData
	 *
	 * @param iterable $input
	 * @param callable $mapper
	 */
	public function properlyReturnsNullIfEmpty(iterable $input, callable $mapper){
		$value = $this->maxBy($input, $mapper);
		$expected = null;
		$this->assertEquals($expected, $value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideMaxByData(){
		return [
			[
				[1, 5, 12, 21, 18, 9], // $input
				function($x){ return $x; }, // $mapper
				21, // $expected
			],
			[
				[1, 5, 12, 21, 18, 9],
				function($x){ return 1 / (float)$x; },
				1,
			],
		];
	}

	public function provideFailureData(){
		return [
			[
				[],
				function($x){ return $x; },
			],
		];
	}
}
