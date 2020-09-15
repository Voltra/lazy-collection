<?php


namespace LazyCollection\Tests\Methods\Terminators;


use LazyCollection\Stream;

class ToArrayTest extends \LazyCollection\Tests\PHPUnit
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
