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
}