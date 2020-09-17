<?php


namespace LazyCollection\Tests\Globals;


use LazyCollection\Helpers;
use LazyCollection\Tests\PHPUnit;

class HelpersTest extends PHPUnit
{
	/******************************************************************************************************************\
	 * TESTS
	\******************************************************************************************************************/
	/**
	 * @test
	 * @covers \LazyCollection\Helpers::isIterable
	 * @dataProvider provideMixedIterableOrNot
	 *
	 * @param $value
	 * @param bool $expected
	 */
	public function iterableDetectsProperly($value, bool $expected){
		$this->assertEquals($expected, Helpers::isIterable($value));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::isAssoc
	 * @dataProvider provideDifferentTypesOfArrays
	 *
	 * @param array $assocOrNot
	 * @param bool $expected
	 */
	public function assocDetectsProperly(array $assocOrNot, bool $expected){
		$this->assertEquals($expected, Helpers::isAssoc($assocOrNot));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::arrayFromIterable
	 * @dataProvider provideIterablesWithTheirArrayShape
	 *
	 * @param iterable $iterable
	 * @param array $expected
	 */
	public function convertsProperlyToIterable(iterable $iterable, array $expected){
		$this->assertEquals($expected, Helpers::arrayFromIterable($iterable));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::toJSON
	 * @dataProvider provideArrayWithJson
	 *
	 * @param array $value
	 * @param string $expected
	 */
	public function properlyConvertsArrayToJson(array $value, string $expected){
		$this->assertJson($expected, Helpers::toJSON($value));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::notNull
	 * @dataProvider provideMaybeNull
	 *
	 * @param $value
	 * @param bool $expectedNeg
	 */
	public function properlyNullChecks($value, bool $expectedNeg){
		$this->assertEquals(!$expectedNeg, Helpers::notNull($value));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::falsy
	 * @dataProvider provideFalsyCheck
	 *
	 * @param $value
	 * @param bool $expected
	 */
	public function properlyChecksFalsy($value, bool $expected){
		$this->assertEquals($expected, Helpers::falsy($value));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::truthy
	 * @dataProvider provideTruthyCheck
	 *
	 * @param $value
	 * @param bool $expected
	 */
	public function properlyChecksTruthy($value, bool $expected){
		$this->assertEquals($expected, Helpers::truthy($value));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::instanceOf
	 * @dataProvider provideClassInstanceCheck
	 *
	 * @param $object
	 * @param string $class
	 * @param bool $expected
	 */
	public function properlyChecksClassInstance($object, string $class, bool $expected){
		$this->assertEquals($expected, Helpers::instanceOf($object, $class));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::yes
	 * @dataProvider provideRandomTestArguments
	 *
	 * @param array $args
	 */
	public function yesIsAlwaysTrue(array $args){
		$this->assertTrue(Helpers::yes(...$args));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::no
	 * @dataProvider provideRandomTestArguments
	 *
	 * @param array $args
	 */
	public function noIsAlwaysFalse(array $args){
		$this->assertFalse(Helpers::no(...$args));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::negate
	 * @dataProvider provideNegatePayload
	 *
	 * @param callable $predicate
	 * @param $input
	 * @param bool $predicateResult
	 */
	public function negateGivesTheCorrectPredicate(callable $predicate, $input, bool $predicateResult){
		$negated = Helpers::negate($predicate);
		$this->assertEquals($predicateResult, $predicate($input));
		$this->assertNotEquals($predicateResult, $negated($input));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::identity
	 * @dataProvider provideRandomTestData
	 *
	 * @param $value
	 */
	public function identityFunctionIsAnActualIdentityFunction($value){
		$this->assertEquals($value, Helpers::identity($value));
	}

	/**
	 * @test
	 * @covers       \LazyCollection\Helpers::firstArg
	 * @dataProvider provideArgumentsArray
	 *
	 *
	 * @param mixed ...$args
	 */
	public function firstArgAlwaysReturnsTheFirstArg(...$args){
		$this->assertEquals($args[0], Helpers::firstArg(...$args));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::secondArg
	 * @dataProvider provideArgumentsArray
	 *
	 * @param mixed ...$args
	 */
	public function secondArgAlwaysReturnsTheSecondArg(...$args){
		$this->assertEquals($args[1], Helpers::secondArg(...$args));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::merge
	 * @dataProvider provideNonMergeableArrays
	 *
	 * @param array $defaults
	 * @param array $newValues
	 */
	public function mergeDoesNotMergeRegularArrays(array $defaults, array $newValues){
		$this->assertEquals($defaults, Helpers::merge($defaults, $newValues));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::merge
	 * @dataProvider provideMergeableArrays
	 *
	 * @param array $defaults
	 * @param array $newValues
	 * @param array $expected
	 */
	public function mergeDoesTheMergeAndOverrideWithAssocArrays(array $defaults, array $newValues, array $expected){
		$this->assertEquals($expected, Helpers::merge($defaults, $newValues));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::interpolate
	 * @dataProvider provideDataWithStringShape
	 *
	 * @param $value
	 * @param string $expected
	 */
	public function interpolateGivesCorrectString($value, string $expected){
		$this->assertEquals($expected, Helpers::interpolate($value));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::cmp
	 * @dataProvider provideComparisonPayload
	 *
	 * @param $lhs
	 * @param $rhs
	 * @param int $expected
	 */
	public function cmpGivesLexicalComparisonResult($lhs, $rhs, int $expected){
		$sign = static function(int $nb){
			if($nb === 0)
				return 0;

			return $nb < 0 ? -1 : 1;
		};

		$this->assertEquals($expected, $sign(
			Helpers::cmp($lhs, $rhs)
		));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::uniqueBy
	 * @dataProvider provideUniqueByPayload
	 *
	 * @param array $input
	 * @param callable $key
	 * @param array $expected
	 */
	public function uniqueByProperlySeperateDistinctElements(array $input, callable $key, array $expected){
		$result = Helpers::uniqueBy($key, $input);
		$this->assertEquals($expected, $result);
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::sortBy
	 * @dataProvider provideSortPayload
	 *
	 * @param array $input
	 * @param callable $keyExtractor
	 * @param array $expected
	 */
	public function sortingDoesTheRightThing(array $input, callable $keyExtractor, array $expected){
		$this->assertEquals($expected, Helpers::sortBy($keyExtractor, $input));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::doSort
	 * @dataProvider provideAssocSortPayload
	 *
	 * @param array $input
	 * @param array $expected
	 */
	public function associativeInplaceSortDoesTheRightThing(array $input, array $expected){
		$this->assertEquals($expected, Helpers::doSort($input));
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::doSort
	 */
	public function associativeInplaceSortCanSortDescending(){
		$input = [1, 5, 2];
		$expected = [5, 2, 1];
		$result = Helpers::doSort($input, SORT_DESC);
		$this->assertEquals($expected, $result);
	}

	/**
	 * @test
	 * @covers \LazyCollection\Helpers::sortWith
	 * @dataProvider provideSortWithPayload
	 *
	 * @param array $input
	 * @param callable $comparator
	 * @param array $expected
	 */
	public function inPlaceComparativeSortingWorksProperly(array $input, callable $comparator, array $expected){
		$this->assertEquals($expected, Helpers::sortWith($comparator, $input));
	}

	/******************************************************************************************************************\
	 * TEST PROVIDERS
	\******************************************************************************************************************/
	public function provideSortWithPayload(){
		return [
			[
				[5,1,8],
				function($lhs, $rhs){ return $rhs <=> $lhs; },
				[8, 5, 1],
			],
			[
				[5,1,8],
				function($lhs, $rhs){ return $lhs <=> $rhs; },
				[1, 5, 8],
			],
		];
	}

	public function provideAssocSortPayload(){
		return [
			[
				[5,1,8],
				[1,5,8],
			],
		];
	}

	public function provideSortPayload(){
		return [
			[
				[1,-5,8],
				function($x){ return -4 * (4 - $x); },
				[-5, 1, 8], // by [1 => -12, -5 => -36, 8 => 16]
			],
		];
	}

	public function provideUniqueByPayload(){
		return [
			[
				[0,1,2,1,2,3],
				function($e){ return 2 * $e; },
				[0,1,2,3],
			],
		];
	}

	public function provideComparisonPayload(){
		return [
			[0, 1, -1],
			[1, 0, 1],
			[0, 0, 0],

			["ab", "cd", -1],
			["ab", "ab", 0],
			["cd", "ab", 1],
		];
	}

	public function provideDataWithStringShape(){
		return [
			["", ""],
			["str", "str"],
			[42, "42"],
			[null, "null"],
			[0, "0"],
			[true, "true"],
			[false, "false"],
		];
	}

	public function provideMergeableArrays(){
		return [
			[ // [Test Case #0]
				[
					"key" => "value",
					"value" => "key",
				],
				[
					"value" => "value",
				],
				[
					"key" => "value",
					"value" => "value",
				],
			],  // [/Test Case #0]
			[ // [Test Case #1]
				[
					"key" => "value",
					"value" => "key",
				],
				[
					"stuff" => "thing",
				],
				[
					"key" => "value",
					"value" => "key",
					"stuff" => "thing",
				],
			],  // [/Test Case #1]
		];
	}

	public function provideNonMergeableArrays(){
		return [
			[
				["key" => "value"],
				[0,1,2],
			],
		];
	}

	public function provideArgumentsArray(){
		return [
			[0, 42],
			[0,1],
			[false, 42, 69],
			[null, new \stdClass(), true],
		];
	}

	public function provideRandomTestData(){
		return [
			[1],
			[null],
			[false],
			[42.0],
		];
	}

	public function provideNegatePayload(){
		return [
			[
				function($x){ return $x % 2 === 0; },
				2,
				true,
			],
			[
				function($x){ return $x % 3 === 0; },
				9,
				true,
			],
			[
				function($x){ return $x % 3 === 0; },
				10,
				false,
			],
		];
	}

	public function provideRandomTestArguments(){
		return [
			[
				[1,2,3],
			],
			[
				[],
			],
		];
	}

	public function provideClassInstanceCheck(){
		return [
			[null, \stdClass::class, false],
			[0, \stdClass::class, false],
			["", \stdClass::class, false],
			[0.0, \stdClass::class, false],
			[false, \stdClass::class, false],
			[[], \stdClass::class, false],
			[new \DirectoryIterator("."), \stdClass::class, false],

			[new \stdClass(), \stdClass::class, true],
			[new \DirectoryIterator("."), \DirectoryIterator::class, true],
			[new \DirectoryIterator("."), \Iterator::class, true],
		];
	}

	public function provideTruthyCheck(){
		return array_map(function($testData){
			[$value, $expected] = $testData;
			return [$value, !$expected];
		}, $this->provideFalsyCheck());
	}

	public function provideFalsyCheck(){
		return [
			[0, true],
			[0.0, true],
			["", true],
			[false, true],
			[null, true],
			["0", true],
			[[], true],

			[1, false],
			[1.0, false],
			["1", false],
			[true, false],
			[new \stdClass(), false],
		];
	}

	public function provideMaybeNull(){
		return [
			[null, true],
			[0, false],
			[false, false],
			["", false],
			[[], false],
			[new \stdClass(), false],
		];
	}

	public function provideArrayWithJson(){
		return [
			[
				[1,2,3],
				"[1,2,3]",
			],
			[
				["key" => 1],
				"{\"key\": 1}",
			],
			[
				[],
				"[]",
			],
		];
	}

	public function provideIterablesWithTheirArrayShape(){
		return [
			[
				[1,2,3],
				[1,2,3],
			],
			[
				(function(){ yield 42; })(),
				[42],
			],
		];
	}

	public function provideDifferentTypesOfArrays(){
		return [
			[
				[1,2,3],
				false,
			],
			[
				["key" => "value"],
				true,
			],
			[
				["6" => "not an array"],
				true,
			],
		];
	}

	public function provideMixedIterableOrNot(){
		return [
			[[], true],
			[1, false],
			[false, false],
			[42.0, false],
		];
	}
}
