<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class FirstOrNullTest extends \LazyCollection\Tests\PHPUnit
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
	public function firstOrNull(iterable $it, callable $predicate = null){
		return Stream::fromIterable($it)->firstOrNull($predicate);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::firstOrNull
	 * @dataProvider provideFirstData
	 *
	 * @param iterable $input
	 * @param callable|null $predicate
	 * @param $expected
	 */
	public function returnsFirstIfItExist(iterable $input, ?callable $predicate, $expected){
		$value = $this->firstOrNull($input, $predicate);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @covers \LazyCollection\Stream::firstOrNull
	 * @dataProvider provideFailFirstData
	 *
	 * @param iterable $input
	 * @param callable|null $predicate
	 */
	public function returnsNullIfThereIsNoFirst(iterable $input, ?callable $predicate){
		$value = $this->firstOrNull($input, $predicate);
		$this->assertNull($value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideFirstData(){
		return $this->firstProvider->provideFirstData();
	}

	public function provideFailFirstData(){
		return [
			[
				[1, 2, 3],
				function($x){ return $x > 4; },
			],
		];
	}
}
