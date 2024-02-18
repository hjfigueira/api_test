<?php

namespace App\Http\Controllers\Core;

use App\Http\Mapper\ApiMapperInterface;
use App\Http\ViewModels\ApiViewModelInterface;
use App\Repositories\Core\BaseRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;

abstract class ApiController extends BaseController implements ApiConfigurableController
{
    use AuthorizesRequests, ValidatesRequests;

    protected BaseRepository $repository;
    protected ApiViewModelInterface $viewModel;
    protected ApiMapperInterface $mapper;

    protected const DEFAULT_PAGE_SIZE = 10;
    protected const MAX_PAGE_SIZE = 50;

    public function __construct()
    {
        $this->repository   = $this->getRepository();
        $this->viewModel    = $this->getViewModel();
        $this->mapper       = $this->getMapper();
    }

    public function index(Request $request) : JsonResponse
    {
        $pageSettings = $request->query('pagination');

        $perPage    = min(($pageSettings['perPage'] ?? self::DEFAULT_PAGE_SIZE),self::MAX_PAGE_SIZE);
        $page       = $pageSettings['page'] ?? 1;

        $recordsList = $this->repository->findAllPaginated($perPage, $page);
        $parsedRecords = [];

        foreach ($recordsList->items() as $item){
            $parsedRecords[] = $this->viewModel->list($item);
        }

        //@todo this can be converted to a JSONResponseEnvelope
        $parsedData = [
            'page' => $recordsList->currentPage(),
            'perPage' => $recordsList->perPage(),
            'total' => $recordsList->total(),
            'hasNext' => $recordsList->hasMorePages(),
            'data' => $parsedRecords
        ];

        return new JsonResponse($parsedData);
    }

    public function show(Request $request, $id) : JsonResponse
    {
        $record     = $this->repository->findOneById((int)$id);
        $parsedData = $this->viewModel->details($record);
        return new JsonResponse($parsedData);
    }

    public function store(Request $request) : JsonResponse
    {
        $jsonBodyData   = $request->all();
        try{
            $this->validate($request, $this->mapper->storeRules());
        }catch (ValidationException $error){
            return new JsonResponse($error->errors(), $error->status);
        }

        $newRecord      = $this->repository->getModel();
        $mappedModel    = $this->mapper->store($newRecord,$jsonBodyData);
        $newModel       = $this->repository->store($mappedModel);
        $parsedData     = $this->viewModel->details($newModel);
        return new JsonResponse($parsedData);
    }

    public function update(Request $request, int $id) : JsonResponse
    {
        $jsonBodyData   = $request->all();
        try{
            $this->validate($request, $this->mapper->updateRules());
        }catch (ValidationException $error){
            return new JsonResponse($error->errors(), $error->status);
        }

        $existingModel  = $this->repository->findOneById($id);
        $mappedModel    = $this->mapper->update($existingModel,$jsonBodyData);
        $updatedRecord  = $this->repository->update($mappedModel);
        $parsedData     = $this->viewModel->details($updatedRecord);
        return new JsonResponse($parsedData);
    }

    public function destroy(Request $request, int $id) : JsonResponse
    {
        $model = $this->repository->findOneById($id);
        $this->repository->destroy($model);
        return new JsonResponse();
    }
}
