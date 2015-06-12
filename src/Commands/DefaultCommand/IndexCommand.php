<?php

namespace Osedea\LaravelRest\Commands\DefaultCommand;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\Request;
use Osedea\LaravelRest\Commands\Command;

class IndexCommand extends Command implements SelfHandling
{
    public $modelClass;

    /**
     * @param $modelClass
     */
    function __construct($modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * Execute the command.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function handle()
    {
        $class = $this->modelClass;
        $perPage = $this->getPerPageFromModelClass($class);
        $fields = $this->getFieldsFromRequest();

        $query = $class::withRequestSort()->withRequestEmbed();
        $query = $this->addWhereStatements($query, $class);

        // If `count` param is present, return early since we don't want to paginate
        if (Request::get('count')) {
            return [ 'count' => $query->count() ];
        }

        return $query->paginate($perPage, $fields);
    }
}
