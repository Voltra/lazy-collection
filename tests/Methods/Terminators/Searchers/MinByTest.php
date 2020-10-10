<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class MinByTest extends \LazyCollection\Tests\PHPUnit
{
	/**
	 * @var MaxByTest
	 */
	protected $maxByProvider;

	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->maxByProvider = new MaxByTest($name, $data, $dataName);
	}

	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function minBy(iterable $it, callable $mapper){
		return Stream::fromIterable($it)->minBy($mapper);
	}


	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::minBy
	 * @dataProvider provideMinByData
	 *
	 * @param iterable $input
	 * @param callable $mapper
	 * @param $expected
	 */
	public function properlyReturnsMin(iterable $input, callable $mapper, $expected){
		$value = $this->minBy($input, $mapper);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::minBy
	 * @dataProvider provideFailureData
	 *
	 * @param iterable $input
	 * @param callable $mapper
	 */
	public function properlyReturnsNullIfEmpty(iterable $input, callable $mapper){
		$value = $this->minBy($input, $mapper);
		$this->assertNull($value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideMinByData(){
		return [
			[
				[1, 5, 12, 21, 18, 9], // $input
				function($x){ return $x; }, // $mapper
				1, // $expected
			],
			[
				[1, 5, 12, 21, 18, 9],
				function($x){ return 1 / (float)$x; },
				21,
			],
		];
	}

	public function provideFailureData(){
		return [
			[
				[],
				function($x){ return $x; },
			],
		];
	}
}
