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
