<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MunicipiosModel;


class Municipios extends ResourceController
{
    protected $format    = 'json';

    public function index()
    {
        $municipiosModel = new MunicipiosModel();

        return $this->respond($municipiosModel->findAll());
    }
}
