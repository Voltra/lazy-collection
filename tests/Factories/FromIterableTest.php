<?php


namespace LazyCollection\Tests\Factories;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;
use stdClass;

class FromIterableTest extends PHPUnit {
	/**
	 * @test empty iterable makes empty stream
	 * @cover \LazyCollection\Stream::fromIterable
	 */
	public function emptyIterableMakesEmptyStream(){
		$it = [];
		$stream = Stream::fromIterable($it);
		foreach ($stream as $value){
			self::fail("Expected empty stream, got a value");
		}

		self::assertTrue(true);
	}

	/**
	 * @test size of stream is size of iterable
	 * @cover \LazyCollection\Stream::fromIterable
	 * @dataProvider provideIterableWithSize
	 */
	public function sizeOfStreamIsSizeOfIterable(iterable $it, int $size){
		$stream = Stream::fromIterable($it);
		$ssize = 0;
		foreach($stream as $_){
			++$ssize;
		}
		self::assertSame($size, $ssize);
	}

    /**
     * @test stream does not change keys
     * @cover \LazyCollection\Stream::fromIterable
     * @dataProvider provideAssociative
     * @param array $arr
     */
	public function streamDoesNotChangeKeys(array $arr){
		$expectedKeys = array_keys($arr);
		$stream = Stream::fromIterable($arr);
		$keys = [];
		foreach ($stream as $key => $value){
			$keys[] = $key;
		}
		self::assertSame($expectedKeys, $keys);
	}

    /**
     * @test can create from various iterables
     * @cover \LazyCollection\Stream::fromIterable
     * @dataProvider provideDifferentIterables
     * @param iterable $it
     */
	public function canCreateFromVariousIterables(iterable $it){
		Stream::fromIterable($it);
		self::assertTrue(true);
	}

    /******************************************************************************************************************\
     * TEST PROVIDERS
    \******************************************************************************************************************/
	public function provideIterableWithSize(): array {
		return [
			[
				[], 0
			],
			[
				[1,2], 2
			],
			[
				[1], 1
			],
			[
				[1,2,3,4,5], 5
			]
		];
	}

	public function provideAssociative(): array{
		return [
			[
				["key" => "value"],
			],
			[
				["key" => "value", "test" => "test"]
			],

			[
				["key" => "value", "test" => "test", "value" => "key"]
			],
		];
	}

	public function provideDifferentIterables(): array{
		return [
			[
				["key" => "value"],
			],
			[
				[1,2,3]
			],
		];
	}
}
