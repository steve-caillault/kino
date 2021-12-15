<?php

namespace Tests;

use Illuminate\Foundation\Testing\{
    TestCase as BaseTestCase,
    DatabaseTransactions
};
use Faker\Generator as GeneratorFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    /**
     * Retourne une instance de Faker
     * Nous n'utilisons pas le trait WithFaker de Laravel car il ne peut pas être utilisé dans les DataProvider
     * @return GeneratorFaker
     */
    protected function getFaker() : GeneratorFaker
    {
        return with(new Faker)->get();
    }

}
