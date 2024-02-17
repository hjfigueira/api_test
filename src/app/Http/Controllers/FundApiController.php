<?php

namespace App\Http\Controllers;

use App\Http\Mapper\ApiMapperInterface;
use App\Http\Mapper\FundMapper;
use App\Http\Repositories\FundRepository;
use App\Http\Repositories\BaseRepository;
use App\Http\ViewModels\ApiViewModelInterface;
use App\Http\ViewModels\FundViewModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FundApiController extends ApiController
{

    //@todo, this can be moved to properties or to the abstract class since there's no need to be public
    public function getRepository(): BaseRepository
    {
        return new FundRepository();
    }

    public function getViewModel(): ApiViewModelInterface
    {
        return new FundViewModel();
    }

    public function getMapper() : ApiMapperInterface
    {
        return new FundMapper();
    }

    public function store(Request $request): JsonResponse
    {
        return parent::store($request);
    }
}
