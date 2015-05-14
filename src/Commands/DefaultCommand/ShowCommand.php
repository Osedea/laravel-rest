<?php

namespace Osedea\LaravelRest\Commands\DefaultCommand;

use Illuminate\Contracts\Bus\SelfHandling;
use Osedea\LaravelRest\Commands\Command;

class ShowCommand extends Command implements SelfHandling
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
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function handle()
    {
        $class = $this->modelClass;
        $fields = $this->getFieldsFromRequest();

        return $class::withRequestEmbed()->findOrFail($this->id, $fields);
    }
}
