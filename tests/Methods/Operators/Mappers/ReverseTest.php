<?php


namespace LazyCollection\Tests\Methods\Operators\Mappers;


use LazyCollection\Helpers;
use LazyCollection\Stream;
use LazyCollection\Tests\PHPUnit;

class ReverseTest extends PHPUnit
{
    /******************************************************************************************************************\
     * HELPERS
    \******************************************************************************************************************/
    /**
     * @param iterable $it
     * @return array
     */
    public function reverse(iterable $it){
        return Stream::fromIterable($it)
            ->reverse()
            ->toArray();
    }



    /******************************************************************************************************************\
     * TESTS
    \******************************************************************************************************************/
    /**
     * @test
     * @covers \LazyCollection\Stream::reverse
	 * @dataProvider provideRegularIterables
     *
     * @param iterable $input
     */
    public function reversesRegularIterables(iterable $input){
        $expected = array_reverse(Helpers::arrayFromIterable($input));
        $result = $this->reverse($input);
        $this->assertEquals($expected, $result);
    }

	/**
	 * @test
	 * @covers \LazyCollection\Stream::reverse
	 * @dataProvider provideAssociativeIterables
	 *
	 * @param iterable $expected
	 */
    public function doesNotMutateAssociativeIterables(iterable $expected){
    	$result = $this->reverse($expected);
    	$this->assertEquals($expected, $result);
	}


    /******************************************************************************************************************\
     * TEST PROVIDERS
    \******************************************************************************************************************/
    public function provideRegularIterables(){
        return [
            [
                [1, 2, 3],
            ],
			[
				[],
			],
			[
				[1, 5, 2, 16, 8],
			],
        ];
    }

    public function provideAssociativeIterables(){
    	return [
    		[
    			["key" => "value"],
			],
		];
	}
}
