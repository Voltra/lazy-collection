<?php


namespace LazyCollection\Tests\Methods\Operators\Limiters;


use LazyCollection\Stream;

class SubStreamTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @param int $startIndex
	 * @param int $endIndex
	 * @return array
	 */
	public function subStream(iterable $it, int $startIndex, int $endIndex){
		return Stream::fromIterable($it)
			->subStream($startIndex, $endIndex)
			->toArray();
	}


	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::subStream
	 * @dataProvider provideSubStreamData
	 *
	 * @param iterable $input
	 * @param int $startIndex
	 * @param int $endIndex
	 * @param iterable $expected
	 */
	public function createProperSubstream(iterable $input, int $startIndex, int $endIndex, iterable $expected){
		$result = $this->subStream($input, $startIndex, $endIndex);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideSubStreamData(){
		return [
			[
				[1, 2, 3, 4, 5, 6, 7, 8],
				1, 3,
				[2, 3],
			],
			[
				[1, 2, 3, 4],
				3, 6,
				[4],
			],
			[
				[1, 2, 3, 4, 5, 6, 7, 8],
				42, 69,
				[],
			],
			[
				[1, 2, 3, 4, 5, 6, 7, 8],
				-1, 2,
				[1, 2, 3],
			],
			[
				[1, 2, 3, 4],
				1, -1,
				[],
			],
		];
	}
}
