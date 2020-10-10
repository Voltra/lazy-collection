<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Exceptions\NotFoundException;
use LazyCollection\Stream;

class SingleTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function single(iterable $it, callable $predicate = null){
		return Stream::fromIterable($it)->single($predicate);
	}


	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover       \LazyCollection\Stream::single
	 * @dataProvider provideSingleData
	 *
	 * @param iterable $input
	 * @param callable|null $predicate
	 * @param $expected
	 */
	public function properlyRetrieveTheSingleElement(iterable $input, ?callable $predicate, $expected){
		$value = $this->single($input, $predicate);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::single
	 * @dataProvider provideFailureData
	 *
	 * @param iterable $input
	 * @param callable|null $predicate
	 */
	public function throwsIfNoneOrMoreThanOne(iterable $input, ?callable $predicate){
		$this->expectException(NotFoundException::class);
		$this->single($input, $predicate);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideSingleData(){
		return [
			[
				[1, 2, 3], // $input
				function($x){ return $x % 2 === 0; }, // $predicate
				2, // $expected
			],
			[
				[1],
				null,
				1,
			],
		];
	}

	public function provideFailureData(){
		return [
			[
				[],
				null,
			],
			[
				[1, 3, 5],
				function($x){ return $x % 2 === 0; },
			],
		];
	}
}
