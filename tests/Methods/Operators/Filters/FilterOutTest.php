<?php


namespace LazyCollection\Tests\Methods\Operators\Filters;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class FilterOutTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @var FilterNotTest
	 */
	private $__fnt;

	public function __construct($name = null, array $data = [], $dataName = '')
	{
		parent::__construct($name, $data, $dataName);
		$this->__fnt = new FilterNotTest($name, $data, $dataName);
	}

	public function filterOut(iterable $it, callable $predicate){
		return Stream::fromIterable($it)
			->filterOut($predicate)
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @cover \LazyCollection\Stream::filterOut
	 * @dataProvider provideDataSet
	 *
	 * @param array $input
	 * @param callable $predicate
	 * @param array $expected
	 */
	public function filtersProperlyUsingThePredicate(array $input, callable $predicate, array $expected){
		$result = $this->filterOut($input, $predicate);
		$this->assertEquals($expected, $result);
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideDataSet(){
		return $this->__fnt->provideDataSet();
	}
}
