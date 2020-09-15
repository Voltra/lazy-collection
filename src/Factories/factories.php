<?php

use LazyCollection\Helpers;
use LazyCollection\Stream;

Stream::registerFactory("fromIterable", function(iterable $it){
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

Stream::registerFactory("range", function(int $start = 0, int $end = null, int $step = 1): Stream{
	/**
	 * @var Stream $this
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
