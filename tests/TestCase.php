<?php

namespace Woolf\Carter\Tests;

use PHPUnit_Framework_TestCase;

abstract class TestCase extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        if (class_exists('Mockery')) {
            \Mockery::close();
        }
    }
}
