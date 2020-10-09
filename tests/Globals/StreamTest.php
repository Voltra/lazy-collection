<?php


namespace LazyCollection\Tests\Globals;


use LazyCollection\Exceptions\InvalidFactoryException;
use LazyCollection\Exceptions\InvalidMethodException;
use LazyCollection\Stream;

class StreamTest extends \LazyCollection\Tests\PHPUnit
{
	//TODO: Test for error in factory calls
	//TODO: Test for error in method calls
	//TODO: Test method registration
	//TODO: Test factory registration

	/******************************************************************************************************************\
	 * HELPERS
	\******************************************************************************************************************/



	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Stream::registerMethod
	 *
	 * @throws \LazyCollection\Exceptions\InvalidMethodException
	 */
	public function registeringMethodMakesItAvailableOnInstance(){
		$expected = 42;

		Stream::registerMethod("life", function() use($expected){
			/**
			 * @var Stream $this
			 */
			return $this->firstOr($expected);
		});

		$value = Stream::fromIterable([])->life();
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::registerMethod
	 *
	 * @throws InvalidMethodException
	 */
	public function cannotRegisterMethodThatAlreadyExist(){
		$this->expectException(InvalidMethodException::class);
		Stream::registerMethod("map", function(){});
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::__call
	 */
	public function cannotCallNonExistingMethod(){
		$this->expectException(InvalidMethodException::class);
		Stream::fromIterable([])->nonExistingMethod();
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::registerFactory
	 *
	 * @throws \LazyCollection\Exceptions\InvalidFactoryException
	 */
	public function registeringFactoryMakesItAvailableAsStaticMethod(){
		$expected = [];
		Stream::registerFactory("of", function() use($expected){
			return Stream::fromIterable($expected);
		});

		$value = Stream::of()->toArray();
		$this->assertEquals($expected, $value);
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::registerFactory
	 *
	 * @throws InvalidFactoryException
	 */
	public function cannotRegisterFactoryThatAlreadyExist(){
		$this->expectException(InvalidFactoryException::class);
		Stream::registerFactory("fromIterable", function(){});
	}

	/**
	 * @test
	 * @cover \LazyCollection\Stream::__callStatic
	 */
	public function cannotCallNonExistingFactory(){
		$this->expectException(InvalidFactoryException::class);
		Stream::notExistingFactory();
	}



	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
}
