<?php

namespace nailfor\shazam\API\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use nailfor\shazam\API\Factory\ClassReplacer;
use nailfor\shazam\API\Repositories\APIRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class ModelController extends Controller
{
    protected static array $requests = [];

    /**
     * Stores the name of the request variable that determines the name of the repository.
     *
     */
    protected string $modelRequest = '';

    /**
     * Contains the model repository.
     */
    protected APIRepositoryInterface $model;

    public function __construct(APIRepositoryInterface $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request): mixed
    {
        $model = $this->getModel($request);

        try {
            $response = $model->getCollection($request);
        } catch (Exception $e) {
            return $this->error(400, $e->getMessage());
        }

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     */
    public function store(Request $request): mixed
    {
        $model = $this->getModel($request);

        $code = 400;
        try {
            $response = $model->store($request);
        } catch (QueryException $e) {
            return $this->error($code, $e->getMessage());
        } catch (Exception $e) {
            if (method_exists($e, 'getCode')) {
                $code = $e->getCode();
            }
            $code = $code ? : 400;

            return $this->error($code, $e->getMessage());
        }

        if ($response === false) {
            return $this->error($code, 'error');
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     */
    public function show(Request $request, mixed $id): mixed
    {
        $model = $this->getModel($request);

        $response = '';

        try {
            $response = $model->getResource($id);
        } catch (NotFoundHttpException $e) {
            $response = $this->pageNotFound();
        } catch (ModelNotFoundException $e) {
            $response = $this->pageNotFound();
        } catch (Exception $e) {
            $response = $this->error(400, $e->getMessage());
        }

        return $response;
    }

    /**
     * Destroy ID from model.
     *
     */
    public function destroy(Request $request, mixed $id): mixed
    {
        $model = $this->getModel($request);

        $result = '';

        try {
            $model->destroy($id);
        } catch (ModelNotFoundException $e) {
            $result = $this->pageNotFound();
        }

        return $result;
    }

    /**
     * Return Request by type (if exists) or null.
     *
     * @param string $type type of request(index, show, store, delete)
     *
     */
    public function getRequests($type = ''): string
    {
        $requests = static::$requests;
        if (!$type) {
            return '';
        }

        $request = request();
        $class = $requests[$type] ?? '';
        if ($type == 'store' && !$request->id) {
            $class = $requests['create'] ?? $class;
        }

        $factory = new ClassReplacer();

        return $factory->handle($request, $class, $this->modelRequest, 'Request');
    }

    /**
     * @inheritDoc
     *
     */
    protected function error(int $code, string $message): mixed
    {
        return response(['error' => $message], $code);
    }

    public function getModel(Request $request): APIRepositoryInterface
    {
        if (!$this->modelRequest) {
            return $this->model;
        }

        $factory = new ClassReplacer();
        $model = $factory->handle($request, get_class($this->model), $this->modelRequest, 'Repository');

        return App::make($model);
    }
}
