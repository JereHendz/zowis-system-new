<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\CountryModel;
use App\Models\RolesModel;


class Roles extends ResourceController
{

    protected $format    = 'json';
    public function index()
    {
        $rolesModel = new RolesModel();
        $countryModel = new CountryModel();

        $info["roles"]=$rolesModel->findAll();
        $info["countries"]=$countryModel->findAll();

        return $this->respond($info);
    }
}
