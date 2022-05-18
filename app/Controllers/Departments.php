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
        $thisTest=false;
        $thiHas=true;
        if ($thisHas==true) {
            // Don ju mouse
            // this is a test
            $rat=true;
            $see=false;

        }
        $deparmentModel = new DepartmentModel();

        if (!$data=$deparmentModel->getDepartmentsByCountry($id)) {
            return $this->failNotFound();
        }

        return $this->respond($data);
    }
}
