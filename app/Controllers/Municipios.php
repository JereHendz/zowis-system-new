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
    public function show($id = null)
    {
        $municipiosModel = new MunicipiosModel();

        if (!$data=$municipiosModel->getMunicipiosByDepto($id)) {
            return $this->failNotFound();
        }

        return $this->respond($data);
    }
}
