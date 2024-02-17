<?php

namespace App\Http\Controllers;

use App\Http\Repositories\FundRepository;
use App\Http\Repositories\Repository;
use App\Http\ViewModels\ApiViewModel;
use App\Http\ViewModels\FundViewModel;

class FundApiController extends ApiController
{
    //@todo, this can be moved to properties or to the abstract class since there's no need to be public
    public function getRepository(): Repository
    {
        return new FundRepository();
    }

    public function getViewModel(): ApiViewModel
    {
        return new FundViewModel();
    }
}
