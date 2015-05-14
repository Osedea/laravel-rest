<?php

namespace Osedea\LaravelRest\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Illuminate\Http\Request;

/**
 * This is the default controller for api calls.
 * All requests go through here and call the corresponding command handler.
 * Some actions have a default command handler (index, indexRelation, show and destroy).
 *
 * You can override this controller (or just one action) by adding routes with a specified resource name.
 *
 * For example: `Route::get('/pizzas', 'PizzaRestController@index');` won't get here.
 *
 * Class DefaultRestController
 * @package Osedea\LaravelRest\Http\Controllers
 */
class DefaultRestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @param  string $resource
     * @return \Response
     */
    public function index(Request $request, $resource)
    {
        $command = $this->translator->getCommandFromResource($resource, 'index');

        $this->runBeforeCommands($command);

        $data = $this->dispatchFrom($command, $request, [
            'modelClass' => $this->translator->getClassFromResource($resource)
        ]);

        $this->fireEventForResource($resource, 'index', $data);

        return $this->success($data);
    }

    /**
     * Display a list of a resource relation.
     *
     * @param  Request $request
     * @param  string $resource
     * @param  int $id
     * @param  string $relation
     * @return \Response
     */
    public function indexRelation(Request $request, $resource, $id, $relation)
    {
        $command = $this->translator->getCommandFromResource($resource, 'index', $relation);

        $this->runBeforeCommands($command);

        $data = $this->dispatchFrom($command, $request, [
            'modelClass' => $this->translator->getClassFromResource($resource),
            'id' => $id,
            'relation' => $relation,
            'relationClass' => $this->translator->getClassFromResource($relation)
        ]);

        $this->fireEventForResource($resource, 'index', $data);

        return $this->success($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @param  string $resource
     * @return \Response
     */
    public function store(Request $request, $resource)
    {
        $command = $this->translator->getCommandFromResource($resource, 'store');

        $this->runBeforeCommands($command);

        $data = $this->dispatchFrom($command, $request);

        $this->fireEventForResource($resource, 'store', $data);

        return $this->success($data, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request $request
     * @param  string $resource
     * @param  int $id
     * @return \Response
     */
    public function show(Request $request, $resource, $id)
    {
        $command = $this->translator->getCommandFromResource($resource, 'show');

        $this->runBeforeCommands($command);

        $data = $this->dispatchFrom($command, $request, [
            'modelClass' => $this->translator->getClassFromResource($resource),
            'id' => $id
        ]);

        $this->fireEventForResource($resource, 'show', $data);

        return $this->success($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  string $resource
     * @param  int $id
     * @return \Response
     */
    public function update(Request $request, $resource, $id)
    {
        $command = $this->translator->getCommandFromResource($resource, 'update');

        $this->runBeforeCommands($command);

        $data = $this->dispatchFrom($command, $request);

        $this->fireEventForResource($resource, 'update', $data);

        return $this->success($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @param  string $resource
     * @param  int $id
     * @return \Response
     */
    public function destroy(Request $request, $resource, $id)
    {
        $command = $this->translator->getCommandFromResource($resource, 'destroy');

        $this->runBeforeCommands($command);

        $this->dispatchFrom($command, $request, [
            'modelClass' => $this->translator->getClassFromResource($resource),
            'id' => $id
        ]);

        $this->fireEventForResource($resource, 'destroy', $id);

        return $this->ok();
    }

}
