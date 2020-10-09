<?php

use LazyCollection\Helpers;
use LazyCollection\Stream;

/**********************************************************************************************************************\
 * Limiters
\**********************************************************************************************************************/
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
		$sorted = Helpers::sortBy($idExtractor, $arr, SORT_REGULAR|SORT_DESC);
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
