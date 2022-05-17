<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DepartmentModel;

class Departments extends ResourceController
{
    protected $format    = 'json';

    public function index()
    {
        $deparmentModel = new DepartmentModel();

        return $this->respond($deparmentModel->findAll());
    }
    public function show($id = null)
    {
        // Instance  Department
        $deparmentModel = new DepartmentModel();

        if (!$data=$deparmentModel->getDepartmentsByCountry($id)) {
            return $this->failNotFound();
        }

        return $this->respond($data);
    }
}
