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
 * @method Stream from(iterable $it) Make a stream from an iterable
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
 * @method Stream instancesOf(string $className) Keep only the instances of the given class
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
	 * @var callable[] $methods The registered methods
	 */
	protected static $methods = [];

	/**
	 * @var callable[] $factories The registered stream factories
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
			$method = static::$methods[$name];
			return Closure::fromCallable($method)->call($this, ...$args);
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
			$factory = static::$factories[$name];
			return Closure::fromCallable($factory)
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

		static::$methods[$name] = $method;
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

		static::$factories[$name] = $factory;
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