<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function signInAdmin(): static
    {
        $this->withSession([
            'admin_authenticated' => true,
        ]);

        return $this;
    }
}
