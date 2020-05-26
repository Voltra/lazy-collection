<?php

use LazyCollection\Helpers;
use LazyCollection\Stream;

/**********************************************************************************************************************\
 * Mappers
\**********************************************************************************************************************/
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

			if($this->associative) {
				yield $key => $value;
			}
			else {
				yield $value;
			}
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

Stream::addMethod("reverse", function(): Stream{
	return $this->pipe(static function(Generator $parent){
		$arr = Helpers::arrayFromIterable($parent);
		yield from array_reverse($arr, false);
	});
});



/**********************************************************************************************************************\
 * Filters
\**********************************************************************************************************************/
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

Stream::addMethod("filterNot", function(callable $predicate): Stream{
	return $this->filter(Helpers::negate($predicate));
});

Stream::addMethod("filterOut", function(callable $predicate): Stream{
	return $this->filterNot($predicate);
});

Stream::addMethod("notNull", function(): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->filter([Helpers::class, "notNull"]);
});

Stream::addMethod("falsy", function(): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->filter([Helpers::class, "falsy"]);
});

Stream::addMethod("truthy", function(): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->filter([Helpers::class, "truthy"]);
});

Stream::addMethod("instanceOf", function(string $class): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->filter(static function($value) use ($class): bool {
		return Helpers::instanceOf($value, $class);
	});
});

Stream::addMethod("notInstanceOf", function(string $class){
	/**
	 * @var Stream $this
	 */
	return $this->filterNot(static function($value) use ($class) {
		return Helpers::instanceOf($value, $class);
	});
});



/**********************************************************************************************************************\
 * Extenders
\**********************************************************************************************************************/
Stream::addMethod("then", function(iterable $it){
	/**
	 * @var Stream $this
	 */
	return $this->pipe(static function(Generator $parent) use($it){
		yield from $parent;
		yield from $it;
	});
});

Stream::addMethod("zipWith", function(Stream $it): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->pipe(static function(Generator $parent) use($it){
		$rhsIt = $it->getIterator();
		while($parent->valid() && $rhsIt->valid()){
			$lhs = $parent->current();
			$rhs = $it->current();

			yield [$lhs, $rhs];

			$parent->next();
			$it->next();
		}
	});
});