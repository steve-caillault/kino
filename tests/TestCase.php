<?php

namespace Tests;

use Illuminate\Foundation\Testing\{
    TestCase as BaseTestCase,
    DatabaseTransactions
};
use Illuminate\Support\Facades\DB;
use Faker\Generator as GeneratorFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    /**
     * Nombre de tests exécutés
     * @var int
     */
    private static int $count_tests_executed = 0;

    /**
     * Retourne une instance de Faker
     * Nous n'utilisons pas le trait WithFaker de Laravel car il ne peut pas être utilisé dans les DataProvider
     * @return GeneratorFaker
     */
    protected function getFaker() : GeneratorFaker
    {
        return with(new Faker)->get();
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     *
     * @throws \Mockery\Exception\InvalidCountException
     */
    protected function tearDown(): void
    {
        $this->beforeApplicationDestroyed(function() {
            $this->cleanDatabases();

            self::$count_tests_executed++;
        });
        parent::tearDown();
    }

    /**
     * Tronque les tables de la base de données lors de l'exécution d'un grand nombre de tests
     */
    private function cleanDatabases() : void
    {
        $modelClassesToTruncate = [
            \App\Models\AdminUser::class,
            \App\Models\MovieRoom::class,
        ];

        // On ne tronque pas les tables à chaque fois
        // Cela permet d'optimiser le temps d'exécution
        if(self::$count_tests_executed % 10 === 0)
        {
            DB::statement('SET foreign_key_checks=0');

            foreach($modelClassesToTruncate as $modelClassToTruncate)
            {
                with(new $modelClassToTruncate)->truncate();
            }

            DB::statement('SET foreign_key_checks=1');
        }
    }

    /**
     * Assert that a given where condition exists in the database.
     * On étend la méthode de Laravel pour pouvoir préciser l'opérateur dans $data
     *
     * @param  \Illuminate\Database\Eloquent\Model|string  $table
     * @param  array  $data
     * @param  string|null  $connection
     * @return $this
     */
    protected function assertDatabaseHas($table, array $data, $connection = null) : self
    {
        $query = $this->app->make('db')->connection($connection)->table($table);
        
        foreach($data as $rule)
        {
            list($left, $operator, $right) = $rule;
            $query->where($left, $operator, $right);
        }
        
        $count = $query->count();

        $this->assertGreaterThan(0, $count, sprintf(
            'Unable to find row in database table [%s] that matched attributes [%s].', $table, json_encode($data)
        ));

        return $this;
    }
}
