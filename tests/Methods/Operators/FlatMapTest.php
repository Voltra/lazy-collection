<?php


namespace LazyCollection\Tests\Methods\Operators;


use LazyCollection\Stream;

class FlatMapTest extends \LazyCollection\Tests\PHPUnit
{
    /******************************************************************************************************************\
     * HELPERS
    \******************************************************************************************************************/
    /**
     * @param iterable $it
     * @param callable $mapper
     * @return array
     */
    protected function flatMap(iterable $it, callable $mapper){
        return Stream::fromIterable($it)
            ->flatMap($mapper)
            ->toArray();
    }



    /******************************************************************************************************************\
     * TESTS
    \******************************************************************************************************************/
    /**
     * @test
     * @covers \LazyCollection\Stream::flatMap
     * @dataProvider provideFlatMapData
     *
     * @param iterable $input
     * @param callable $mapper
     * @param iterable $expected
     */
    public function flatMapsStuff(iterable $input, callable $mapper, iterable $expected){
        $result = $this->flatMap($input, $mapper);
        $this->assertEquals($expected, $result);
    }



    /******************************************************************************************************************\
     * TEST PROVIDERS
    \******************************************************************************************************************/
    public function provideFlatMapData(){
        return [
            [
                [1, 2, 3],
                function($e){ return [$e, $e]; },
                [1, 1, 2, 2, 3, 3],
            ],
            [
                [1, 2, 3],
                function($e){ return [$e, $e, $e]; },
                [1, 1, 1, 2, 2, 2, 3, 3, 3],
            ],
            [
                [1, 2, 3],
                function($e){ return [ "key__$e" => $e ]; },
                [
                    "key__1" => 1,
                    "key__2" => 2,
                    "key__3" => 3,
                ],
            ],
        ];
    }
}
