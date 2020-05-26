<?php

use LazyCollection\Helpers;
use LazyCollection\Stream;

/**********************************************************************************************************************\
 * Limiters
\**********************************************************************************************************************/
Stream::addMethod("takeWhile", function(callable $predicate): Stream{
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

Stream::addMethod("takeUntil", function(callable $predicate): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->takeWhile(Helpers::negate($predicate));
});

Stream::addMethod("take", function(int $maxAmount): Stream{
	/**
	 * @var Stream $this
	 */
	$count = 0;
	return $this->takeWhile(static function() use (&$count, $maxAmount): bool{
		return $count++ < $maxAmount;
	});
});

Stream::addMethod("skipWhile", function(callable $predicate): Stream{
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

Stream::addMethod("skipUntil", function (callable $predicate): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->skipWhile(Helpers::negate($predicate));
});

Stream::addMethod("skip", function(int $maxAmount): Stream{
	/**
	 * @var Stream $this
	 */
	$count = 0;
	return $this->skipWhile(static function() use(&$count, $maxAmount){
		return $count++ < $maxAmount;
	});
});

Stream::addMethod("subStream", function(int $startIndex, int $endIndex): Stream{
	/**
	 * @var Stream $this
	 */
	$take = $endIndex - $startIndex;
	return $this->skip($startIndex)->take($take);
});

Stream::addMethod("uniqueBy", function(callable $idExtractor): Stream{
	/**
	 * @var Stream $this
	 */
	$arr = $this->toArray();
	return Stream::fromIterable(Helpers::uniqueBy($idExtractor, $arr));
});

Stream::addMethod("unique", function(): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->uniqueBy([Helpers::class, "identity"]);
});



/**********************************************************************************************************************\
 * Arrangers
\**********************************************************************************************************************/
Stream::addMethod("sortBy", function(callable $idExtractor): Stream{
	/**
	 * @var Stream $this
	 */
	$arr = $this->toArray();
	return Stream::fromIterable(Helpers::sortBy($idExtractor, $arr, SORT_ASC));
});

Stream::addMethod("sort", function(): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->sortBy([Helpers::class, "identity"]);
});

Stream::addMethod("sortByDescending", function(callable $idExtractor): Stream{
	/**
	 * @var Stream $this
	 */
	$arr = $this->toArray();
	return Stream::fromIterable(Helpers::sortBy($idExtractor, $arr, SORT_DESC));
});

Stream::addMethod("sortDescending", function(): Stream{
	/**
	 * @var Stream $this
	 */
	return $this->sortByDescending([Helpers::class, "identity"]);
});

Stream::addMethod("sortWith", function(callable $comparator): Stream{
	/**
	 * @var Stream $this
	 */
	$arr = $this->toArray();
	return Stream::fromIterable(Helpers::sortWith($comparator, $arr));
});



/**********************************************************************************************************************\
 * Blockifiers
\**********************************************************************************************************************/
Stream::addMethod("chunks", function(int $size): Stream{
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