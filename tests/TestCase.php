<?php
namespace Dameety\Paybox\Tests;

use Exception;
use Dameety\Paybox\Tests\Utils\User;
use Illuminate\Foundation\Exceptions\Handler;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Contracts\Debug\ExceptionHandler;

abstract class TestCase extends Orchestra
{
    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->actingAs( new User() );

        $this->disableExceptionHandling();
    }

    public function setUpDatabase()
    {
        $this->loadMigrationsFrom([
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__ . '/migrations'),
        ]);
        $this->artisan('migrate', ['--database' => 'testing']);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('paybox.uri', 'paybox');
        $app['config']->set('paybox.middleware', ['web', 'auth']);
        $app['config']->set('paybox.currency.code', 'usd');
        $app['config']->set('paybox.userModel', User::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            \Dameety\Paybox\PayboxServiceProvider::class,
            \Orchestra\Database\ConsoleServiceProvider::class,
            \Cviebrock\EloquentSluggable\ServiceProvider::class
        ];
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(
            ExceptionHandler::class, new class extends Handler
            {
                public function __construct() { }
                public function report(Exception $exception) { }
                public function render($request, Exception $exception)
                {
                    throw $exception;
                }
            }
        );
    }
}
