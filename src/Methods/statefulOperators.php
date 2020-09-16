<?php

use LazyCollection\Helpers;
use LazyCollection\Stream;

/**********************************************************************************************************************\
 * Limiters
\**********************************************************************************************************************/
Stream::registerMethod("takeWhile", function(callable $predicate): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->pipe(static function(Generator $parent) use($predicate){
		foreach ($parent as $key => $value){
			if($predicate($value, $key)) {
				yield $key => $value;
			}
			else {
				break;
			}
		}
	});
});

Stream::registerMethod("takeUntil", function(callable $predicate): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->takeWhile(Helpers::negate($predicate));
});

Stream::registerMethod("take", function(int $maxAmount): Stream{
	/**
	 * @var Stream $this
	 */
	$count = 0;
	return $this->takeWhile(static function() use (&$count, $maxAmount): bool{
		return $count++ < $maxAmount;
	});
});

Stream::registerMethod("skipWhile", function(callable $predicate): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->pipe(static function(Generator $parent) use($predicate){
		$skip = true;
		foreach ($parent as $key => $value){
			if($skip && $predicate($value, $key)) {
				continue;
			}

			$skip = false;
			yield $key => $value;
		}
	});
});

Stream::registerMethod("skipUntil", function (callable $predicate): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->skipWhile(Helpers::negate($predicate));
});

Stream::registerMethod("skip", function(int $maxAmount): Stream{
	/**
	 * @var Stream $this
	 */
	$count = 0;
	return $this->skipWhile(static function() use(&$count, $maxAmount){
		return $count++ < $maxAmount;
	});
});

Stream::registerMethod("subStream", function(int $startIndex, int $endIndex): Stream{
	/**
	 * @var Stream $this
	 */
	$take = $endIndex - $startIndex;
	return $this->skip($startIndex)->take($take);
});

Stream::registerMethod("uniqueBy", function(callable $idExtractor): Stream{
	/**
	 * @var Stream $this
	 * @static Stream
	 */
	$arr = $this->toArray();

	//INFO: Due to how PHP treats parent:: and className:: calls, we need to construct the stream manually as a workaround
	return new static((function() use($idExtractor, $arr){
		$unique = Helpers::uniqueBy($idExtractor, $arr);
		yield from $unique;
	})(), Helpers::isAssoc($arr));
});

Stream::registerMethod("unique", function(): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->uniqueBy([Helpers::class, "identity"]);
});



/**********************************************************************************************************************\
 * Arrangers
\**********************************************************************************************************************/
Stream::registerMethod("sortBy", function(callable $idExtractor): Stream{
	/**
	 * @var Stream $this
	 */
	$arr = $this->toArray();
	//INFO: Due to how PHP treats parent:: and className:: calls, we need to construct the stream manually as a workaround

	/*
	 e.g. this would have been a shorthand if it were possible

		return static::fromIterable(Helpers::sortBy($idExtractor, $arr, SORT_ASC));
	 */
	return new static((function() use($idExtractor, $arr){
		$sorted = Helpers::sortBy($idExtractor, $arr, SORT_ASC);
		yield from $sorted;
	})(), Helpers::isAssoc($arr));
});

Stream::registerMethod("sort", function(): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->sortBy([Helpers::class, "identity"]);
});

Stream::registerMethod("sortByDescending", function(callable $idExtractor): Stream{
	/**
	 * @var Stream $this
	 */
	$arr = $this->toArray();

	//INFO: Due to how PHP treats parent:: and className:: calls, we need to construct the stream manually as a workaround
//	return static::fromIterable(Helpers::sortBy($idExtractor, $arr, SORT_DESC));
	return new static((function() use($idExtractor, $arr){
		$sorted = Helpers::sortBy($idExtractor, $arr, SORT_DESC);
		yield from $sorted;
	})(), Helpers::isAssoc($arr));
});

Stream::registerMethod("sortDescending", function(): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->sortByDescending([Helpers::class, "identity"]);
});

Stream::registerMethod("sortWith", function(callable $comparator): Stream{
	/**
	 * @var Stream $this
	 */
	$arr = $this->toArray();
	//INFO: Due to how PHP treats parent:: and className:: calls, we need to construct the stream manually as a workaround
//	return static::fromIterable(Helpers::sortWith($comparator, $arr));
	return new static((function() use($comparator, $arr){
		$sorted = Helpers::sortWith($comparator, $arr);
		yield from $sorted;
	})(), Helpers::isAssoc($arr));
});



/**********************************************************************************************************************\
 * Blockifiers
\**********************************************************************************************************************/
Stream::registerMethod("chunks", function(int $size): Stream{
	/**
	 * @var Stream $this
	 */
	$bucket = [];
	$bucketSize = 0;
	return $this->pipe(static function(Generator $parent) use(&$bucket, &$bucketSize, $size){
		foreach ($parent as $value){
			if($bucketSize < $size){
				$bucket[] = $value;
				$bucketSize++;
			}else{
				yield $bucket;
				$bucket = [$value];
				$bucketSize = 1;
			}
		}

		if($bucketSize > 0) { // Do not forget cleanup for non-evened streams
			yield $bucket;
		}
	});
});
