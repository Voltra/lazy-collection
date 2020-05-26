<?php


namespace LazyCollection\Tests\Factories;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class FromIterableTest extends PHPUnit {
	/**
	 * @test empty iterable makes empty stream
	 * @covers Stream::fromIterable
	 */
	public function emptyIterableMakesEmptyStream(){
		$it = [];
		$stream = Stream::fromIterable($it);
		foreach ($stream as $value){
			self::fail("Expected empty stream, got a value");
		}

		self::assertTrue(true);
	}
}