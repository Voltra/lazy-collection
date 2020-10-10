<center><img src="./res/lazy-collection.png" alt="lazy-collection logo" width="250"/></center>

# Lazy Collection

>  A library for manipulating collections the lazy way

```bash
composer require voltra/lazy-collection
```

For more info, consult the [official documentation](https://voltra.github.io/lazy-collection) or join my [official discord server for help with libraries](https://discord.gg/JtWAjbw).

## Using the library

`use \LazyCollection\Stream;`

There are two ways you can use this library :

* using the factory functions (in the `LazyCollection` namespace)
* using the factory static methods

### Functions

There are a few functions predefined for you :

* `collect(...$args)` which creates a stream from the series of arguments
* `stream($iterable)` which creates a stream wrapping the given `iterable`
* `infiniteRange($start, $step)` which creates an infinite stream of numbers starting from `$start` incremented by `$step`
* `range($start = 0, $end = null, $step = 1)` which creates an kind of numbers range
* `splitBy($str, $separator, $removeEmptyStrings = true)` which creates a stream of strings by splitting `$str` into parts using `$separator`
* `splitByRegex($str, $re, $removeEmptyStrings = true)` which creates a stream of strings by splitting `$str` into parts using the regular expression `$re` (cf. [preg_split](Make a stream by splitting a string in parts using a regular expression))

### Factories

* `Stream::fromIterable($iterable)`
* `Stream::range($start = 0, $end = null, $step = 1)`
* `Stream::splitBy($str, $separator, $removeEmptyStrings = true`
* `splitByRegex($str, $re, $removeEmptyStrings = true)`



## Extend the library

`Stream` provides utilities to add methods and factories :

* `Stream::registerMethod($name, $method)` which can return an instance of `Stream` or something else
* `Stream::registerFactory($name, $factory)` which should return an instance of `Stream`

```php
use \LazyCollection\Stream;

Stream::registerMethod("mapTo42", static function(){
    /**
     * @var Stream $this
     */
    return $this->map(static function(){ return 42; });
});

Stream::fromIterable([1, 2, 3])
    ->mapTo42()
    ->toArray(); //-> [42, 42, 42]

Stream::registerFactory("answerToLife", function(){
    $gen = (static function(){
        yield 42;
    })();
    
    return new static($gen, false); // new static($generator, $isAssociative)
    
    /*
    Alternatively:
    
    return static:fromIterable([42]);
    */
});

Stream::answerToLife()->toArray(); //-> [42]
```

## Why use this library

Its goal is to provide a standalone library for collection manipulation with an elegant and fluent syntax and performance.

Because of its design, the following pieces are strictly equivalent in terms of complexity :

```php
$items = [1, 2, 3, 4, 5, 6];
$results = [];

foreach($items as $item){
    $mapped = 3 * $item - 2; // models 3x-2
    if($mapped % 2 === 0)
        $results[] = $mapped;
}

$streamResults = Stream::fromIterable($items)
    ->map(function($x){ return 3 * $x - 2; })
    ->filter(function($x){ return $x % 2 === 0; })
    ->toArray();

// $results is the same as $streamResults
```



No matter how much operations you use, it will always be `O(n)`. The equivalent of a single for-loop.



Note that some operations like `reverse` or the likes of `unique` and `sort` are considered **eager** operations (or **stateful**) as they need to iterate over the entire stream once before emitting values themselves.

The point is, any operation you do cost, in the worst case scenario, as much as what you could write by hand.



## Badges

![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/Voltra/lazy-collection) ![Packagist Downloads](https://img.shields.io/packagist/dm/voltra/lazy-collection) ![Packagist License](https://img.shields.io/packagist/l/voltra/lazy-collection) ![GitHub issues](https://img.shields.io/github/issues-raw/Voltra/lazy-collection) ![GitHub pull requests](https://img.shields.io/github/issues-pr-raw/Voltra/lazy-collection) ![Packagist Stars](https://img.shields.io/packagist/stars/voltra/lazy-collection) ![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/voltra/lazy-collection)



[![forthebadge](https://forthebadge.com/images/badges/built-with-love.svg)](https://forthebadge.com)