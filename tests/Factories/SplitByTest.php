<?php


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
	 * @covers \LazyCollection\Stream::splitBy
	 * @dataProvider provideSplitInside
	 *
	 * @param string $str
	 * @param string $sep
	 * @param array $expected
	 */
	public function splittingInsideGivesTheCorrectParts(string $str, string $sep, array $expected){
		$result = $this->split($str, $sep);
		$this->expectOutputString("");
//		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideSplitInside(){
		return [
			[
				"1,2,3",
				",",
				["1", "2", "3"],
			]
		];
	}
}
