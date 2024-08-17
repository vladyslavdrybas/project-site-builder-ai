<?php

namespace App\Tests;

use App\Utility\EmailHasher;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EmailHasherTest extends TestCase
{
    public function testSomething(): void
    {
        $parameterBagMock = $this->createStub(ParameterBagInterface::class)
        ;

        $parameterBagMock
            ->method('get')
            ->willReturnMap([
                ['app_secret' , 'app_secret'],
                ['hash_secret' , 'hash_secret'],
            ])
        ;

        $email = 'test@email.com';

        $hash = hash('sha256', $email . 'app_secret' . 'hash_secret');

        $hasher = new EmailHasher($parameterBagMock);

        $this->assertEquals($hash, $hasher->hash($email));
    }
}
