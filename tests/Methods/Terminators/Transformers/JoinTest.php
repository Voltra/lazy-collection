<?php


namespace LazyCollection\Tests\Methods\Terminators\Transformers;


use LazyCollection\Stream;

class JoinTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function join(iterable $it, array $options = []){
		return Stream::fromIterable($it)->join($options);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::join
	 * @dataProvider provideJoinData
	 *
	 * @param iterable $input
	 * @param string $expected
	 * @param array $options
	 */
	public function properlyJoinsToString(iterable $input, string $expected, array $options = []){
		$value = $this->join($input, $options);
		$this->assertEquals($expected, $value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideJoinData(){
		return [
			[
				[1, 2, 3],
				"[1, 2, 3]",
				[],
			],
			[
				["", ","],
				"[, ,]",
				[],
			],
			[
				[1, 2, 3],
				"{1,2,3}",
				[
					"prefix" => "{",
					"suffix" => "}",
					"separator" => ",",
				],
			],
		];
	}
}
