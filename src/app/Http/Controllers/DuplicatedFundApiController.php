<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Core\ApiController;
use App\Http\Mapper\ApiMapperInterface;
use App\Http\Mapper\FundMapper;
use App\Http\ViewModels\ApiViewModelInterface;
use App\Http\ViewModels\FundViewModel;
use App\Repositories\Core\BaseRepository;
use App\Repositories\FundRepository;

class DuplicatedFundApiController extends ApiController
{
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
}
