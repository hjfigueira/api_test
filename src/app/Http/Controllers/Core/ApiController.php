<?php

namespace App\Http\Controllers\Core;

use App\Http\Interfaces\ApiFilterableController;
use App\Http\Interfaces\ApiMapperInterface;
use App\Http\Interfaces\ApiViewModelInterface;
use App\Repositories\Core\BaseRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;

/**
 * @method applyFilters(Request $request, int $perPage, int $page)
 */
abstract class ApiController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected BaseRepository $repository;

    protected ApiViewModelInterface $viewModel;

    protected ApiMapperInterface $mapper;

    protected const int DEFAULT_PAGE_SIZE = 10;
    protected const int MAX_PAGE_SIZE     = 50;


    public function __construct(
        BaseRepository $repository,
        ApiViewModelInterface $viewModel,
        ApiMapperInterface $mapper
    )
    {
        $this->repository = $repository;
        $this->viewModel  = $viewModel;
        $this->mapper     = $mapper;
    }


    public function index(Request $request) : JsonResponse
    {
        $pageSettings = $request->query('pagination');

        $perPage = min(($pageSettings['perPage'] ?? self::DEFAULT_PAGE_SIZE), self::MAX_PAGE_SIZE);
        $page    = ($pageSettings['page'] ?? 1);

        if ($this instanceof ApiFilterableController) {
            $recordsList    = $this->applyFilters($request, $perPage, $page);
        } else {
            $recordsList    = $this->repository->findAllPaginated($perPage, $page);
        }

        $parsedRecords = [];

        foreach ($recordsList->items() as $item) {
            $parsedRecords[] = $this->viewModel->list($item);
        }

        // @todo this can be converted to a JSONResponseEnvelope
        $parsedData = [
            'page'    => $recordsList->currentPage(),
            'perPage' => $recordsList->perPage(),
            'total'   => $recordsList->total(),
            'hasNext' => $recordsList->hasMorePages(),
            'data'    => $parsedRecords,
        ];

        return new JsonResponse($parsedData);
    }


    public function show(Request $request, int $id) : JsonResponse
    {
        try {
            $record     = $this->repository->findOneById($id);
            $parsedData = $this->viewModel->detail($record);
            return new JsonResponse($parsedData);
        } catch (ModelNotFoundException $error) {
            return new JsonResponse(['message' => 'record not found'], 404);
        }
    }


    public function store(Request $request) : JsonResponse
    {
        $jsonBodyData = $request->all();
        try {
            $this->validate($request, $this->mapper->storeRules());
            $newRecord   = $this->repository->getModel();
            $mappedModel = $this->mapper->store($newRecord, $jsonBodyData);
            $newModel    = $this->repository->store($mappedModel);
            $parsedData  = $this->viewModel->detail($newModel);
            return new JsonResponse($parsedData);
        } catch (ValidationException $error) {
            return new JsonResponse($error->errors(), $error->status);
        }
    }


    public function update(Request $request, int $id) : JsonResponse
    {
        $jsonBodyData = $request->all();
        try {
            $this->validate($request, $this->mapper->updateRules());
            $existingModel = $this->repository->findOneById($id);
            $mappedModel   = $this->mapper->update($existingModel, $jsonBodyData);
            $updatedRecord = $this->repository->update($mappedModel);
            $parsedData    = $this->viewModel->detail($updatedRecord);
            return new JsonResponse($parsedData);
        } catch (ValidationException $error) {
            return new JsonResponse($error->errors(), $error->status);
        }
    }


    public function destroy(Request $request, int $id) : JsonResponse
    {
        $model = $this->repository->findOneById($id);
        if ($this->repository->destroy($model)) {
            return new JsonResponse(['message' => 'deleted successfully'], 200);
        };

        return new JsonResponse(['message' => 'error while removing the record'], 500);
    }
}
