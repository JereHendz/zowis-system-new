<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Roles extends ResourceController
{

    protected $modelName = 'App\Models\RolesModel';
    protected $format    = 'json';
    public function index()
    {
        return $this->respond($this->model->findAll());
    }
}
