<?php


namespace LazyCollection\Tests\Methods\Terminators\Transformers;


use LazyCollection\Stream;

class ToJsonTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function toJSON(iterable $it){
		return Stream::fromIterable($it)->toJSON();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::toJSON
	 * @dataProvider provideArrays
	 *
	 * @param array $arr
	 */
	public function properlyConvertsToJsonString(array $arr){
		$expected = json_encode($arr);
		$value = $this->toJSON($arr);
		$this->assertJsonStringEqualsJsonString($expected, $value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideArrays(){
		return [
			[
				[1, 2, 3],
				["key" => "value"],
			]
		];
	}
}
