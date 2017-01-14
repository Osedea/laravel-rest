<?php

namespace Osedea\LaravelRest\Commands\DefaultCommand;

use Request;
use ReflectionClass;
use Illuminate\Contracts\Bus\SelfHandling;
use Osedea\LaravelRest\Commands\Command;

class UpdateCommand extends Command implements SelfHandling
{
    public $modelClass;
    public $id;

    public function __construct($modelClass, $id)
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
        $model = $class::findOrFail($this->id);

        $reflection = new ReflectionClass($class);
        $property = $reflection->getProperty('fillable');
        $property->setAccessible(true);
        $fillable = $property->getValue($model);

        $input = $this->removeNullFields(Request::only($fillable));

        return $model->update($input);
    }

    /**
     * Removes the null fields of the given array but does not remove the empty
     * ones. This is useful to clean the request input data so the update
     * process can do partial updates without issues.
     *
     * @param array $fields
     * @return array
     */
    public function removeNullFields(array $fields)
    {
        $cleanFields = [];

        foreach ($fields as $field => $value) {
            if ($value === null) {
                continue;
            }

            $cleanFields[$field] = $value;
        }

        return $cleanFields;
    }
}
