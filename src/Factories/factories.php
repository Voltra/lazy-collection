<?php

use LazyCollection\Helpers;
use LazyCollection\Stream;

Stream::registerFactory("fromIterable", function(iterable $it, bool $isAssoc = null){
	/**
	 * @var $this Stream
	 * @static Stream
	 */

	$gen = (static function() use($it){
		yield from $it;
	})();

	$associative = Helpers::notNull($isAssoc) ? $isAssoc : Helpers::isAssoc(Helpers::arrayFromIterable($it));
	return new static($gen, $associative);
});

Stream::registerFactory("range", function(int $start = 0, int $end = null, int $step = 1): Stream{
	/**
	 * @var $this Stream
	 * @static Stream
	 */
	$gen = (static function($start, $end, $step){
		if(Helpers::notNull($end)){
			$begin = $start;
			$end_ = $end;
			$cmp = $start < $end ? static function($i, $end){
				return $i < $end;
			} : static function($i, $end){
				return $i > $end;
			};
			$st = $step;

			if(($start < $end && $step < 0) || ($start > $end && $step > 0)) {
                // Avoids using the wrong stepping + UX (can pass 1 instead of passing -1 to decrease by 1)
				$st *= -1;
			}

			for($i = $begin ; $cmp($i, $end_) ; $i += $st){
				yield $i;
			}
		}else{
			for($i = $start ; ; $i += $step){
				yield $i;
			}
		}
	})($start, $end, $step);

	return new static($gen, false);
});

Stream::registerFactory("splitBy", function(string $str, string $separator = "", bool $removeEmptyStrings = true): Stream{
	/**
	 * @var $this Stream
	 * @static Stream
	 */

	$split = explode($separator, $str);
	$predicate = $removeEmptyStrings ? static function(string $e){ return $e !== ""; } : [Helpers::class, "yes"];
	return static::fromIterable($split)
		->filter($predicate);
});

Stream::registerFactory("splitByRegex", function(string $str, string $re, bool $removeEmptyStrings = true): Stream{
	/**
	 * @var $this Stream
	 * @static Stream
	 */

	$flag = $removeEmptyStrings ? PREG_SPLIT_NO_EMPTY : 0;
	$split = preg_split($re, $str, -1, $flag);
	return static::fromIterable($split);
});
