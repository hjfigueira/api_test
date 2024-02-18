<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Core\ApiController;
use App\Http\Mapper\ApiMapperInterface;
use App\Http\Mapper\FundMapper;
use App\Http\ViewModels\ApiViewModelInterface;
use App\Http\ViewModels\FundViewModel;
use App\Repositories\Core\BaseRepository;
use App\Repositories\FundRepository;

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

    public function getFilters() : array
    {
        return [
            'year'      => [ 'equal', 'not', 'greater', 'lesser' ],
            'name'      => [ 'equal', 'not'],
            'manager'   => [ 'equal', 'not'],
        ];
    }
}
