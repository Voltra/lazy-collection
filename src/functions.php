<?php

namespace LazyCollection;

if(!function_exists("collect")){
    function collect(...$args): Stream{
        return Stream::fromIterable($args);
    }
}

if(!function_exists("stream")){
    function stream(iterable $it): Stream{
        return Stream::fromIterable($it);
    }
}

if(!function_exists("infiniteRange")){
    function infiniteRange($start, $step){
        return Stream::range($start, null, $step);
    }
}

if(!function_exists("range")){
    function range($begin = 0, $end = null, $step = 1){
        return Stream::range($begin, $end, $step);
    }
}
