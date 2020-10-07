<?php


namespace LazyCollection\Tests\Methods\Terminators\Transformers;


use LazyCollection\Helpers;
use LazyCollection\Stream;

class AssociateByTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function associateBy(iterable $it, callable $valueFactory = null, callable $keyFactory = null){
		return Stream::fromIterable($it)->associateBy($valueFactory, $keyFactory);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::associateBy
	 * @dataProvider provideAssociationByData
	 *
	 * @param iterable $it
	 * @param iterable $expected
	 * @param callable|null $valueFactory
	 * @param callable|null $keyFactory
	 */
	public function properlyAssociates(iterable $it, iterable $expected, callable $valueFactory = null, callable $keyFactory = null){
		$value = $this->associateBy($it, $valueFactory, $keyFactory);
		$this->assertEquals($expected, $value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideAssociationByData(){
		return [
			[
				[1, 2, 3],
				["key__1__0" => 1, "key__2__1" => 2, "key__3__2" => 3],
				null,
				function($value, $key){ return "key__{$value}__{$key}"; },
			],
			[
				[1, 2, 3],
				[1, 3, 5],
				function($value, $key){ return $value + $key; },
				null,
			],
			[
				[1, 2, 3],
				[1, 2, 3],
				null,
				null,
			],
		];
	}
}
