<?php


namespace LazyCollection\Tests\Methods\Terminators\Checks;


abstract class CheckTest extends \LazyCollection\Tests\PHPUnit
{
	public static $method = "";

	public function properlyChecksWithPredicate(iterable $input, callable $predicate, bool $expected){
		$method = static::$method;
		$value = $this->$method($input, $predicate);
		$this->assertEquals($expected, $value);
	}
}
