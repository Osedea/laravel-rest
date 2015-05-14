<?php

namespace Osedea\LaravelRest\Commands\DefaultCommand;

use Illuminate\Contracts\Bus\SelfHandling;
use Osedea\LaravelRest\Commands\Command;

class DestroyCommand extends Command implements SelfHandling
{
    public $modelClass;
    public $id;

    function __construct($modelClass, $id)
    {
        $this->modelClass = $modelClass;
        $this->id = $id;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $class = $this->modelClass;

        $model = $class::findOrFail($this->id);

        $model->delete();
    }
}
