<?php


namespace LazyCollection\Tests\Methods\Terminators\Searchers;


use LazyCollection\Stream;

class AtIndexOrTest extends \LazyCollection\Tests\PHPUnit
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
	public function atIndexOr(iterable $it, $default, int $index){
		return Stream::fromIterable($it)->atIndexOr($index, $default);
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers       \LazyCollection\Stream::atIndexOr
	 * @dataProvider provideAtIndexData
	 *
	 * @param iterable $input
	 * @param int $index
	 * @param $expected
	 */
	public function returnsItemAtIndexIfItExist(iterable $input, int $index, $expected){
		$value = $this->atIndexOr($input, null, $index);
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @covers       \LazyCollection\Stream::atIndexOr
	 * @dataProvider provideFailureIndexOrData
	 *
	 * @param iterable $input
	 * @param $default
	 * @param int $index
	 */
	public function returnsDefaultIfNoItemAtIndex(iterable $input, $default, int $index){
		$value = $this->atIndexOr($input, $default, $index);
		$this->assertEquals($default, $value);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideAtIndexData(){
		return $this->atIndexProvider->provideAtIndexData();
	}

	public function provideFailureIndexOrData(){
		return [
			[
				[], // $input
				42, // $default
				0, // $index
			],
			[
				[],
				69,
				42,
			],
			[
				[1, 3],
				5,
				2,
			],
		];
	}
}
