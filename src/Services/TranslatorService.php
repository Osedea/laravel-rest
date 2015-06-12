<?php

namespace Osedea\LaravelRest\Services;

use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TranslatorService
{
    /**
     * Returns a class name base on a resource mapping.
     * The mapping comes from a config file (api.php).
     *
     * Example: `users` should return `\App\Models\User`
     *
     * @param string $resource
     * @return string
     * @throws NotFoundHttpException
     */
    public function getClassFromResource($resource)
    {
        // This is the models namespace
        $modelsNamespace = Config::get('api.models_namespace', Config::get('api.app_namespace'));

        // This array contains mapping between resources and Model classes
        $mapping = Config::get('api.mapping');

        if (!is_array($mapping)) {
            throw new RuntimeException('The config api.mapping needs to be an array.');
        }

        if (!isset($mapping[$resource])) {
            throw new NotFoundHttpException;
        }

        return implode('\\', [$modelsNamespace, $mapping[$resource]]);
    }

    /**
     * Returns a command class name based on a resource mapping.
     *
     * Examples:
     *     - getCommandFromResource('users', 'show') returns \App\Commands\UserCommand\ShowCommand
     *     - getCommandFromResource('users', 'index', 'groups') returns \App\Commands\UserCommand\GroupIndexCommand
     *
     * @param string $resource
     * @param string $action
     * @param string|null $relation
     * @return string
     * @throws NotFoundHttpException
     */
    public function getCommandFromResource($resource, $action, $relation = null)
    {
        $namespace = Config::get('api.app_namespace');
        $mapping = Config::get('api.mapping');

        // If the mapping does not exist, we consider this as a 404 error
        if (!isset($mapping[$resource])) {
            throw new NotFoundHttpException('The resource [' . $resource . '] is not mapped to any Model.');
        }

        $allowedActions = ['index', 'store', 'show', 'update', 'destroy'];

        if (!in_array($action, $allowedActions)) {
            throw new InvalidArgumentException('[' . $action . '] is not a valid action.');
        }

        // If we have a $relation parameter, then we generate a command based on it
        if ($relation) {
            if (!isset($mapping[$relation])) {
                throw new NotFoundHttpException('The resource [' . $resource . '] is not mapped to any Model.');
            }

            $command = implode('\\', [
                $namespace,
                'Commands',
                $mapping[$resource] . 'Command',
                ucfirst($mapping[$relation]) . ucfirst($action) . 'Command'
            ]);
        } else {
            $command = implode('\\', [
                $namespace,
                'Commands',
                $mapping[$resource] . 'Command',
                ucfirst($action) . 'Command'
            ]);
        }

        // If no custom command is found, then we use one of the default ones
        if (!class_exists($command)) {
            if ($relation) {
                $command = implode('\\', [
                    'Osedea',
                    'LaravelRest',
                    'Commands',
                    'DefaultCommand',
                    'Relation' . ucfirst($action) . 'Command'
                ]);
            } else {
                $command = implode('\\', [
                    'Osedea',
                    'LaravelRest',
                    'Commands',
                    'DefaultCommand',
                    ucfirst($action) . 'Command'
                ]);
            }
        }

        if (!class_exists($command)) {
            throw new NotFoundHttpException('There is no default command for this action and resource.');
        }

        return $command;
    }
}
