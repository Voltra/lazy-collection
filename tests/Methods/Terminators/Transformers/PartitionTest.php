<?php


namespace LazyCollection\Tests\Methods\Terminators\Transformers;


use LazyCollection\Stream;

class PartitionTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function partition(iterable $it, callable $predicate){
		return Stream::fromIterable($it)->partition($predicate);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::partition
	 * @dataProvider providePartitionData
	 *
	 * @param iterable $input
	 * @param callable $predicate
	 * @param iterable $expected
	 */
	public function properlyPartitionsWithPredicate(iterable $input, callable $predicate, iterable $expected){
		$value = $this->partition($input, $predicate);
		$this->assertEquals($expected, $value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function providePartitionData(){
		return [
			[
				[1, 2, 3],
				function($x){ return $x % 2 === 0; },
				[
					[2],
					[1, 3],
				],
			],
		];
	}
}
