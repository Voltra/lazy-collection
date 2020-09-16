<?php


namespace LazyCollection\Tests\Methods\Operators\Filters;


use LazyCollection\Stream;

class FalsyTest extends HelpersBasedTests
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @return array
	 */
	public function falsy(iterable $it){
		return Stream::fromIterable($it)
			->falsy()
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::falsy
	 * @dataProvider provideFalsyCheck
	 *
	 * @param $value
	 * @param bool $isFalsy
	 */
	public function properlyFilters($value, bool $isFalsy){
		$count = $isFalsy ? 1 : 0;
		$result = $this->falsy([$value]);
		$this->assertCount($count, $result);
	}


	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideFalsyCheck(){
		return $this->provider->provideFalsyCheck();
	}
}
