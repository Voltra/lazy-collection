<?php


namespace LazyCollection\Tests\Methods\Terminators\Consumers;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class ForEachTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @param callable $callback
	 */
	public function forEach(iterable $it, callable $callback){
		Stream::fromIterable($it)
			->forEach($callback);
	}


	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover       \LazyCollection\Stream::forEach
	 * @dataProvider provideArrays
	 *
	 * @param array $input
	 */
	public function properlyCallsTheCallbackOnEachElement(array $input){
		$count = count($input);
		$callback = $this->createCallbackMock($this->exactly($count));
		$this->forEach($input, $callback);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideArrays(){
		return [
			[
				[1, 2, 3],
			],
			[
				["key" => "value"],
			],
		];
	}
}
