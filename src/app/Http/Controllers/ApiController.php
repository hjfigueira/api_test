<?php

namespace App\Http\Controllers;

use App\Http\Repositories\Repository;
use App\Http\ViewModels\ApiViewModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class ApiController extends BaseController implements ApiConfigurableController
{
    use AuthorizesRequests, ValidatesRequests;

    private Repository $repository;
    private ApiViewModel $viewModel;


    public function __construct()
    {
        $this->repository = $this->getRepository();
        $this->viewModel = $this->getViewModel();
    }

    public function index(Request $request) : JsonResponse
    {
        $recordsList = $this->repository->findAllPaginated(10);
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

    public function show(Request $request, $id)
    {
        $record = $this->repository->findOneById((int)$id);
        $parsedData = $this->viewModel->details($record);
        return new JsonResponse($parsedData);
    }

    public function store(Request $request)
    {
        $jsonBodyData = $request->all();
        $newRecord = $this->repository->getModel();
    }

    public function update(Request $request, int $id)
    {
        $existingModel = $this->repository->findOneById($id);
        //validate


        $updatedRecord = $this->repository->update($existingModel);
        $parsedData = $this->viewModel->details($updatedRecord);
        return new JsonResponse($parsedData);
    }

    public function destroy(Request $request, int $id){
        $model = $this->repository->findOneById($id);
        $this->repository->destroy($model);
        return new JsonResponse();
    }
}
