<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    #[TestDox('Verifica se a asserção básica "true é true" funciona corretamente.')]
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }
}
