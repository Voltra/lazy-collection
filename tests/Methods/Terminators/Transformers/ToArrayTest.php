<?php


namespace LazyCollection\Tests\Methods\Terminators\Transformers;


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class ToArrayTest extends PHPUnit
{
    /**
     * @test
     * @covers Stream::toArray
     * @dataProvider provideArrays
     *
     * @param array $expected
     */
    public function properlyConvertsToArray(array $expected){
        $result = Stream::fromIterable($expected)->toArray();
        $this->assertEquals($expected, $result);
    }

    /******************************************************************************************************************\
     * TEST PROVIDERS
    \******************************************************************************************************************/
    public function provideArrays(){
        return [
            [
                [0,1,2],
            ],
            [
                [
                    "key" => "stuff",
                    "value" => "thing",
                ],
            ],
        ];
    }
}
