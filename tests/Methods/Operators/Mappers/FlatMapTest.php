<?php


namespace LazyCollection\Tests\Methods\Operators\Mappers\Mappers\Mappers\Mappers\Mappers;


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
     * @cover \LazyCollection\Stream::flatMap
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

	/**
	 * @test
	 * @cover \LazyCollection\Stream::flatMap
	 * @dataProvider provideFlatMapData
	 *
	 * @param iterable $input
	 * @param callable $mapper
	 * @param iterable $expected
	 */
    public function mapperIsCalledOncePerElement(iterable $input, callable $mapper, iterable $expected){
    	$count = count($input);
    	$callback = $this->createCallbackMock($this->exactly($count), null, []);
    	$this->flatMap($input, $callback);
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
