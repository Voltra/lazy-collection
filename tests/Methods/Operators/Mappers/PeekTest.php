<?php


namespace LazyCollection\Tests\Methods\Operators\Mappers;


use LazyCollection\Stream;

class PeekTest extends \LazyCollection\Tests\PHPUnit
{
    /******************************************************************************************************************\
     * HELPERS
    \******************************************************************************************************************/
    /**
     * @param iterable $it
     * @param callable $cb
     * @return array
     */
    protected function peek(iterable $it, callable $cb){
        return Stream::fromIterable($it)
            ->peek($cb)
            ->toArray();
    }



    /******************************************************************************************************************\
     * TESTS
    \******************************************************************************************************************/
    /**
     * @test
     * @covers \LazyCollection\Stream::peek
     * @dataProvider providePeekData
     *
     * @param array $expected
     * @param callable $cb
     */
    public function doesNotMutateTheInput(array $expected, callable $cb){
        $result = $this->peek($expected, $cb);
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     * @covers \LazyCollection\Stream::peek
     * @dataProvider providePeekData
     *
     * @param array $expected
     * @param callable $cb
     */
    public function properlyCallsTheCallback(array $expected, callable $cb){
        $count = count($expected);
        $callable = $this->createCallbackMock($this->exactly($count), null, 2);
        $this->peek($expected, $callable);
    }


    /******************************************************************************************************************\
     * TEST PROVIDERS
    \******************************************************************************************************************/
    public function providePeekData(){
        return [
            [
                [1,2,3],
                function($e){ return 2 * $e; },
            ],
            [
                [2,4,6],
                function($e){ return 1 + $e; },
            ],
            [
                [],
                function($e){ return 1 + $e; },
            ],
        ];
    }
}
