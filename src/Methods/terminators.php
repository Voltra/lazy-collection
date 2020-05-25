<?php

use LazyCollection\Exceptions\NotFoundException;
use LazyCollection\Helpers;
use LazyCollection\Stream;

/**********************************************************************************************************************\
 * Consumers
\**********************************************************************************************************************/
Stream::addMethod("forEach", function(callable $cb): void{
	/**
	 * @var Stream $this
	 */
	foreach ($this as $key => $value){
		$cb($value, $key);
	}
});

Stream::addMethod("reduce", function(callable $reducer, $init = null){
	/**
	 * @var Stream $this
	 */
	$acc = $init;
	$firstDone = false;
	foreach ($this as $key => $value) {
		if($init === null && !$firstDone){
			$acc = $value;
			$firstDone = true;
		}else {
			$acc = $reducer($acc, $value, $key);
		}
	}
	return $acc;
});

Stream::addMethod("count", function(callable $predicate = [Helpers::class, "yes"]): int{
	/**
	 * @var Stream $this
	 */
	return $this->reduce(static function($acc, $elem, $key) use($predicate): int{
		return $predicate($elem, $key) ? $acc + 1 : $acc;
	}, 0);
});



/**********************************************************************************************************************\
 * Transformers
\**********************************************************************************************************************/
Stream::addMethod("toArray", function(): array{
	/**
	 * @var Stream $this
	 */

	$ret = [];
	if($this->associative){
		foreach ($this as $key => $value) {
			$ret[$key] = $value;
		}
	}else{
		foreach ($this as $value) {
			$ret[] = $value;
		}
	}

	return $ret;
});

Stream::addMethod("toJSON", function(): string{
	/**
	 * @var Stream $this
	 */
	return Helpers::toJSON($this->toArray());
});



/**********************************************************************************************************************\
 * Checks
\**********************************************************************************************************************/
Stream::addMethod("all", function(callable $predicate): bool{
	/**
	 * @var Stream $this
	 */
	foreach ($this as $key => $value){
		if(!$predicate($value, $key)) {
			return false;
		}
	}

	return true;
});

Stream::addMethod("none", function(callable $predicate): bool{
	/**
	 * @var Stream $this
	 */
	return $this->all(Helpers::negate($predicate));
});

Stream::addMethod("any", function (callable $predicate): bool{
	/**
	 * @var Stream $this
	 */
	return !$this->none($predicate);
});

Stream::addMethod("notAll", function(callable $predicate): bool{
	/**
	 * @var Stream $this
	 */
	return !$this->all($predicate);
});



/**********************************************************************************************************************\
 * Searchers
\**********************************************************************************************************************/
Stream::addMethod("first", function(callable $predicate = [Helpers::class, "yes"]){
	/**
	 * @var Stream $this
	 */
	foreach ($this as $key => $value){
		if($predicate($value, $key)) {
			return $value;
		}
	}

	throw new NotFoundException("Could not find first element");
});

Stream::addMethod("firstOr", function($default, callable $predicate = [Helpers::class, "yes"]){
	/**
	 * @var Stream $this
	 */
	try{
		return $this->first($predicate);
	}catch(NotFoundException $e){
		return $default;
	}
});

Stream::addMethod("firstOrNull", function (callable $predicate = [Helpers::class, "yes"]){
	/**
	 * @var Stream $this
	 */
	return $this->firstOr(null, $predicate);
});

Stream::addMethod("last", function(callable $predicate = [Helpers::class, "yes"]){
	$def = [Helpers::class, "yes"];
	$current = $def;

	/**
	 * @var Stream $this
	 */
	foreach ($this as $key => $value){
		if($predicate($value, $key)) {
			$current = $value;
		}
	}

	if($current !== $def) {
		return $current;
	}

	throw new NotFoundException("Could not find last element");
});

Stream::addMethod("lastOr", function($default, callable $predicate = [Helpers::class, "yes"]){
	/**
	 * @var Stream $this
	 */
	try{
		return $this->last($predicate);
	}catch(NotFoundException $e){
		return $default;
	}
});

Stream::addMethod("lastOrNull", function (callable $predicate = [Helpers::class, "yes"]){
	/**
	 * @var Stream $this
	 */
	return $this->lastOr(null, $predicate);
});