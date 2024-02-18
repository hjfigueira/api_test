<?php

namespace App\Http\Controllers\Core;

use App\Http\Mapper\ApiMapperInterface;
use App\Http\ViewModels\ApiViewModelInterface;
use App\Repositories\Core\BaseRepository;

interface ApiConfigurableController
{
    function getRepository() : BaseRepository;
    function getViewModel() : ApiViewModelInterface;
    function getMapper() : ApiMapperInterface;
}