<?php
namespace LazyCollection;

use Closure;
use Generator;
use IteratorAggregate;
use LazyCollection\Exceptions\InvalidFactoryException;
use LazyCollection\Exceptions\InvalidMethodException;

/**
 * Represents a stream of data
 * @package LazyCollection
 *
 *
 * @method static Stream fromIterable(iterable $it, bool $isAssoc = null) Make a stream from an iterable
 * @method static Stream range(int $start = 0, int $end = null, int $step = 1) Make a stream from a range of integers
 * @method static Stream splitBy(string $str, string $separator = "", bool $removeEmptyStrings = true) Make a stream by splitting a string in parts
 *
 *
 * @method Stream map(callable $mapper) Transform each value using the mapper function
 * @method Stream peek(callable $cb) Execute a callback on each value
 * @method Stream flatten() Flattens a stream of iterable to a stream of data (noop for associative)
 * @method Stream flatMap(callable $mapper) Maps a stream to a stream of iterable then flattens its (associative will only map)
 * @method Stream mapFlattened(callable $mapper) Flattens the stream then maps it (associative will only map)
 * @method Stream reverse() Reverses the order of the stream (eager, works only for finite streams)
 *
 * @method Stream filter(callable $predicate) Filters only elements satisfying the given predicate
 * @method Stream filterNot(callable $predicate) Filters only elements that do not satisfy the given predicate
 * @method Stream filterOut(callable $predicate) Alias for filterNot
 * @method Stream notNull() Filters only elements non null
 * @method Stream falsy() Filters only elements that are considered falsy
 * @method Stream truthy() Filters only elements that are considered truthy (i.e. not falsy)
 * @method Stream instanceOf(string $className) Keep only the instances of the given class
 * @method Stream notInstanceOf(string $className) Keep only the items that are not instances of the given class
 *
 * @method Stream then(iterable $it) Streams the elements of the stream then the elements of the iterable
 * @method Stream zipWith(Stream $rhs) Transform a pair of stream into a stream of pairs (of shortest length)
 *
 *
 * @method Stream takeWhile(callable $predicate) Takes elements while the predicate is satisfied
 * @method Stream takeUntil(callable $predicate) Takes elements until the predicate is satisfied
 * @method Stream take(int $maxAmount) Takes at most the first $maxAmount elements
 * @method Stream skipWhile(callable $predicate) Skips elements while the predicate is satisfied
 * @method Stream skipUntil(callable $predicate) Skips elements until the predicate is satisfied
 * @method Stream skip(int $maxAmount) Skips at most the first $maxAmount elements
 * @method Stream subStream(int $startIndex, int $endIndex) Skips $startIndex elements and takes $endIndex-$startIndex elements
 * @method Stream uniqueBy(callable $idExtractor) Remove elements/duplicates that have the same id by the $idExtractor
 * @method Stream unique() Remove duplicates
 *
 * @method Stream sortBy(callable $idExtractor) Sorts the elements by comparing the extracted IDs (ascending order)
 * @method Stream sort() Sorts the elements in ascending order
 * @method Stream sortByDescending(callable $idExtractor) Sorts the elements by comparing the extracted IDs (descending order)
 * @method Stream sortDescending() Sorts the elements in descending order
 * @method Stream sortWith(callable $comparator) Sorts the elements using the given comparator
 *
 * @method Stream chunks(int $size) Split the stream in a stream of chunks (which size is at most $size)
 *
 *
 * @method void forEach(callable $cb) Execute a callback on each element
 * @method mixed|null reduce(callable $reducer, mixed $init = null) Reduces the stream to a single value via the reducer (if no init is provided then it will be the first element)
 * @method int count(callable $predicate = [Helpers::class, "yes"]) Counts the amount of elements that satisfy the predicate (if no predicate is provided, will count the amount of elements)
 *
 * @method array toArray() Consume the stream and put the values in an array
 * @method string toJSON() Consume the stream and transforms it into JSON
 * @method array associateBy(callable $valueFactory = [Helpers::class, "identity"], callable $keyFactory = [Helpers::class, "identity"]) Create an associative array by generating key and value separately
 * @method array associate(callable $factory) Create an associative array by generating a [$key, $value] pair
 * @method array groupBy(callable $keyExtractor) Group an array of associative arrays into an associative array of arrays
 * @method string join(array $options) Join to string using prefix, separator, suffix, stringifier, strlen and substr options
 * @method array partition(callable $predicate) Partitions the values based on the predicate (true is left, false is right)
 * @method number|null sum() Computes the sum of all the elements
 * @method number|null sumBy(callable $mapper) Computes the sum of all the mapped elements
 * @method number|null average() Computes the average of all the elements
 * @method array unzip() Unzips a stream of pair into a pair of arrays
 *
 * @method bool all(callable $predicate) Determine whether or not all the elements satisfy the predicate
 * @method bool none(callable $predicate) Determine whether or not none of the elements satisfy the predicate
 * @method bool any(callable $predicate) Determine whether or any of the elements satisfy the predicate
 * @method bool notAll(callable $predicate) Determine whether or any of the elements does not satisfy the predicate
 *
 * @method mixed first(callable $predicate = [Helpers::class, "yes"]) Get the first element that match the predicate (or the very first if no predicate is provided) or throw if none
 * @method mixed firstOr(mixed $default, callable $predicate = [Helpers::class, "yes"]) Get the first element that match the predicate or the default element if none
 * @method mixed|null firstOrNull(callable $predicate = [Helpers::class, "yes"]) Get the first element that match the predicate or null if none
 * @method mixed last(callable $predicate = [Helpers::class, "yes"]) Get the last element that match the predicate (or the very first if no predicate is provided) or throw if none
 * @method mixed lastOr(mixed $default, callable $predicate = [Helpers::class, "yes"]) Get the last element that match the predicate or the default element if none
 * @method mixed|null lastOrNull(callable $predicate = [Helpers::class, "yes"]) Get the first element last match the predicate or null if none
 * @method mixed atIndex(int $i) Get the element at index $i or throw if none
 * @method mixed atIndexOr(int $i, mixed $default) Get the $i element or the default if none
 * @method mixed|null atIndexOrNull(int $i) Get the $i element or null if none
 * @method int indexOfFirst(callable $predicate) Get the index of the first element that satisfies the predicate, or -1
 * @method int indexOf(mixed $needle) Get the first index of the $needle, or -1
 * @method int indexOfLast(callable $predicate) Get the index of the last element that satisfies the predicate, or -1
 * @method mixed|null maxBy(callable $mapper) Get the max value determined by comparing mapped values
 * @method mixed|null max() Get the max value
 * @method mixed|null maxWith(callable $comparator) Get the max value using a custom comparator
 * @method mixed|null minBy(callable $mapper) Get the min value determined by comparing mapped values
 * @method mixed|null min() Get the min value
 * @method mixed|null minWith(callable $comparator) Get the min value using a custom comparator
 * @method mixed single(callable $predicate = [Helpers::class, "yes"]) Get the single element that satisfies the predicate (if no predicate is given, the single element), or throws
 * @method mixed singleOr(mixed $default, callable $predicate = [Helpers::class, "yes"]) Get the single element that satisfies the predicate (if no predicate is given, the single element), or get the default
 * @method mixed singleOrNull(callable $predicate = [Helpers::class, "yes"]) Get the single element that satisfies the predicate (if no predicate is given, the single element), or null
 * @method bool contains(mixed $needle) Determine whether or not the stream contains the $needle
 */
class Stream implements IteratorAggregate {
	/******************************************************************************************************************\
	 * Properties
	\******************************************************************************************************************/
	/**
	 * @var Generator $gen The generator that provides the stream's values
	 */
	protected $gen;

	/**
	 * @var bool $associative Whether or not the stream is associative
	 */
	protected $associative;

	/**
	 * @var Closure[] $methods The registered methods
	 */
	protected static $methods = [];

	/**
	 * @var Closure[] $factories The registered stream factories
	 */
	protected static $factories = [];



	/******************************************************************************************************************\
	 * Methods
	\******************************************************************************************************************/
	/**
	 * Construct a stream from its generator
	 * @param Generator $gen The generator to get values from
	 * @param bool $associative Whether or not the stream is associative
	 */
	protected function __construct(Generator $gen, bool $associative = false) {
		$this->gen = $gen;
		$this->associative = $associative;
	}

	/**
	 * Construct a consumer stream from this stream
	 * @param callable $generatorFactory A function that accepts the current generator and returns a new generator
	 * @param bool $associative
	 * @return self
	 */
	protected function pipe(callable $generatorFactory, bool $associative = false): self{
		return new static($generatorFactory($this->gen), $this->associative || $associative);
	}

	/**
	 * Call a registered method
	 * @param string $name The name of the method
	 * @param array $args The arguments for the method
	 * @return mixed
	 * @throws InvalidMethodException
	 * @uses static::$methods for lookup
	 */
	public function __call(string $name, array $args) {
		if(static::hasMethod($name)){
			$closure = static::$methods[$name];
			return $closure->call($this, ...$args);
		}

		throw new InvalidMethodException("Method $name does not exist");
	}

	/**
	 * Call a registered factory
	 * @param string $name The name of the factory method
	 * @param array $args The arguments for the factory
	 * @return mixed
	 * @throws InvalidFactoryException
	 * @uses static::$factories for lookup
	 * @uses static::nullInstance for closure binding
	 */
	public static function __callStatic(string $name, array $args): self {
		if(static::hasFactory($name)){
			$closure = static::$factories[$name];
			return $closure(...$args);
		}

		throw new InvalidFactoryException("Factory $name does not exist");
	}


	protected static function nullInstance(): Stream{
		return new static((static function(){
			yield null;
		})());
	}


	/******************************************************************************************************************\
	 * Extending
	\******************************************************************************************************************/
	/**
	 * Determine whether or not a method is registered with the given name
	 * @param string $name The method to lookup
	 * @return bool
	 * @uses static::$methods for lookup
	 */
	protected static function hasMethod(string $name): bool{
		return array_key_exists($name, static::$methods);
	}

	/**
	 * Determine whether or not a factory is registered with the given name
	 * @param string $name The factory to lookup
	 * @return bool
	 * @uses static::$factories for lookup
	 */
	protected static function hasFactory(string $name): bool{
		return array_key_exists($name, static::$factories);
	}

	/**
	 * Register an extension method
	 * @param string $name The name of the method
	 * @param callable $method The function to execute
	 * @throws InvalidMethodException
	 * @uses static::$methods for lookup and insertion
	 */
	public static function registerMethod(string $name, callable $method): void {
		if(static::hasMethod($name)) {
			throw new InvalidMethodException("Method $name already exists");
		}

		static::$methods[$name] = Closure::fromCallable($method);
	}

	/**
	 * Register a stream factory
	 * @param string $name The name of the static method
	 * @param callable $factory The function to execute
	 * @throws InvalidFactoryException
	 * @uses static::$factories for lookup and insertion
	 */
	public static function registerFactory(string $name, callable  $factory): void{
		if(static::hasFactory($name)){
			throw new InvalidFactoryException("Factory $name already exists");
		}

		static::$factories[$name] = Closure::bind($factory, null, static::class);
	}


	/******************************************************************************************************************\
	 * implements Iterator
	\******************************************************************************************************************/
	/**
	 * @inheritDoc
	 */
	public function getIterator() {
		return $this->gen;
	}
}
