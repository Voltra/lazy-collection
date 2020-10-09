<?php

namespace LazyCollection;

if(!function_exists("collect")){
	/**
	 * Collect the spread arguments into a stream
	 * @param mixed ...$args
	 * @return Stream
	 */
    function collect(...$args): Stream{
        return Stream::fromIterable($args);
    }
}

if(!function_exists("stream")){
	/**
	 * Wrap the given iterable into a stream
	 * @param iterable $it - The iterable to wrap
	 * @return Stream
	 */
    function stream(iterable $it): Stream{
        return Stream::fromIterable($it);
    }
}

if(!function_exists("infiniteRange")){
	/**
	 * Create an infinite range
	 * @param $start - The first element
	 * @param $step - The amount to increment by
	 * @return Stream
	 */
    function infiniteRange($start, $step){
        return Stream::range($start, null, $step);
    }
}

if(!function_exists("range")){
	/**
	 * Create a range of integers
	 * @param int $begin - The first element
	 * @param int|null $end - The maximum last element
	 * @param int $step - The amount to increment by
	 * @return Stream
	 */
    function range($begin = 0, $end = null, $step = 1){
        return Stream::range($begin, $end, $step);
    }
}

if(!function_exists("splitBy")){
	/**
	 * Make a stream by splitting a string in parts
	 * @param string $str - The string to split
	 * @param string $separator - The separator to split by
	 * @param bool $removeEmptyStrings - Whether or not it should remove empty strings from the resulting stream
	 * @return Stream
	 */
	function splitBy(string $str, string $separator = "", bool $removeEmptyStrings = true){
		return Stream::splitBy($str, $separator, $removeEmptyStrings);
	}
}

if(!function_exists("splitByRegex")){
	/**
	 * Make a stream by splitting a string in parts using a regular expression
	 * @param string $str - The string to split
	 * @param string $re - The regular expression to split by (cf. {@link https://www.php.net/manual/function.preg-split})
	 * @param bool $removeEmptyStrings - Whether or not it should remove empty strings from the resulting stream
	 * @return Stream
	 */
	function splitByRegex(string $str, string $re, bool $removeEmptyStrings = true){
		return Stream::splitByRegex($str, $re, $removeEmptyStrings);
	}
}
