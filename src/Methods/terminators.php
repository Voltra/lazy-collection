<?php

use LazyCollection\Exceptions\NotFoundException;
use LazyCollection\Helpers;
use LazyCollection\Stream;

/**********************************************************************************************************************\
 * Consumers
\**********************************************************************************************************************/
Stream::registerMethod("forEach", function(callable $cb): void{
	/**
	 * @var Stream $this
	 */
	foreach ($this as $key => $value){
		$cb($value, $key);
	}
});

Stream::registerMethod("reduce", function(callable $reducer, $init = null){
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

Stream::registerMethod("count", function(callable $predicate = null): int{
	/**
	 * @var Stream $this
	 */
	$predicate = $predicate ?: [Helpers::class, "yes"];
	return $this->reduce(static function($acc, $elem, $key) use($predicate): int{
		return $predicate($elem, $key) ? $acc + 1 : $acc;
	}, 0);
});



/**********************************************************************************************************************\
 * Transformers
\**********************************************************************************************************************/
Stream::registerMethod("toArray", function(): array{
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

Stream::registerMethod("toJSON", function(): string{
	/**
	 * @var Stream $this
	 */
	return Helpers::toJSON($this->toArray());
});

Stream::registerMethod("associateBy", function(callable $valueFactory = null, callable $keyFactory = null): array{
	/**
	 * @var Stream $this
	 */
	$valueFactory = $valueFactory ?: [Helpers::class, "firstArg"];
	$keyFactory = $keyFactory ?: [Helpers::class, "secondArg"];
	$ret = [];
	foreach ($this as $key => $value) {
		$newKey = $keyFactory($value, $key);
		$newValue = $valueFactory($value, $key);
		$ret[$newKey] = $newValue;
	}
	return $ret;
});

Stream::registerMethod("associate", function(callable $factory): array {
	/**
	 * @var Stream $this
	 */
	$ret = [];
	foreach ($this as $key => $value){
		[$newKey, $newValue] = $factory($value, $key);
		$ret[$newKey] = $newValue;
	}
	return $ret;
});

Stream::registerMethod("groupBy", function(callable $keyExtractor): array{
	/**
	 * @var Stream $this
	 */
	$ret = [];
	foreach ($this as $key => $value){
		$groupKey = $keyExtractor($value, $key);
		$ret[$groupKey][] = $value;
	}
	return $ret;
});

Stream::registerMethod("join", function(array $userOptions = []): string{
	/**
	 * @var Stream $this
	 */
	$options = Helpers::merge([
		"prefix" => "[",
		"separator" => ", ",
		"suffix" => "]",
		"stringifier" => [Helpers::class, "identity"],
		"strlen" => "strlen",
		"substr" => "substr"
	], $userOptions);

	$ret = $options["prefix"];
	$count = 0;

	foreach ($this as $key => $value){
		$str = $options["stringifier"]($value, $key);
		$ret .= $str . $options["separator"];
		$count++;
	}

	if($count > 0){
		$len = $options["strlen"]($options["separator"]);
		$ret = $options["substr"]($ret, 0, -$len);
	}

	$ret .= $options["suffix"];
	return $ret;
});

Stream::registerMethod("partition", function(callable $predicate): array{
	/**
	 * @var Stream $this
	 */
	$ret = [[], []];
	foreach($this as $key => $value){
		$index = $predicate($value, $key) ? 0 : 1;
		$ret[$index][] = $value;
	}
	return $ret;
});

Stream::registerMethod("sum", function($init = null){
	/**
	 * @var Stream $this
	 */
	return $this->reduce(static function($acc, $elem){
		return $acc + $elem;
	}, $init);
});

Stream::registerMethod("sumBy", function(callable $mapper, $init = null){
	/**
	 * @var Stream $this
	 */
	return $this->map($mapper)->sum($init);
});

Stream::registerMethod("average", function(){
	/**
	 * @var Stream $this
	 */
	$i = 0;
	return $this->reduce(function($acc, $elem) use(&$i){
		//cf. https://stackoverflow.com/a/5984099/7316365
		$key = $i++;
		return (($acc * $key) + $elem) / $i;
	}, 0);
});

Stream::registerMethod("unzip", function(): array{
	/**
	 * @var Stream $this
	 */
	return $this->reduce(static function($acc, $elem){
		[$lhs, $rhs] = $elem;
		$acc[0][] = $lhs;
		$acc[1][] = $rhs;
		return $acc;
	}, [[], []]);
});



/**********************************************************************************************************************\
 * Checks
\**********************************************************************************************************************/
Stream::registerMethod("all", function(callable $predicate): bool{
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

Stream::registerMethod("none", function(callable $predicate): bool{
	/**
	 * @var Stream $this
	 */
	return $this->all(Helpers::negate($predicate));
});

Stream::registerMethod("any", function (callable $predicate): bool{
	/**
	 * @var Stream $this
	 */
	return !$this->none($predicate);
});

Stream::registerMethod("notAll", function(callable $predicate): bool{
	/**
	 * @var Stream $this
	 */
	return !$this->all($predicate);
});



/**********************************************************************************************************************\
 * Searchers
\**********************************************************************************************************************/
Stream::registerMethod("first", function(callable $predicate = null){
	$predicate = $predicate ?: [Helpers::class, "yes"];

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

Stream::registerMethod("firstOr", function($default, callable $predicate = null){
	$predicate = $predicate ?: [Helpers::class, "yes"];

	/**
	 * @var Stream $this
	 */
	try{
		return $this->first($predicate);
	}catch(NotFoundException $e){
		return $default;
	}
});

Stream::registerMethod("firstOrNull", function (callable $predicate = null){
	$predicate = $predicate ?: [Helpers::class, "yes"];

	/**
	 * @var Stream $this
	 */
	return $this->firstOr(null, $predicate);
});

Stream::registerMethod("last", function(callable $predicate = null){
	$predicate = $predicate ?: [Helpers::class, "yes"];

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

Stream::registerMethod("lastOr", function($default, callable $predicate = null){
	$predicate = $predicate ?: [Helpers::class, "yes"];

	/**
	 * @var Stream $this
	 */
	try{
		return $this->last($predicate);
	}catch(NotFoundException $e){
		return $default;
	}
});

Stream::registerMethod("lastOrNull", function (callable $predicate = null){
	$predicate = $predicate ?: [Helpers::class, "yes"];

	/**
	 * @var Stream $this
	 */
	return $this->lastOr(null, $predicate);
});

Stream::registerMethod("atIndex", function(int $i){
	/**
	 * @var Stream $this
	 */
	$cur = 0;
	return $this->first(function() use(&$cur, $i): bool{
		return $cur++ === $i;
	});
});

Stream::registerMethod("atIndexOr", function(int $i, $default){
	/**
	 * @var Stream $this
	 */
	try{
		return $this->atIndex($i);
	}catch(NotFoundException $e){
		return $default;
	}
});

Stream::registerMethod("atIndexOrNull", function (int $i){
	/**
	 * @var Stream $this
	 */
	return $this->atIndexOr($i, null);
});

Stream::registerMethod("indexOfFirst", function(callable $predicate): int{
	/**
	 * @var Stream $this
	 */
	$cur = 0;
	foreach ($this as $key => $value){
		if($predicate($value, $key)) {
			return $cur;
		}

		$cur++;
	}

	return -1;
});

Stream::registerMethod("indexOf", function($needle): int{
	/**
	 * @var Stream $this
	 */
	return $this->indexOfFirst(static function ($value) use($needle){
		return $value === $needle;
	});
});

Stream::registerMethod("indexOfLast", function(callable $predicate): int{
	/**
	 * @var Stream $this
	 */
	$cur = 0;
	$ret = -1;
	foreach ($this as $key => $value){
		if($predicate($value, $key)) {
			$ret = $cur;
		}
		$cur++;
	}

	return $ret;
});

Stream::registerMethod("lastIndexOf", function($needle): int{
	/**
	 * @var Stream $this
	 */
	return $this->indexOfLast(static function($value) use($needle){
		return $value === $needle;
	});
});

Stream::registerMethod("maxBy", function(callable $mapper){
	/**
	 * @var Stream $this
	 */
	return $this->reduce(function($acc, $elem) use($mapper) {
		$value = $mapper($elem);
		$vacc = $mapper($acc);
		return Helpers::cmp($value, $vacc) > 0 ? $elem : $acc;
	});
});

Stream::registerMethod("max", function(){
	/**
	 * @var Stream $this
	 */
	return $this->maxBy([Helpers::class, "identity"]);
});

Stream::registerMethod("maxWith", function(callable $comparator){
	/**
	 * @var Stream $this
	 */
	return $this->reduce(static function($acc, $elem) use($comparator){
		return $comparator($elem, $acc) > 0 ? $elem : $acc;
	});
});

Stream::registerMethod("minBy", function(callable $mapper){
	/**
	 * @var Stream $this
	 */
	return $this->reduce(static function($acc, $elem) use($mapper) {
		$value = $mapper($elem);
		$vacc = $mapper($acc);
		return Helpers::cmp($value, $vacc) < 0 ? $elem : $acc;
	});
});

Stream::registerMethod("min", function(){
	/**
	 * @var Stream $this
	 */
	return $this->minBy([Helpers::class, "identity"]);
});

Stream::registerMethod("minWith", function(callable $comparator){
	/**
	 * @var Stream $this
	 */
	return $this->reduce(static function($acc, $elem) use($comparator){
		return $comparator($elem, $acc) < 0 ? $elem : $acc;
	});
});

Stream::registerMethod("single", function(callable $predicate = null){
	$predicate = $predicate ?: [Helpers::class, "yes"];

	/**
	 * @var Stream $this
	 */
	$arr = $this->filter($predicate)->toArray();

	if(count($arr) === 1) {
		return $arr[0];
	}

	throw new NotFoundException("Couldn't find single element");
});

Stream::registerMethod("singleOr", function($default, callable $predicate = null){
	$predicate = $predicate ?: [Helpers::class, "yes"];

	/**
	 * @var Stream $this
	 */
	try{
		return $this->single($predicate);
	}catch(NotFoundException $e){
		return $default;
	}
});

Stream::registerMethod("singleOrNull", function(callable $predicate = null){
	$predicate = $predicate ?: [Helpers::class, "yes"];

	/**
	 * @var Stream $this
	 */
	return $this->singleOr(null, $predicate);
});

Stream::registerMethod("contains", function($needle): bool{
	/**
	 * @var Stream $this
	 */
	return $this->indexOf($needle) >= 0;
});
