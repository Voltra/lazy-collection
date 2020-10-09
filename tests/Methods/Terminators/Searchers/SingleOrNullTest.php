<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class SingleOrNullTest extends \LazyCollection\Tests\PHPUnit
{
	/**
	 * @var SingleTest
	 */
	protected $singleProvider;

	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->singleProvider = new SingleTest($name, $data, $dataName);
	}

	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function singleOrNull(iterable $it, callable $predicate = null){
		return Stream::fromIterable($it)->singleOrNull($predicate);
	}


	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover       \LazyCollection\Stream::singleOrNull
	 * @dataProvider provideSingleOrNullData
	 *
	 * @param iterable $input
	 * @param callable|null $predicate
	 * @param $expected
	 */
	public function properlyRetrieveTheSingleElement(iterable $input, ?callable $predicate, $expected){
		$value = $this->singleOrNull($input, $predicate);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @cover       \LazyCollection\Stream::singleOrNull
	 * @dataProvider provideFailureData
	 *
	 * @param iterable $input
	 * @param callable|null $predicate
	 */
	public function returnsDefaultOnFail(iterable $input, ?callable $predicate){
		$value = $this->singleOrNull($input, $predicate);
		$this->assertNull($value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideSingleOrNullData(){
		return $this->singleProvider->provideSingleData();
	}

	public function provideFailureData(){
		return [
			[
				[], // $input
				null, // $predicate
			],
			[
				[1, 3, 5],
				function($x){ return $x % 2 === 0; },
			],
		];
	}
}
