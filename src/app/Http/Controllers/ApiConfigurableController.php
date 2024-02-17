<?php

namespace App\Http\Controllers;

use App\Http\Repositories\Repository;
use App\Http\ViewModels\ApiViewModel;

interface ApiConfigurableController
{
    function getRepository() : Repository;

    function getViewModel() : ApiViewModel;
}
