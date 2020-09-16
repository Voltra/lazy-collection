<?php


namespace LazyCollection\Tests\Methods\StatefulOperators\Limiters;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class UniqueTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @return array
	 */
	public function unique(iterable $it){
		return Stream::fromIterable($it)
			->unique()
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers       \LazyCollection\Stream::unique
	 * @dataProvider provideUniquePayload
	 *
	 * @param iterable $input
	 * @param iterable $expected
	 */
	public function makeSureItUniquesByTheKey(iterable $input, iterable $expected){
		$result = $this->unique($input);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideUniquePayload(){
		return [
			[
				[1,2,1],
				[1,2],
			],
		];
	}
}
