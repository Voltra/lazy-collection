<?php


namespace LazyCollection\Tests\Methods\Operators\Extenders;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class ZipWithTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $lhs
	 * @param iterable $rhs
	 * @return array
	 */
	public function zipWith(iterable $lhs, iterable $rhs){
		return Stream::fromIterable($lhs)
			->zipWith(Stream::fromIterable($rhs))
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::zipWith
	 * @dataProvider provideZipData
	 *
	 * @param iterable $lhs
	 * @param iterable $rhs
	 * @param iterable $expected
	 */
	public function checkZipsProperly(iterable $lhs, iterable $rhs, iterable $expected){
		$result = $this->zipWith($lhs, $rhs);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideZipData(){
		return [
			[
				[1, 2, 3],
				[4, 5, 6],
				[
					[1, 4],
					[2, 5],
					[3, 6],
				],
			],
			[
				["key"],
				["value"],
				[
					["key", "value"],
				],
			],
			[
				[1, 2, 3],
				[4, 5],
				[
					[1, 4],
					[2, 5],
				],
			],
			[
				[4, 5],
				[1, 2, 3],
				[
					[4, 1],
					[5, 2],
				],
			],
		];
	}
}
