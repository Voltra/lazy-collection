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

	public static function yes($_): bool{
		return true;
	}

	public static function negate(callable $predicate): callable{
		return function(...$args) use($predicate): bool{
			return !$predicate(...$args);
		};
	}
}