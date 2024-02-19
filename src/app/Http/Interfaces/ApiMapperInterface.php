<?php

namespace App\Http\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface ApiMapperInterface
{

    /**
     * Method used to fetch the rules for validation when updating a method
     *
     * @return array[] ruleset
     */
    public function updateRules() : array;

    /**
     * Method to extract data from the request
     *
     * @param Model $target model to be filled
     * @param array $data request body data
     * @return Model filled model
     */
    public function update(Model $target, array $data = []):Model;

    /**
     * Method used to get validation rules on the save(store) context
     *
     * @return array[] ruleset
     */
    public function storeRules() : array;


    /**
     * Method to extract data from the request on the save context
     *
     * @param Model $target model to be filled
     * @param array $data request body data
     * @return Model filled model
     */
    public function store(Model $target, array $data = []):Model;
}
