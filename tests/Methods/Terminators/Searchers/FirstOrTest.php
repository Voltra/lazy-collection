<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class FirstOrTest extends \LazyCollection\Tests\PHPUnit
{
	/**
	 * @var FirstTest
	 */
	protected $firstProvider;

	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->firstProvider = new FirstTest($name, $data, $dataName);
	}

	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function firstOr(iterable $it, $default, callable $predicate = null){
		return Stream::fromIterable($it)->firstOr($default, $predicate);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::firstOr
	 * @dataProvider provideSuccessfulFirstOrData
	 *
	 * @param iterable $input
	 * @param callable|null $predicate
	 * @param $expected
	 */
	public function returnsFirstIfItExist(iterable $input, ?callable $predicate, $expected){
		$value = $this->firstOr($input, null, $predicate);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::firstOr
	 * @dataProvider provideFailureFirstOrData
	 *
	 * @param iterable $input
	 * @param $default
	 * @param callable|null $predicate
	 */
	public function returnsDefaultIfNoFirstExist(iterable $input, $default, ?callable $predicate){
		$value = $this->firstOr($input, $default, $predicate);
		$this->assertEquals($default, $value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideSuccessfulFirstOrData(){
		return $this->firstProvider->provideFirstData();
	}

	public function provideFailureFirstOrData(){
		return [
			[
				[],
				42,
				null,
			],
			[
				[],
				69,
				function($x){ return $x % 2 === 0; },
			],
			[
				[1, 3],
				2,
				function($x){ return $x % 2 === 0; },
			],
		];
	}
}
