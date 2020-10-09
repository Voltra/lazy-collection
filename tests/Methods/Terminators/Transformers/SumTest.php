<?php


namespace LazyCollection\Tests\Methods\Terminators\Transformers;


use LazyCollection\Stream;

class SumTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function sum(iterable $it, $init = null){
		return Stream::fromIterable($it)->sum($init);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover       \LazyCollection\Stream::sum
	 * @dataProvider provideSumData
	 *
	 * @param iterable $input
	 * @param $expected
	 * @param $init
	 */
	public function sumsProperly(iterable $input, $expected, $init = null){
		$value = $this->sum($input, $init);
		$this->assertEquals($expected, $value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideSumData(){
		return [
			[
				[1,2,3],
				6,
			],
			[
				[],
				null,
			],
			[
				[1,2,3],
				12,
				6,
			],
			[
				[],
				0, // expected
				0, // init
			],
		];
	}
}
