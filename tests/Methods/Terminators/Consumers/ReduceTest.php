<?php


namespace LazyCollection\Tests\Methods\Terminators\Consumers;


use LazyCollection\Stream;

class ReduceTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function reduce(iterable $it, callable $reducer, $init = null){
		return Stream::fromIterable($it)
			->reduce($reducer, $init);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::reduce
	 * @dataProvider provideReduceData
	 *
	 * @param iterable $it
	 * @param callable $reducer
	 * @param $init
	 * @param $expected
	 */
	public function correctlyReducesTheData(iterable $it, callable $reducer, $init, $expected){
		$value = $this->reduce($it, $reducer, $init);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::reduce
	 * @dataProvider provideReduceData
	 *
	 * @param array $arr
	 * @param callable $reducer
	 * @param null $init
	 */
	public function callsTheReducerTheRightAmountOfTimes(array $arr, callable $reducer, $init = null){
		$length = count($arr);
		$count = is_null($init) ? $length - 1 : $length;
		$callback = $this->createCallbackMock($this->exactly($count), null, 42);
		$this->reduce($arr, $callback, $init);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideReduceData(){
		return [
			[
				[1, 2, 3],
				function($acc, $e){ return $acc + $e; },
				null,
				6,
			],
			[
				[1, 2, 3, 10, 11],
				function($acc, $e){ return $acc + $e; },
				42,
				69,
			],
		];
	}
}
