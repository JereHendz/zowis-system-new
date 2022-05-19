<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\EmployeeModel;
use App\Models\RolesModel;
use App\Models\CountryModel;
use App\Models\DepartmentModel;
use App\Models\MunicipiosModel;




class Employees extends ResourceController
{
    protected $format    = 'json';

    public function index()
    {
        $employeeModel = new EmployeeModel();
        $roles = new RolesModel();
        $countries= new CountryModel();
        $department= new DepartmentModel();
        $municipios= new MunicipiosModel();

        $data = array(
            'employees' => $employeeModel->getEmplooyees(),
            'roles' => $roles->findAll(),
            'countries' => $countries->findAll(),
            'department' => $department->findAll(),
            'municipios' => $municipios->findAll(),
        );

        return $this->respond($data);
    }

    public function create()
    {

        // Instance of user model 
        $employeeModel = new employeeModel();

        // Get the form of login 
        $form = $this->request->getJSON(true);

        // Create employee array, set up default information
        $data = [
            'idRol' => $form['idRol'],
            'idCountry'    => $form['idCountry'],
            'idMunicipio'    => $form['idMunicipio'],
            'firstName'  => $form['firstName'],
            'lastName'  => $form['lastName'],
            'dui'  => $form['dui'],
            'address'  => $form['address'],
            'email'  => $form['email'],
            'phoneNumber'  => $form['phoneNumber'],
            'whoCreated'  => $form['whoCreated'],
            'createDate' => date('Y-m-d H:m:s'),
        ];

        // Validating  information and save record
        if (!$id = $employeeModel->insert($data)) {

            return $this->failValidationErrors($employeeModel->errors());
        }

        // Get the employee that has been saved
        $employeeCreated = $employeeModel->find($id);

        //  Response using response trait of codeigniter 4
        return $this->respondCreated(['message' => 'Empleado creado correctamente', 'data' => $employeeCreated]);
    }
}
