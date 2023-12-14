<?php

namespace nailfor\shazam\API\Http\Controllers;

use Illuminate\Http\Request;
use nailfor\shazam\API\Helpers\Logger;
use nailfor\shazam\API\Repositories\APIRepositoryInterface;

abstract class LoggerController extends ApiController
{
    protected Logger $logger;

    public function __construct(APIRepositoryInterface $model)
    {
        parent::__construct($model);

        $this->logger = new Logger();
    }

    /**
     * @inheritdoc
     */
    public function index(Request $request): mixed
    {
        $response = parent::index($request);
        $this->logger();

        return $response;
    }

    /**
     * @inheritdoc
     */
    public function show(Request $request, mixed $id): mixed
    {
        $response = parent::show($request, $id);
        $this->logger();

        return $response;
    }
}
