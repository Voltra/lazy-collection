<?php


use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class RangeTest extends PHPUnit
{
    /******************************************************************************************************************\
     * TESTS
    \******************************************************************************************************************/
    /**
     * @test
     * @covers Stream::range
     */
    public function emptyRangeMakesAnEmptyStream(){
        $stream = Stream::range(0, 0);
        $this->assertCount(0, $stream);
    }

    /**
     * @test
     * @covers \LazyCollection\Stream::range
     * @dataProvider provideIncreasingRange
     * @param array $testData
     */
    public function increasingRangeProducesTheExpectedValues(array $testData){
        [$begin, $end, $step, $expected] = $testData;
        $stream = Stream::range($begin, $end, $step);
        $value = $stream->toArray();
        $this->assertEquals($expected, $value);
    }

    /**
     * @test
     * @covers \LazyCollection\Stream::range
     * @dataProvider provideDecreasingRange
     *
     * @param array $testData
     */
    public function decreasingRangeProducesTheExpectedValues(array $testData){
        [$begin, $end, $step, $expected] = $testData;
        $stream = Stream::range($begin, $end, $step);
        $value = $stream->toArray();
        $this->assertEquals($expected, $value);
    }

    /******************************************************************************************************************\
     * TEST PROVIDERS
    \******************************************************************************************************************/
    public function provideIncreasingRange(){
        return [
            [ // Test Bucket #0
                [0, 2, 1, [0,1]],
                [0, 4, 1, [0,1,2,3]],
            ],
            [ // Test Bucket #1
                [0, 4, 2, [0,2]],
                [0, 8, 2, [0,2,4,6]],
            ],
            [ // Test Bucket #2
                [0, 7, 3, [0,3,6]],
                [0, 9, 3, [0,3,6]],
                [0, 16, 3, [0,3,6,9,12,15]],
            ],
        ];
    }

    public function provideDecreasingRange(){
        return [
            [ // Test Bucket #0
                [2, 0, -1, [2, 1]],
                [4, 0, 1, [4, 3, 2, 1]],
            ],
            [ // Test Bucket #1
                [4, 0, -2, [4, 2]],
                [8, 0, 2, [8, 6, 4, 2]],
            ],
            [ // Test Bucket #2
                [7, 0, -3, [7, 4, 1]],
                [9, 0, 3, [9, 6, 3]],
                [16, 0, -3, [16, 13, 10, 7, 4, 1]],
            ],
        ];
    }
}
