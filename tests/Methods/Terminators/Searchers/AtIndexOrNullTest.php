<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class AtIndexOrNullTest extends \LazyCollection\Tests\PHPUnit
{
	/**
	 * @var AtIndexTest
	 */
	protected $atIndexProvider;

	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->atIndexProvider = new AtIndexTest($name, $data, $dataName);
	}

	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	public function atIndexOrNull(iterable $it, int $index){
		return Stream::fromIterable($it)->atIndexOrNull($index);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover       \LazyCollection\Stream::atIndexOrNull
	 * @dataProvider provideAtIndexData
	 *
	 * @param iterable $input
	 * @param int $index
	 * @param $expected
	 */
	public function returnsLastIfItExist(iterable $input, int $index, $expected){
		$value = $this->atIndexOrNull($input, $index);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @cover       \LazyCollection\Stream::atIndexOrNull
	 * @dataProvider provideFailAtIndexOrNullData
	 *
	 * @param iterable $input
	 * @param int $index
	 */
	public function returnsNullIfThereIsNoLast(iterable $input, int $index){
		$value = $this->atIndexOrNull($input, $index);
		$this->assertNull($value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideAtIndexData(){
		return $this->atIndexProvider->provideAtIndexData();
	}

	public function provideFailAtIndexOrNullData(){
		return [
			[
				[1, 2, 3], // $input
				42, // $index
			],
		];
	}
}
