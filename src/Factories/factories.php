<?php

use LazyCollection\Helpers;
use LazyCollection\Stream;

/**
 * @class Stream
 * @method static Stream Stream::from(iterable $it)
 */
Stream::addFactory("from", function(iterable $it){
	/**
	 * @var $this Stream
	 * @static Stream
	 */

	$gen = (static function() use($it){
		foreach ($it as $key => $value) {
			yield $key => $value;
		}
	})();

	$associative = Helpers::isAssoc(Helpers::arrayFromIterable($it));
	return new static($gen, $associative);
});