<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class LastIndexOfTest extends \LazyCollection\Tests\PHPUnit
{
	/**
	 * @var IndexOfTest
	 */
	protected $indexOfProvider;

	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->indexOfProvider = new IndexOfTest($name, $data, $dataName);
	}

	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function lastIndexOf(iterable $it, $elem){
		return Stream::fromIterable($it)
			->lastIndexOf($elem);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::lastIndexOf
	 * @dataProvider provideIndexOfData
	 *
	 * @param iterable $input
	 * @param $needle
	 * @param int $expected
	 */
	public function returnsCorrectIndexIfExist(iterable $input, $needle, int $expected){
		$value = $this->lastIndexOf($input, $needle);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::lastIndexOf
	 * @dataProvider provideFailureData
	 *
	 * @param iterable $input
	 * @param $needle
	 */
	public function returnsMinusOnIfDoesNotExist(iterable $input, $needle){
		$value = $this->lastIndexOf($input, $needle);
		$expected = -1;
		$this->assertEquals($expected, $value);
	}


	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideIndexOfData(){
		return [
			[
				[1, 2, 3, 4, 3, 5], // $input
				3, // $needle
				4, // $expected
			],
		];
	}

	public function provideFailureData(){
		return $this->indexOfProvider->provideFailureData();
	}
}
