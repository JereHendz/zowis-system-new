<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\CountryModel;

class Country extends ResourceController
{
    protected $format    = 'json';

    public function index()
    {
        $countryModel = new CountryModel();

        return $this->respond($countryModel->findAll());
    }
}
