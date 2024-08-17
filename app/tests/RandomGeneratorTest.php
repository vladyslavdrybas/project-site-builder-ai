<?php

namespace App\Tests;

use App\Utility\RandomGenerator;
use PHPUnit\Framework\TestCase;

class RandomGeneratorTest extends TestCase
{
    public function testUniqueId(): void
    {
        $generator = new RandomGenerator();

        $id = explode('.' , $generator->uniqueId());

        $this->assertEquals(13, strlen($id[0]));
        $this->assertEquals(6, strlen($id[1]));
    }
}
