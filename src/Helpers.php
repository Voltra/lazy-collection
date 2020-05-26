<?php


namespace LazyCollection;


abstract class Helpers {
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

	public static function toJSON(array $arr){
		return json_encode($arr, true);
	}

	public static function notNull($value): bool{
		return $value !== null;
	}

	public static function falsy($value): bool{
		return !$value;
	}

	public static function truthy($value): bool{
		return !static::falsy($value);
	}

	public static function instanceOf($obj, string $class): bool{
		return $obj instanceof $class;
	}

	public static function yes(...$args): bool{
		return true;
	}

	public static function negate(callable $predicate): callable{
		return function(...$args) use($predicate): bool{
			return !$predicate(...$args);
		};
	}

	public static function identity($x){
		return $x;
	}

	public static function merge(array $defaults, array $modified){
		if(!static::isAssoc($modified)) {
			return $defaults;
		}

		return array_merge_recursive($defaults, $modified);
	}
	
	public static function interpolate($value): string{
		return (string)$value;
	}

	public static function cmp($lhs, $rhs): int{
		return $lhs <=> $rhs;
	}

	public static function uniqueBy(callable $key, array $arr): array {
		$mapped = array_map($key, $arr);
		$unique = array_unique($mapped, SORT_REGULAR);
		return array_map(static function($key, $value) use($arr){
			return $arr[$key];
		}, array_keys($unique), $unique);
	}

	public static function sortBy(callable $keyExtractor, array $arr, int $flags = SORT_REGULAR): array{
		$tmp = array_merge([], $arr);
		$mapped = array_map($keyExtractor, $tmp);
		$sorted = static::doSort($mapped, $flags);
		return array_map(static function($key, $value) use($arr){
			return $arr[$key];
		}, array_keys($sorted), $sorted);
	}

	public static function doSort($array, int $flags = SORT_REGULAR){
		asort($array, $flags);
		return $array;
	}

	public static function sortWith(callable $comparator, array $arr): array{
		usort($arr, $comparator);
		return $arr;
	}
}