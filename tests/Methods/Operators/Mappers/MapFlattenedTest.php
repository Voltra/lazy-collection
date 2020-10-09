<?php


namespace LazyCollection\Tests\Methods\Operators\Mappers\Mappers\Mappers;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class MapFlattenedTest extends PHPUnit
{
    /******************************************************************************************************************\
     * HELPERS
    \******************************************************************************************************************/
    /**
     * @param iterable $it
     * @param callable $mapper
     * @return array
     */
    protected function mapFlattened(iterable $it, callable $mapper){
        return Stream::fromIterable($it)
            ->mapFlattened($mapper)
            ->toArray();
    }



    /******************************************************************************************************************\
     * TESTS
    \******************************************************************************************************************/
    /**
     * @test
     * @cover \LazyCollection\Stream::mapFlattened
     * @dataProvider provideChunkyArrays
     *
     * @param iterable $input
     * @param callable $mapper
     * @param iterable $expected
     */
    public function properlyFlattensAndThenMaps(iterable $input, callable $mapper, iterable $expected){
        $result = $this->mapFlattened($input, $mapper);
        $this->assertEquals($expected, $result);
    }

	/**
	 * @test
	 * @cover \LazyCollection\Stream::mapFlattened
	 * @dataProvider provideChunkyArrays
	 *
	 * @param iterable $input
	 * @param callable $mapper
	 * @param iterable $expected
	 */
    public function mapperIsCalledOncePerElement(iterable $input, callable $mapper, iterable $expected){
    	$count = count($expected);
    	$callback = $this->createCallbackMock($this->exactly($count), null, 42);
    	$result = $this->mapFlattened($input, $callback);
	}



    /******************************************************************************************************************\
     * TEST PROVIDERS
    \******************************************************************************************************************/
    public function provideChunkyArrays(){
        return [
            [
                [1, [2, 3], [4], 5, 6],
                function($e){ return 2 * $e; },
                [2, 4, 6, 8, 10, 12],
            ],
        ];
    }
}
