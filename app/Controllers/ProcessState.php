<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

use App\Models\ProcessStateModel;


class ProcessState extends ResourceController
{
    protected $format    = 'json';

    public function index()
    {
        $processModel = new ProcessStateModel();

        return $this->respond($processModel->findAll());
    }

    public function show($id = null)
    {
        $processStateMoldel = new ProcessStateModel();

        if (!$data=$processStateMoldel->getStateByType($id)) {
            return $this->failNotFound(); 
        }

        return $this->respond($data);
    }
}
