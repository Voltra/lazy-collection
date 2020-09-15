<?php


namespace LazyCollection\Tests;


use Jasny\PHPUnit\CallbackMockTrait;
use Jasny\PHPUnit\ExpectWarningTrait;
use Jasny\PHPUnit\PrivateAccessTrait;
use Jasny\PHPUnit\SafeMocksTrait;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;

class PHPUnit extends TestCase {
    use PHPMock;
    use CallbackMockTrait;
    use ExpectWarningTrait;
    use PrivateAccessTrait;
    use SafeMocksTrait;
}
