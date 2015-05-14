<?php

namespace Osedea\LaravelRest\Commands\DefaultCommand;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Support\Facades\Request;
use Osedea\LaravelRest\Commands\Command;

class RelationIndexCommand extends Command implements SelfHandling
{
    public $modelClass;
    public $id;
    public $relation;
    public $relationClass;

    /**
     * @param $modelClass
     * @param $id
     * @param $relation
     * @param $relationClass
     */
    function __construct($modelClass, $id, $relation, $relationClass)
    {
        $this->modelClass = $modelClass;
        $this->id = $id;
        $this->relation = $relation;
        $this->relationClass = $relationClass;
    }

    /**
     * Execute the command.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function handle()
    {
        $class = $this->modelClass;
        $relation = $this->relation;
        $relationClass = $this->relationClass;

        $model = $class::findOrFail($this->id);

        $perPage = $this->getPerPageFromModelClass($relationClass);
        $fields = $this->getFieldsFromRequest($relation);

        $query = $model->$relation();
        $query = $this->addWhereStatements($query, $class);

        // If `count` param is present, return early since we don't want to sort or embed
        if (Request::get('count')) {
            return [ 'count' => $query->count() ];
        }

        return $query->withRequestSort()->withRequestEmbed()->paginate($perPage, $fields);
    }
}
