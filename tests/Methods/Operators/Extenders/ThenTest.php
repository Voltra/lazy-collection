<?php


namespace LazyCollection\Tests\Methods\Operators\Extenders;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class ThenTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $lhs
	 * @param iterable $rhs
	 * @return array
	 */
	public function then(iterable $lhs, iterable $rhs){
		return Stream::fromIterable($lhs)
			->then($rhs)
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::then
	 * @dataProvider provideData
	 *
	 * @param iterable $first
	 * @param iterable $then
	 * @param iterable $expected
	 */
	public function checkItConcatsProperly(iterable $first, iterable $then, iterable $expected){
		$result = $this->then($first, $then);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideData(){
		return [
			[
				[1,2,3],
				[4,5],
				[1,2,3,4,5],
			],
			[
				[],
				[1,2],
				[1,2],
			],
			[
				[1,2],
				[],
				[1,2],
			],
		];
	}
}
