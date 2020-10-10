<?php

namespace LazyCollection\Tests\Factories;

use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class SplitByTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param string $str
	 * @param string $sep
	 * @param bool $rmEmpty
	 * @return array
	 */
	public function split(string $str, string $sep = "", bool $rmEmpty = true){
		return Stream::splitBy($str, $sep, $rmEmpty)->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover       \LazyCollection\Stream::splitBy
	 * @dataProvider provideSplitData
	 *
	 * @param string $str
	 * @param string $sep
	 * @param bool $rmEmpty
	 * @param array $expected
	 */
	public function splittingGivesTheCorrectParts(string $str, string $sep, bool $rmEmpty, array $expected){
		$result = $this->split($str, $sep, $rmEmpty);
		$this->assertEquals($expected, $result);
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::splitBy
	 * @dataProvider provideNoOccurrences
	 *
	 * @param string $str
	 * @param string $sep
	 * @param bool $rmEmpty
	 */
	public function splittingWithNoOccurenceOfSeparatorGivesBackSingleElementStream(string $str, string $sep, bool $rmEmpty){
		$result = $this->split($str, $sep, $rmEmpty);
		$this->assertEquals([$str], $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideNoOccurrences(){
		return [
			[
				"1,2,3",
				";",
				true,
			],
			[
				"1,2,3",
				";",
				false,
			],
		];
	}

	public function provideSplitData(){
		return [
			[
				"1,2,3",
				",",
				true,
				["1", "2", "3"],
			],

			[
				"1,2,3,",
				",",
				true,
				["1", "2", "3"],
			],

			[
				"1,2,3,",
				",",
				false,
				["1", "2", "3", ""],
			],

			[
				"1,2,,3,",
				",",
				true,
				["1", "2", "3"],
			],

			[
				"1,2,,3,",
				",",
				false,
				["1", "2", "", "3", ""],
			],
		];
	}
}
