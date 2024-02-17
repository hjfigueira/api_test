<?php

namespace App\Http\Controllers;

use App\Http\Mapper\ApiMapperInterface;
use App\Http\Repositories\BaseRepository;
use App\Http\ViewModels\ApiViewModelInterface;

interface ApiConfigurableController
{
    function getRepository() : BaseRepository;
    function getViewModel() : ApiViewModelInterface;
    function getMapper() : ApiMapperInterface;
}
