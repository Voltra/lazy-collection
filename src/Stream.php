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
 * @method Stream fromIterable(iterable $it) Make a stream from an iterable
 *
 * @method Stream map(callable $mapper) Transform each value using the mapper function
 * @method Stream peek(callable $cb) Execute a callback on each value
 * @method Stream flatten() Flattens a stream of iterable to a stream of data (noop for associative)
 * @method Stream flatMap(callable $mapper) Maps a stream to a stream of iterable then flattens its (associative will only map)
 * @method Stream mapFlattened(callable $mapper) Flattens the stream then maps it (associative will only map)
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
 * @method Stream takeWhile(callable $predicate) Takes elements while the predicate is satisfied
 * @method Stream takeUntil(callable $predicate) Takes elements until the predicate is satisfied
 * @method Stream take(int $maxAmount) Takes at most the first $maxAmount elements
 * @method Stream skipWhile(callable $predicate) Skips elements while the predicate is satisfied
 * @method Stream skipUntil(callable $predicate) Skips elements until the predicate is satisfied
 * @method Stream skip(int $maxAmount) Skips at most the first $maxAmount elements
 * @method Stream subStream(int $startIndex, int $endIndex) Skips $startIndex elements and takes $endIndex-$startIndex elements
 *
 * @method Stream chunks(int $size) Split the stream in a stream of chunks (which size is at most $size)
 *
 * @method void forEach(callable $cb) Execute a callback on each element
 * @method mixed reduce(callable $reducer, mixed $init = null) Reduces the stream to a single value via the reducer (if no init is provided then it will be the first element)
 * @method int count(callable $predicate = [Helpers::class, "yes"]) Counts the amount of elements that satisfy the predicate (if no predicate is provided, will count the amount of elements)
 *
 * @method array toArray() Consume the stream and put the values in an array
 * @method string toJSON() Consume the stream and transforms it into JSON
 *
 * @method bool all(callable $predicate) Determine whether or not all the elements satisfy the predicate
 * @method bool none(callable $predicate) Determine whether or not none of the elements satisfy the predicate
 * @method bool any(callable $predicate) Determine whether or any of the elements satisfy the predicate
 * @method bool notAll(callable $predicate) Determine whether or any of the elements does not satisfy the predicate
 *
 * @method mixed first(callable $predicate = [Helpers::class, "yes"]) Get the first element that match the predicate (or the very first if no predicate is provided)
 * @method mixed firstOr(mixed $default, callable $predicate = [Helpers::class, "yes"]) Get the first element that match the predicate or the default element if none
 * @method mixed firstOrNull(callable $predicate = [Helpers::class, "yes"]) Get the first element that match the predicate or null if none
 * @method mixed last(callable $predicate = [Helpers::class, "yes"]) Get the last element that match the predicate (or the very first if no predicate is provided)
 * @method mixed lastOr(mixed $default, callable $predicate = [Helpers::class, "yes"]) Get the last element that match the predicate or the default element if none
 * @method mixed lastOrNull(callable $predicate = [Helpers::class, "yes"]) Get the first element last match the predicate or null if none
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
	 * @return self
	 */
	protected function pipe(callable $generatorFactory): self{
		return new static($generatorFactory($this->gen), $this->associative);
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
	 */
	public static function __callStatic(string $name, array $args): self {
		if(static::hasFactory($name)){
			$closure = static::$factories[$name];
			return Closure::fromCallable($closure)
					->bindTo(null, static::class)
					->call(null, ...$args);
		}

		throw new InvalidFactoryException("Factory $name does not exist");
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
	public static function addMethod(string $name, callable $method): void {
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
	public static function addFactory(string $name, callable  $factory): void{
		if(static::hasFactory($name)){
			throw new InvalidFactoryException("Factory $name already exists");
		}

		static::$factories[$name] = Closure::fromCallable($factory);
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