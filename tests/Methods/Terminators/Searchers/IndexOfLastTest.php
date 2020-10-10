<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class IndexOfLastTest extends \LazyCollection\Tests\PHPUnit
{
	/**
	 * @var IndexOfFirstTest
	 */
	protected $indexOfFirstProvider;

	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->indexOfFirstProvider = new IndexOfFirstTest($name, $data, $dataName);
	}

	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function indexOfLast(iterable $it, callable $predicate){
		return Stream::fromIterable($it)->indexOfLast($predicate);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::indexOfLast
	 * @dataProvider provideIndexOfLastData
	 *
	 * @param iterable $input
	 * @param callable $predicate
	 * @param int $expected
	 */
	public function returnsProperIndex(iterable $input, callable $predicate, int $expected){
		$value = $this->indexOfLast($input, $predicate);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::indexOfLast
	 * @dataProvider provideFailureData
	 *
	 * @param iterable $input
	 * @param callable $predicate
	 */
	public function returnsMinusOneOnFailure(iterable $input, callable $predicate){
		$value = $this->indexOfLast($input, $predicate);
		$expected = -1;
		$this->assertEquals($expected, $value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideIndexOfLastData(){
		return [
			[
				[1, 2, 3], // $input
				function($x){ return $x < 4; }, // $predicate
				2, // $expected
			],
		];
	}

	public function provideFailureData(){
		return $this->indexOfFirstProvider->provideFailureData();
	}
}
