<?php


namespace App\Providers;


use Aws\DynamoDb\DynamoDbClient;
use Illuminate\Support\ServiceProvider;

class DynamoDbProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DynamoDbClient::class, function ($app) {

            return $app->make('aws')->createDynamoDb([
                'endpoint' => 'http://dynamodb:8000',
                'credentials' => [
                    'key' => 'not-a-real-key',
                    'secret' => 'not-a-real-secret',
                ],
            ]);

        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [DynamoDbClient::class];
    }
}
