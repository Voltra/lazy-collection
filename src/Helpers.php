<?php


namespace LazyCollection;


abstract class Helpers {
	/**
	 * Determine whether or not the given value is iterable
	 * @param mixed $it The value to check
	 * @return bool
	 */
	public static function isIterable($it): bool{
		return is_iterable($it);
	}

	/**
	 * Determine whether or not the given array is associative
	 * @param mixed $arr The array to check
	 * @return bool
	 */
	public static function isAssoc($arr): bool{
		// is an array & no difference between its keys and the keys of it keys
		return is_array($arr) && array_diff_key($arr, array_keys(array_keys($arr)));
	}

	/**
	 * Make an array from an iterable
	 * @param iterable $it The iterable to get values from
	 * @return array
	 */
	public static function arrayFromIterable(iterable $it): array {
		$ret = [];

		foreach ($it as $key => $value){
			$ret[$key] = $value;
		}

		return $ret;
	}

	/**
	 * Convert an array to JSON
	 * @param array $arr The array to convert
	 * @param string $defaultJSON
	 * @return string
	 */
	public static function toJSON(array $arr, string $defaultJSON = ""){
		$json = json_encode($arr);
		return $json === false ? $defaultJSON : $json;
	}

	/**
	 * Check if the given value is not null
	 * @param mixed $value The value to check
	 * @return bool
	 */
	public static function notNull($value): bool{
		return $value !== null;
	}

	/**
	 * Determine whether or not the value is falsy
	 * @param mixed $value The value to check
	 * @return bool
	 */
	public static function falsy($value): bool{
		return !$value;
	}

	/**
	 * Determine whether or not the value is truthy
	 * @param mixed $value The value to check
	 * @return bool
	 */
	public static function truthy($value): bool{
		return !static::falsy($value);
	}

	/**
	 * Determine whether or not the given variable is an instance of the given qualified class name
	 * @param mixed $obj The value to check
	 * @param string $class The fully qualified class name
	 * @return bool
	 */
	public static function instanceOf($obj, string $class): bool{
		return $obj instanceof $class;
	}

	/**
	 * A predicate that is always satisfied
	 * @param mixed ...$args
	 * @return bool
	 */
	public static function yes(...$args): bool{
		return true;
	}

	/**
	 * A predicate that is never satisfied
	 * @param mixed ...$args
	 * @return bool
	 */
	public static function no(...$args): bool{
		return false;
	}

	/**
	 * Negate a predicate
	 * @param callable $predicate
	 * @return callable
	 */
	public static function negate(callable $predicate): callable{
		return function(...$args) use($predicate): bool{
			return !$predicate(...$args);
		};
	}

	/**
	 * The identity function
	 * @param mixed $x
	 * @return mixed
	 */
	public static function identity($x){
		return $x;
	}

	/**
	 * Return its first argument
	 * @param $a
	 * @param mixed ...$rest
	 * @return mixed
	 */
	public static function firstArg($a, ...$rest){
		return $a;
	}

	/**
	 * Return its second argument
	 * @param $a
	 * @param $b
	 * @param mixed ...$rest
	 * @return mixed
	 */
	public static function secondArg($a, $b, ...$rest){
		return $b;
	}

	/**
	 * Merge defaults with user provided options
	 * @param array $defaults The array of defaults
	 * @param array $modified The user provided options
	 * @return array
	 */
	public static function merge(array $defaults, array $modified){
		if(!static::isAssoc($modified)) {
			return $defaults;
		}

//		return array_merge_recursive($defaults, $modified);
		return array_merge($defaults, $modified);
	}

	/**
	 * Interpolate a value to string
	 * @param mixed $value The variable to stringify
	 * @return string
	 */
	public static function interpolate($value): string{
		if($value === true)
			return "true";
		if($value === false)
			return "false";
		if($value === null)
			return "null";

		return (string)$value;
	}

	/**
	 * Basic comparator
	 * @param mixed $lhs
	 * @param mixed $rhs
	 * @return int
	 */
	public static function cmp($lhs, $rhs): int{
		return $lhs <=> $rhs;
	}

	/**
	 * Remove duplicates using the keying function
	 * @param callable $key
	 * @param array $arr
	 * @return array
	 */
	public static function uniqueBy(callable $key, array $arr): array {
		$mapped = array_map($key, $arr);
		$unique = array_unique($mapped, SORT_REGULAR);
		return array_map(static function($key, $value) use($arr){
			return $arr[$key];
		}, array_keys($unique), $unique);
	}

	/**
	 * Sort the array by the key extractor
	 * @param callable $keyExtractor
	 * @param array $arr
	 * @param int $flags
	 * @return array
	 */
	public static function sortBy(callable $keyExtractor, array $arr, int $flags = SORT_REGULAR): array{
		$tmp = array_merge([], $arr);
		$mapped = array_map($keyExtractor, $tmp);

		$keyed = []; // mapping of key (via $keyExtractor) to value (from $arr/$tmp)
		for($i = 0, $length = count($tmp) ; $i < $length ; ++$i)
			$keyed[$mapped[$i]] = $tmp[$i];

		$sorted = static::doSort($mapped, $flags);
		return array_map(function($value) use($keyed){
			return $keyed[$value];
		}, $sorted);
	}

	/**
	 * Sort the array
	 * @param array $array
	 * @param int $flags
	 * @return mixed
	 */
	public static function doSort(array $array, int $flags = SORT_REGULAR){
		/*$didSort =*/ sort($array, $flags);
		return $array;
	}

	/**
	 * Sort the array with the given comparator
	 * @param callable $comparator
	 * @param array $arr
	 * @return array
	 */
	public static function sortWith(callable $comparator, array $arr): array{
		usort($arr, $comparator);
		return $arr;
	}
}
