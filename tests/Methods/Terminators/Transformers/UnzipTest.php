<?php


namespace LazyCollection\Tests\Methods\Terminators\Transformers;


use LazyCollection\Stream;

class UnzipTest extends \LazyCollection\Tests\PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function unzip(iterable $it){
		return Stream::fromIterable($it)->unzip();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::unzip
	 * @dataProvider provideUnzipData
	 *
	 * @param iterable $input
	 * @param iterable $expected
	 */
	public function unzipsProperly(iterable $input, iterable $expected){
		$value = $this->unzip($input);
		$this->assertEquals($expected, $value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideUnzipData(){
		return [
			[
				[
					[1, 2],
					[3, 4],
				],
				[
					[1, 3],
					[2, 4],
				],
			],
		];
	}
}
