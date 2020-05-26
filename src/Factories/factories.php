<?php

use LazyCollection\Helpers;
use LazyCollection\Stream;

Stream::addFactory("fromIterable", function(iterable $it){
	/**
	 * @var $this Stream
	 * @static Stream
	 */

	$gen = (static function() use($it){
		yield from $it;
	})();

	$associative = Helpers::isAssoc(Helpers::arrayFromIterable($it));
	return new static($gen, $associative);
});

Stream::addFactory("range", function(int $start = 0, int $end = null, int $step = 1): Stream{
	/**
	 * @var Stream $this
	 */
	$gen = (static function($start, $end, $step){
		if(Helpers::notNull($end)){
			$begin = $start < $end ? $start : $end;
			$end_ = $start < $end ? $end : $start;
			$cmp = $start < $end ? static function($i, $end){
				return $i < $end;
			} : static function($i, $end){
				return $i > $end;
			};
			$st = $step;

			if(($start < $end && $step < 0) || ($start > $end && $step > 0)) {
				$st *= -1;
			}

			for($i = $begin ; $cmp($i, $end) ; $i += $st){
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