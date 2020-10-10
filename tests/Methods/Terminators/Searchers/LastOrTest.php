<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class LastOrTest extends \LazyCollection\Tests\PHPUnit
{
	/**
	 * @var LastTest
	 */
	protected $lastProvider;

	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->lastProvider = new LastTest($name, $data, $dataName);
	}

	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function lastOr(iterable $it, $default, callable $predicate = null){
		return Stream::fromIterable($it)->lastOr($default, $predicate);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::lastOr
	 * @dataProvider provideSuccessfulLastOrData
	 *
	 * @param iterable $input
	 * @param callable|null $predicate
	 * @param $expected
	 */
	public function returnsLastIfItExist(iterable $input, ?callable $predicate, $expected){
		$value = $this->lastOr($input, null, $predicate);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::lastOr
	 * @dataProvider provideFailureLastOrData
	 *
	 * @param iterable $input
	 * @param $default
	 * @param callable|null $predicate
	 */
	public function returnsDefaultIfNoLastExist(iterable $input, $default, ?callable $predicate){
		$value = $this->lastOr($input, $default, $predicate);
		$this->assertEquals($default, $value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideSuccessfulLastOrData(){
		return $this->lastProvider->provideLastData();
	}

	public function provideFailureLastOrData(){
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
