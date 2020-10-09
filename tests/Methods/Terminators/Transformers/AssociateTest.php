<?php


namespace LazyCollection\Tests\Methods\Terminators\Transformers;


use LazyCollection\Stream;

class AssociateTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function associate(iterable $it, callable $factory){
		return Stream::fromIterable($it)->associate($factory);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::associate
	 * @dataProvider provideAssociateData
	 *
	 * @param iterable $input
	 * @param callable $factory
	 * @param iterable $expected
	 */
	public function properlyAssociates(iterable $input, callable $factory, iterable $expected){
		$value = $this->associate($input, $factory);
		$this->assertEquals($expected, $value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideAssociateData(){
		return [
			[
				[1, 2, 3],
				function($value, $key){ return [$key, $value * 2]; },
				[2, 4, 6],
			]
		];
	}
}
