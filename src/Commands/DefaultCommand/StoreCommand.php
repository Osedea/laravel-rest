<?php

namespace Osedea\LaravelRest\Commands\DefaultCommand;

use Request;
use ReflectionClass;
use Illuminate\Contracts\Bus\SelfHandling;
use Osedea\LaravelRest\Commands\Command;

class StoreCommand extends Command implements SelfHandling
{
    public $modelClass;

    public function __construct($modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $class = $this->modelClass;
        $mock = new $class();

        $reflection = new ReflectionClass($class);
        $property = $reflection->getProperty('fillable');
        $property->setAccessible(true);
        $fillable = $property->getValue($mock);

        $input = Request::only($fillable);

        $model = $class::create($input);
        $model->save();

        return $model;
    }
}
