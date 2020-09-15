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
    /**
     * @test
     * @covers \LazyCollection\Stream::flatten
     */
    public function emptyArrayStaysTheSame(){
        $result = $this->flatten([]);
        $this->assertEmpty($result);
    }

    /**
     * @test
     * @covers \LazyCollection\Stream::flatten
     * @dataProvider provideRegularIterable
     *
     * @param array $input
     * @param array $expected
     */
    public function regularIterableIsFlattened(iterable $input, iterable $expected){
        $result = $this->flatten($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     * @covers \LazyCollection\Stream::flatten
     * @dataProvider provideAssociativeIterable
     *
     * @param iterable $expected
     */
    public function associativeIterableIsNotFlattened(iterable $expected){
        $result = $this->flatten($expected);
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     * @covers \LazyCollection\Stream::flatten
     * @dataProvider provideArrayOfAssociative
     *
     *
     * @param iterable $input
     * @param iterable $expected
     */
    public function arrayOfAssociative(iterable $input, iterable $expected){
        $result = $this->flatten($input);
        $this->assertEquals($expected, $result);
    }

    /******************************************************************************************************************\
     * TEST PROVIDERS
    \******************************************************************************************************************/
    public function provideRegularIterable(){
        return [
            [
                [1, [2,3], 4],
                [1,2,3,4],
            ],
        ];
    }

    public function provideAssociativeIterable(){
        return [
            [
                [
                    "random" => "pelo",
                    "key" => "value",
                ],
            ],
        ];
    }

    public function provideArrayOfAssociative(){
        return [
            [
                [
                    ["key1" => 1],
                    ["key2" => 2],
                    ["key3" => 3],
                ],
                [
                    "key1" => 1,
                    "key2" => 2,
                    "key3" => 3,
                ],
            ]
        ];
    }
}
