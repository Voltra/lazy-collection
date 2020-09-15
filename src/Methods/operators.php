<?php

use LazyCollection\Helpers;
use LazyCollection\Stream;

/**********************************************************************************************************************\
 * Mappers
\**********************************************************************************************************************/
Stream::registerMethod("map", function(callable $mapper): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->pipe(static function(Generator $parent) use($mapper){
		foreach ($parent as $key => $value) {
			yield $key => $mapper($value, $key);
		}
	});
});

Stream::registerMethod("peek", function(callable $cb): Stream{
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

Stream::registerMethod("flatten", function(): Stream{
	/**
	 * @var Stream $this
	 */
	if($this->associative)
	    return $this;
	else{
	    $associative = true; //TODO: Check if there can be a better alternative (and the need for one)

        return $this->pipe(static function(Generator $parent){
            foreach ($parent as $value){
                if(Helpers::isIterable($value)){
                    $arr = Helpers::arrayFromIterable($value);
                    if(Helpers::isAssoc($arr)) {
                        yield from $arr;
                    }else{
                        foreach ($arr as $val)
                            yield $val;
                    }
                }else {
                    yield $value;
                }
            }
        }, $associative);
    }
});

Stream::registerMethod("flatMap", function(callable $mapper): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->map($mapper)->flatten();
});

Stream::registerMethod("mapFlattened", function(callable $mapper): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->flatten()->map($mapper);
});

Stream::registerMethod("reverse", function(): Stream{
	return $this->pipe(static function(Generator $parent){
		$arr = Helpers::arrayFromIterable($parent);
		if(Helpers::isAssoc($arr))
		    yield from $arr;
		else
		    yield from array_reverse($arr, false);
	});
});



/**********************************************************************************************************************\
 * Filters
\**********************************************************************************************************************/
Stream::registerMethod("filter", function(callable $predicate): Stream{
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

Stream::registerMethod("filterNot", function(callable $predicate): Stream{
	return $this->filter(Helpers::negate($predicate));
});

Stream::registerMethod("filterOut", function(callable $predicate): Stream{
	return $this->filterNot($predicate);
});

Stream::registerMethod("notNull", function(): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->filter([Helpers::class, "notNull"]);
});

Stream::registerMethod("falsy", function(): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->filter([Helpers::class, "falsy"]);
});

Stream::registerMethod("truthy", function(): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->filter([Helpers::class, "truthy"]);
});

Stream::registerMethod("instanceOf", function(string $class): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->filter(static function($value) use ($class): bool {
		return Helpers::instanceOf($value, $class);
	});
});

Stream::registerMethod("notInstanceOf", function(string $class){
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
Stream::registerMethod("then", function(iterable $it){
	/**
	 * @var Stream $this
	 */
	return $this->pipe(static function(Generator $parent) use($it){
		yield from $parent;
		yield from $it;
	});
});

Stream::registerMethod("zipWith", function(Stream $it): Stream{
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
