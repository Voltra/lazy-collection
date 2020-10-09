<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class LastOrNullTest extends \LazyCollection\Tests\PHPUnit
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
	public function lastOrNull(iterable $it, callable $predicate = null){
		return Stream::fromIterable($it)->lastOrNull($predicate);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::lastOrNull
	 * @dataProvider provideLastData
	 *
	 * @param iterable $input
	 * @param callable|null $predicate
	 * @param $expected
	 */
	public function returnsLastIfItExist(iterable $input, ?callable $predicate, $expected){
		$value = $this->lastOrNull($input, $predicate);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::lastOrNull
	 * @dataProvider provideFailLastData
	 *
	 * @param iterable $input
	 * @param callable|null $predicate
	 */
	public function returnsNullIfThereIsNoLast(iterable $input, ?callable $predicate){
		$value = $this->lastOrNull($input, $predicate);
		$this->assertNull($value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideLastData(){
		return $this->lastProvider->provideLastData();
	}

	public function provideFailLastData(){
		return [
			[
				[1, 2, 3],
				function($x){ return $x > 4; },
			],
		];
	}
}
