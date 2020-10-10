<?php

namespace LazyCollection\Tests\Factories;

use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class SplitByRegexTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param string $str
	 * @param string $re
	 * @param bool $rmEmpty
	 * @return array
	 */
	public function splitByRegex(string $str, string $re, bool $rmEmpty){
		return Stream::splitByRegex($str, $re, $rmEmpty)->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover       \LazyCollection\Stream::splitByRegex
	 * @dataProvider provideSplitData
	 *
	 * @param string $str
	 * @param string $re
	 * @param bool $rmEmpty
	 * @param array $expected
	 */
	public function splittingGivesTheCorrectParts(string $str, string $re, bool $rmEmpty, array $expected){
		$result = $this->splitByRegex($str, $re, $rmEmpty);
		$this->assertEquals($expected, $result);
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::splitByRegex
	 * @dataProvider provideNoOccurrences
	 *
	 * @param string $str
	 * @param string $sep
	 * @param bool $rmEmpty
	 */
	public function splittingWithNoOccurenceOfSeparatorGivesBackSingleElementStream(string $str, string $sep, bool $rmEmpty){
		$result = $this->splitByRegex($str, $sep, $rmEmpty);
		$this->assertEquals([$str], $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideNoOccurrences(){
		return [
			[
				"1,2,3",
				"/\s*;\s*/",
				true,
			],
			[
				"1,2,3",
				"/\s*;\s*/",
				false,
			],
		];
	}

	public function provideSplitData(){
		return [
			[
				"1  , 2, 3",
				"/\s*,\s*/",
				true,
				["1", "2", "3"],
			],

			[
				"1, 2  , 3 ,  ",
				"/\s*,\s*/",
				true,
				["1", "2", "3"],
			],

			[
				"1, 2 , 3,",
				"/\s*,\s*/",
				false,
				["1", "2", "3", ""],
			],

			[
				"1 ,  2,   , 3,",
				"/\s*,\s*/",
				true,
				["1", "2", "3"],
			],

			[
				"1,2,,3,",
				"/\s*,\s*/",
				false,
				["1", "2", "", "3", ""],
			],
		];
	}
}
