<?php


namespace LazyCollection\Tests\Methods\Operators;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class MapTest extends PHPUnit
{
    /******************************************************************************************************************\
     * HELPERS
    \******************************************************************************************************************/
    /**
     * @param iterable $it
     * @param callable $mapper
     * @return array
     */
    protected function map(iterable $it, callable $mapper){
        return Stream::fromIterable($it)
            ->map($mapper)
            ->toArray();
    }

    /******************************************************************************************************************\
     * TESTS
    \******************************************************************************************************************/
    /**
     * @test
     * @covers \LazyCollection\Stream::map
     * @dataProvider provideMappingData
     *
     * @param array $data
     * @param callable $mapper
     * @param array $expected
     */
    public function properlyMapsEachElement(array $data, callable $mapper, array $expected){
        $result = $this->map($data, $mapper);
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     * @covers \LazyCollection\Stream::map
     * @dataProvider provideMappingData
     *
     * @param array $data
     * @param callable $mapper
     * @param array $expected
     */
    public function mapperIsCalledOncePerElement(array $data, callable $mapper, array $expected){
        $count = count($data);
        $callback = $this->createCallbackMock($this->exactly($count), null, 2);
        $this->map($data, $callback);
    }

    /******************************************************************************************************************\
     * TEST PROVIDERS
    \******************************************************************************************************************/
    public function provideMappingData(){
        return [
            [
                [1,2,3],
                function($e){ return 2 * $e; },
                [2,4,6],
            ],
            [
                [2,4,6],
                function($e){ return 1 + $e; },
                [3,5,7],
            ],
        ];
    }

}
