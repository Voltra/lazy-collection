<?php

use LazyCollection\Helpers;
use LazyCollection\Stream;

Stream::addMethod("map", function(callable $mapper): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->pipe(static function(Generator $parent) use($mapper){
		foreach ($parent as $key => $value) {
			yield $key => $mapper($value, $key);
		}
	});
});

Stream::addMethod("peek", function(callable $cb): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->pipe(function(Generator $parent) use($cb){
		foreach ($parent as $key => $value) {
			$cb($value, $key);

			if($this->associative)
				yield $key => $value;
			else
				yield $value;
		}
	});
});

Stream::addMethod("flatten", function(): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->associative ? $this : $this->pipe(static function(Generator $parent){
		foreach ($parent as $value){
			if(Helpers::isIterable($value)){
				foreach($value as $val) {
					yield $val;
				}
			}else {
				yield $value;
			}
		}
	});
});

Stream::addMethod("flatMap", function(callable $mapper): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->map($mapper)->flatten();
});

Stream::addMethod("mapFlattened", function(callable $mapper): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->flatten()->map($mapper);
});

Stream::addMethod("filter", function(callable $predicate): Stream{
	/**
	 * @var Stream $this
	 */

	$gen = $this->associative ? static function(Generator $parent) use ($predicate) {
		foreach ($parent as $key => $value){
			if($predicate($value, $key)){
				yield $key => $value;
			}
		}
	} : static function(Generator $parent) use ($predicate) {
		foreach ($parent as $value){
			if($predicate($value)) {
				yield $value;
			}
		}
	};

	 return $this->pipe($gen);
});

Stream::addMethod("filterNot", function(callable $predicate){
	return $this->filter(static function($value, $key) use ($predicate){
		return !$predicate($value, $key);
	});
});

Stream::addMethod("filterOut", function(callable $predicate){
	return $this->filterNot($predicate);
});

Stream::addMethod("instancesOf", function(string $class){
	/**
	 * @var Stream $this
	 */
	return $this->filter(static function($value) use ($class) {
		return $value instanceof $class;
	});
});