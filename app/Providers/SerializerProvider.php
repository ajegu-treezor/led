<?php


namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Serializer::class, function() {
            return new Serializer([new ObjectNormalizer()], []);
        });
    }
}
