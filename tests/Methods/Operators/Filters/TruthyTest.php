<?php


namespace LazyCollection\Tests\Methods\Operators\Filters;


use LazyCollection\Stream;

class TruthyTest extends HelpersBasedTests
{
	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/
	/**
	 * @param iterable $it
	 * @return array
	 */
	public function truthy(iterable $it){
		return Stream::fromIterable($it)
			->truthy()
			->toArray();
	}



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::truthy
	 * @dataProvider provideTruthyCheck
	 *
	 * @param $value
	 * @param bool $isTruthy
	 */
	public function properlyFilters($value, bool $isTruthy){
		$count = $isTruthy ? 1 : 0;
		$result = $this->truthy([$value]);
		$this->assertCount($count, $result);
	}


	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideTruthyCheck(){
		return $this->provider->provideTruthyCheck();
	}
}
