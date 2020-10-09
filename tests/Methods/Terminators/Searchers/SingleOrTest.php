<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Exceptions\NotFoundException;
use LazyCollection\Stream;

class SingleOrTest extends \LazyCollection\Tests\PHPUnit
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
	public function singleOr(iterable $it, $default, callable $predicate = null){
		return Stream::fromIterable($it)->singleOr($default, $predicate);
	}


	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover       \LazyCollection\Stream::singleOr
	 * @dataProvider provideSingleOrData
	 *
	 * @param iterable $input
	 * @param callable|null $predicate
	 * @param $expected
	 */
	public function properlyRetrieveTheSingleElement(iterable $input, ?callable $predicate, $expected){
		$value = $this->singleOr($input, null, $predicate);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @cover       \LazyCollection\Stream::singleOr
	 * @dataProvider provideFailureData
	 *
	 * @param iterable $input
	 * @param $expected
	 * @param callable|null $predicate
	 */
	public function returnsDefaultOnFail(iterable $input, $expected, ?callable $predicate){
		$value = $this->singleOr($input, $expected, $predicate);
		$this->assertEquals($expected, $value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideSingleOrData(){
		return $this->singleProvider->provideSingleData();
	}

	public function provideFailureData(){
		return [
			[
				[], // $input
				42, // $expected
				null, // $predicate
			],
			[
				[1, 3, 5],
				2,
				function($x){ return $x % 2 === 0; },
			],
		];
	}
}
