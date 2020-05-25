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