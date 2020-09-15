<?php


namespace LazyCollection\Tests\Methods\Operators;


use LazyCollection\Stream;

class FlattenTest extends \LazyCollection\Tests\PHPUnit
{
    /******************************************************************************************************************\
     * HELPERS
    \******************************************************************************************************************/
    /**
     * @param iterable $it
     * @return array
     */
    protected function flatten(iterable $it){
        return Stream::fromIterable($it)
            ->flatten()
            ->toArray();
    }

    /******************************************************************************************************************\
     * TESTS
    \******************************************************************************************************************/
}
