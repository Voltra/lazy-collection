<?php


namespace LazyCollection\Tests\Methods\Terminators\Transformers;


use LazyCollection\Stream;

class SumByTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function sumBy(iterable $it, callable $mapper, $init = null){
		return Stream::fromIterable($it)->sumBy($mapper, $init);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::sumBy
	 * @dataProvider provideSumByData
	 *
	 * @param iterable $input
	 * @param callable $mapper
	 * @param $expected
	 */
	public function properlySumsTheMappedData(iterable $input, callable $mapper, $expected){
		$value = $this->sumBy($input, $mapper);
		$this->assertEquals($expected, $value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideSumByData(){
		return [
			[
				["1", "2", "3"],
				function($x){ return (int)$x; },
				6,
			],
			[
				[1, 2, 3],
				function($x){ return $x + 4; },
				18,
			],
		];
	}
}
