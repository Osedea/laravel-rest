<?php

namespace Osedea\LaravelRest\Commands;

use Osedea\LaravelRest\Exceptions\InvalidFilterException;
use Illuminate\Support\Facades\Request;

abstract class Command
{
    /*
     * List of url parameters that cannot be used for filtering
     */
    private $keywordParams = [
        'sort',
        'count',
        'embed',
        'page',
        'fields',
        'perPage'
    ];

    /**
     * This method can be overriden to return an array of commands to run before.
     * You need to specify the full command class (with namespace), so it's better to use the stdobject::class notation.
     * This also helps code naviguation in an IDE.
     * It is using the built-in Command Pipeline, more info here: http://laravel.com/docs/5.0/bus#command-pipeline
     *
     * Example: return [Lucy\Commands\ExampleCommand::class];
     *
     * @return array
     */
    public static function beforeCommands()
    {
        return [];
    }

    /**
     * Returns an integer for pagination based on a model's class.
     *
     * @param  string $modelClass A model's class.
     * @return int The number of items per page for the given model.
     */
    protected function getPerPageFromModelClass($modelClass) {
        // We need this empty model to grab perPage and perPageMax parameters
        $model = new $modelClass;

        // If Model::$perPage equals 0, then pagination is disabled
        if ($model->getPerPage() == 0) {
            return $modelClass::all();
        }

        // The number of items per page can be overriden as a query parameter
        $perPage = Request::input('perPage', $model->getPerPage());

        // We make sure the $perPage parameter is not higher than the maximum for this model
        if ($perPage > $model->getPerPageMax()) {
            $perPage = $model->getPerPageMax();
        }

        return $perPage;
    }

    /**
     * This method returns an array of fields parsed from the request. `[*]` is returned by default (all fields).
     * Fields can be written in snake_case or camelCase. They are converted to snake_case automatically.
     *
     * Example: `?fields=column1,column2` is returned as `['column1', 'column2']`
     *
     * @return array
     */
    protected function getFieldsFromRequest($prefix = null)
    {
        if (Request::has('fields')) {
            $prefix = $prefix ? $prefix . '.' : '';

            return array_map(function($field) use ($prefix) {
                return $prefix . snake_case($field);
            }, explode(',', Request::get('fields')));
        }

        return ['*'];
    }

    /**
     * Loop through Request params, creating `where` statements.
     *
     * @param $query
     * @return mixed
     */
    protected function addWhereStatements($query, $class)
    {
        $params = Request::all();

        foreach ($params as $key => $value) {
            if (!in_array($key, $this->keywordParams) && !in_array($key, $class::$filterable)) {
                throw new InvalidFilterException;
            }

            if (!in_array($key, $this->keywordParams)) {
                $query->where($key, '=', $value);
            }
        }

        return $query;
    }
}
