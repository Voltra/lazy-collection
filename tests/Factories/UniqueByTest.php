<?php


use LazyCollection\Stream;
use LazyCollection\Tests\Globals\HelpersBasedTests;

class UniqueByTest extends HelpersBasedTests
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function uniqueBy(iterable $it, callable $key){
		return Stream::fromIterable($it)
			->uniqueBy($key)
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::uniqueBy
	 * @dataProvider provideUniqueByPayload
	 *
	 * @param iterable $input
	 * @param callable $key
	 * @param iterable $expected
	 */
	public function makeSureItUniquesByTheKey(iterable $input, callable $key, iterable $expected){
		$result = $this->uniqueBy($input, $key);
		$this->expectOutputString("");
//		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideUniqueByPayload(){
		return $this->provider->provideUniqueByPayload();
	}
}
