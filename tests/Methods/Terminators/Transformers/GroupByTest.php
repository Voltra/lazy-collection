<?php


namespace LazyCollection\Tests\Methods\Terminators\Transformers;


use LazyCollection\Stream;

class GroupByTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function groupBy(iterable $it, callable $keyFactory){
		return Stream::fromIterable($it)->groupBy($keyFactory);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::groupBy
	 * @dataProvider provideGroupByData
	 *
	 * @param iterable $input
	 * @param callable $keyExtractor
	 * @param iterable $expected
	 */
	public function properlyGroupsData(iterable $input, callable $keyExtractor, iterable $expected){
		$value = $this->groupBy($input, $keyExtractor);
		$this->assertEquals($expected, $value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideGroupByData(){
		return [
			[
				[1, 2, 3],
				function($value, $key){ return $value % 2 == 0 ? "even" : "odd"; },
				[
					"even" => [2],
					"odd" => [1, 3],
				],
			],
		];
	}
}
