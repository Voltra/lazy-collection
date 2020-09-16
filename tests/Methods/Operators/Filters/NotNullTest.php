<?php


namespace LazyCollection\Tests\Methods\Operators\Filters;


use LazyCollection\Stream;
use LazyCollection\Tests\Globals\HelpersBasedTests;
use LazyCollection\Tests\Globals\HelpersTest;
use LazyCollection\Tests\PHPUnit;

class NotNullTest extends HelpersBasedTests
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @return array
	 */
	public function notNull(iterable $it){
		return Stream::fromIterable($it)
			->notNull()
			->toArray();
	}


	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::notNull
	 * @dataProvider provideMaybeNull
	 *
	 * @param $value
	 * @param bool $isNull
	 */
	public function properlyFiltersOutNulls($value, bool $isNull){
		$result = $this->notNull([$value]);
		$length = $isNull ? 0 : 1;
		$this->assertCount($length, $result);
	}

	/**
	 * @test
	 * @covers \LazyCollection\Stream::notNull
	 * @dataProvider provideArray
	 *
	 * @param array $input
	 * @param array $expected
	 */
	public function worksOnMultipleItems(array $input, array $expected){
		$result = $this->notNull($input);
		$this->assertEquals($expected, $result);
	}


	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideArray(){
		return [
			[
				[1,2],
				[1,2],
			],
			[
				[false, false],
				[false, false],
			],
			[
				[false, null],
				[false],
			],
		];
	}

	public function provideMaybeNull(){
		return $this->provider->provideMaybeNull();
	}
}
