<?php

namespace Osedea\LaravelRest\Http\Controllers;

use Illuminate\Bus\Dispatcher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Response;
use InvalidArgumentException;
use Osedea\LaravelRest\Services\TranslatorService;

abstract class Controller extends BaseController
{
    use DispatchesCommands, ValidatesRequests;

    protected $dispatcher;
    protected $translator;

    public function __construct(Dispatcher $dispatcher, TranslatorService $translator)
    {
        $this->dispatcher = $dispatcher;
        $this->translator = $translator;
    }

    /**
     * This method returns json for a successful request.
     * If $data is paginated, the pagination info will be added in headers.
     *
     * @param $data
     * @param int $code
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function success($data, $code = 200, $headers = [])
    {
        if ($data instanceof Paginator && $data instanceof LengthAwarePaginator) {
            $headers['X-Total-Count'] = $data->total();
            $headers['X-Total-Pages'] = $data->lastPage();
            $headers['X-Total-Current'] = $data->currentPage();

            // We only set this if the current page is not the last one
            if ($data->currentPage() < $data->lastPage()) {
                $headers['X-Page-Next'] = $data->currentPage() + 1;
            }

            // We only set this if the current page is not the first one
            if ($data->currentPage() > 1) {
                $headers['X-Page-Previous'] = $data->currentPage() - 1;
            }

            $data = $data->items();
        }

        return Response::json($data, $code, $headers, JSON_PRETTY_PRINT);
    }

    /**
     * This method returns json for an unsuccessful request.
     * The $errors parameter can be used to send back form validation.
     *
     * @param $message
     * @param int $code
     * @param null $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function error($message, $code = 500, $errors = null)
    {
        $data = [
            'message' => $message
        ];

        if (is_array($errors)) {
            $data['errors'] = $errors;
        }

        return Response::json($data, $code, [], JSON_PRETTY_PRINT);
    }

    /**
     * This method is for a successful request that won't be returning a body (like a DELETE).
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ok()
    {
        return new IlluminateResponse('', 204);
    }

    protected function fireEventForResource($resource, $action, $command)
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

        $event = implode('\\', [$namespace, 'Events', $mapping[$resource] . ucfirst($action) . 'Event']);

        // If no custom event is found, then do nothing.
        if (!class_exists($event)) {
            return;
        }

        Event::fire(new $event($command));
    }

    /**
     * This method can be used in a controller before dispatching a command to pipe it.
     * It will check if the command has commands to run in the command pipeline.
     *
     * @param string $command A command class name
     */
    protected function runBeforeCommands($command)
    {
        $beforeCommands = $command::beforeCommands();

        if (!is_array($beforeCommands) && strlen(trim($beforeCommands)) > 0) {
            $beforeCommands = [$beforeCommands];
        }

        $this->dispatcher->pipeThrough($beforeCommands);
    }
}
