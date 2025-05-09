<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Console\Test\InteractsWithConsole;

class LoadCommandTest extends KernelTestCase
{
    use InteractsWithConsole;

    public function test_can_load_products(): void
    {
        $this->executeConsoleCommand('app:load --purge')
            ->assertSuccessful() // command exit code is 0
            ->assertOutputContains('success')
            ->assertOutputNotContains('failed')
        ;

    }
}
