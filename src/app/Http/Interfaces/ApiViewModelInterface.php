<?php

namespace App\Http\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface ApiViewModelInterface
{

    /**
     * Method used to parse the data return of a single record, often used to display more data than a list
     *
     * @param Model $record
     * @return array mapped return to be transformed into JSON
     */
    public function detail(Model $record) : array;


    /**
     * Method used to parse a single record, in a list context. To better use, use eager loading for relationships
     *
     * @param Model $record Record source of data
     * @return array mapped return to be transformed into JSON
     */
    public function list(Model $record) : array;
}
