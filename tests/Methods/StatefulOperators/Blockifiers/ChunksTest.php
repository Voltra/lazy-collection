<?php


namespace LazyCollection\Tests\Methods\StatefulOperators\Blockifiers;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class ChunksTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @param int $maxBucketSize
	 * @return array
	 */
	public function chunks(iterable $it, int $maxBucketSize){
		return Stream::fromIterable($it)
			->chunks($maxBucketSize)
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::chunks
	 * @dataProvider provideChunkyArrays
	 *
	 * @param iterable $input
	 * @param int $maxBucketSize
	 * @param iterable $expected
	 */
	public function putsInChunksProperly(iterable $input, int $maxBucketSize, iterable $expected){
		$result = $this->chunks($input, $maxBucketSize);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideChunkyArrays(){
		return [
			[
				[1,2,3,4,5,6],
				2,
				[
					[1,2],
					[3,4],
					[5,6],
				],
			],
			[
				[1,2,3,4,5,6,7],
				2,
				[
					[1,2],
					[3,4],
					[5,6],
					[7],
				],
			],
			[
				[],
				2,
				[],
			],
		];
	}
}
