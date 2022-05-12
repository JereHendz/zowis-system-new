<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\EmployeeModel;


class Employees extends ResourceController
{
    protected $format    = 'json';

    public function index()
    {
        $userModel = new EmployeeModel();

        return $this->respond($userModel->findAll());
    }
}
