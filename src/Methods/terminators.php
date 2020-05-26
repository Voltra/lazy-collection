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

Stream::addMethod("associateBy", function(callable $valueFactory = [Helpers::class, "identity"], callable $keyFactory = [Helpers::class, "identity"]): array{
	/**
	 * @var Stream $this
	 */
	$ret = [];
	foreach ($this as $key => $value) {
		$newKey = $keyFactory($value, $key);
		$newValue = $valueFactory($value, $key);
		$ret[$newKey] = $newValue;
	}
	return $ret;
});

Stream::addMethod("associate", function(callable $factory): array {
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

Stream::addMethod("groupBy", function(callable $keyExtractor): array{
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

Stream::addMethod("join", function(array $userOptions = []): string{
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

Stream::addMethod("partition", function(callable $predicate): array{
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

Stream::addMethod("sum", function(){
	/**
	 * @var Stream $this
	 */
	return $this->reduce(static function($acc, $elem){
		return $acc + $elem;
	});
});

Stream::addMethod("sumBy", function(callable $mapper){
	/**
	 * @var Stream $this
	 */
	return $this->map($mapper)->sum();
});

Stream::addMethod("average", function(){
	/**
	 * @var Stream $this
	 */
	$i = 0;
	return $this->reduce(static function($acc, $elem) use(&$i){
		//cf. http://www.heikohoffmann.de/htmlthesis/node134.html
		// For iterative average
		$i++;
		return $elem + (1/$i) * ($acc - $elem);
	});
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

Stream::addMethod("atIndex", function(int $i){
	/**
	 * @var Stream $this
	 */
	$cur = 0;
	return $this->first(static function($value) use(&$cur, $i): bool{
		return $cur++ === $i;
	});
});

Stream::addMethod("atIndexOr", function(int $i, $default){
	/**
	 * @var Stream $this
	 */
	try{
		return $this->atIndex($i);
	}catch(NotFoundException $e){
		return $default;
	}
});

Stream::addMethod("atIndexOrNull", function (int $i){
	/**
	 * @var Stream $this
	 */
	return $this->atIndexOr($i, null);
});

Stream::addMethod("indexOfFirst", function(callable $predicate): int{
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

Stream::addMethod("indexOf", function($needle): int{
	/**
	 * @var Stream $this
	 */
	return $this->indexOfFirst(static function ($value) use($needle){
		return $value === $needle;
	});
});

Stream::addMethod("indexOfLast", function(callable $predicate): int{
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

Stream::addMethod("lastIndexOf", function($needle): int{
	/**
	 * @var Stream $this
	 */
	return $this->indexOfLast(static function($value) use($needle){
		return $value === $needle;
	});
});

Stream::addMethod("maxBy", function(callable $mapper){
	/**
	 * @var Stream $this
	 */
	return $this->reduce(static function($acc, $elem) use($mapper) {
		$value = $mapper($elem);
		$vacc = $mapper($acc);
		return Helpers::cmp($value, $vacc) > 0 ? $elem : $acc;
	});
});

Stream::addMethod("max", function(){
	/**
	 * @var Stream $this
	 */
	return $this->maxBy([Helpers::class, "identity"]);
});

Stream::addMethod("maxWith", function(callable $comparator){
	/**
	 * @var Stream $this
	 */
	return $this->reduce(static function($acc, $elem) use($comparator){
		return $comparator($elem, $acc) > 0 ? $elem : $acc;
	});
});

Stream::addMethod("minBy", function(callable $mapper){
	/**
	 * @var Stream $this
	 */
	return $this->reduce(static function($acc, $elem) use($mapper) {
		$value = $mapper($elem);
		$vacc = $mapper($acc);
		return Helpers::cmp($value, $vacc) < 0 ? $elem : $acc;
	});
});

Stream::addMethod("min", function(){
	/**
	 * @var Stream $this
	 */
	return $this->minBy([Helpers::class, "identity"]);
});

Stream::addMethod("minWith", function(callable $comparator){
	/**
	 * @var Stream $this
	 */
	return $this->reduce(static function($acc, $elem) use($comparator){
		return $comparator($elem, $acc) < 0 ? $elem : $acc;
	});
});

Stream::addMethod("single", function(callable $predicate = [Helpers::class, "yes"]){
	/**
	 * @var Stream $this
	 */
	$arr = $this->filter($predicate)->toArray();

	if(count($arr) === 1) {
		return $arr[0];
	}

	throw new NotFoundException("Couldn't find single element");
});

Stream::addMethod("singleOr", function($default, callable $predicate = [Helpers::class, "yes"]){
	/**
	 * @var Stream $this
	 */
	try{
		return $this->single($predicate);
	}catch(NotFoundException $e){
		return $default;
	}
});

Stream::addMethod("singleOrNull", function(callable $predicate = [Helpers::class, "yes"]){
	/**
	 * @var Stream $this
	 */
	return $this->singleOr(null, $predicate);
});