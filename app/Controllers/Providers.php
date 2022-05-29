<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProvidersModel;
use App\Models\CountryModel;
use App\Models\DepartmentModel;
use App\Models\MunicipiosModel;


class Providers extends ResourceController
{
    protected $format = 'json';

    public function index(){
        $providersModel = new ProvidersModel();
        $data = array(
            'providers' => $providersModel->getProviders()
        );

        return $this->respond($data);
    }

    public function providersComplete(){
        $providersModel = new ProvidersModel();
        $countries= new CountryModel();
        $department= new DepartmentModel();
        $municipios= new MunicipiosModel();

        $data = array(
            'providers' => $providersModel->getProviders(),
            'countries' => $countries->findAll(),
            'department' => $department->findAll(),
            'municipios' => $municipios->findAll(),
        );

        return $this->respond($data);
    }

    public function create(){
        // Instance of user model 
        $providersModel = new ProvidersModel();

        // Get the form of provider 
        $form = $this->request->getJSON(true);

        // Create provider array, set up default information
        $data = [
            'idCountry'    => $form['idCountry'],
            'idMunicipio'  => $form['idMunicipio'],
            'comercialName'=> $form['comercialName'],
            'giro'         => $form['giro'],
            'document'     => $form['document'],
            'address'      => $form['address'],
            'email'        => $form['email'],
            'phoneNumber'  => $form['phoneNumber'],
            'whoCreated'   => $form['whoCreated'],
            'createDate'   => date('Y-m-d H:m:s'),
        ];

        // Validating  information and save record
        if (!$id = $providersModel->insert($data)) {

            return $this->failValidationErrors($providersModel->errors());
        }

        // Get the provider that has been saved
        $providerCreated = $providersModel->find($id);

        //  Response using response trait of codeigniter 4
        return $this->respondCreated(['message' => 'Proveedor creado correctamente', 'data' => $providerCreated]);
    }

    public function update($id = null){
        $providersModel = new ProvidersModel();
        $form=$this->request->getJSON(true);

        if(empty($form)){
            return $this->failValidationErrors('Nothing to update');
        }

        if(!$providersModel->find($id)){
            return $this->failNotFound();
        }

        if(!$providersModel->update($id, $form)){
            return $this->failValidationErrors($providersModel->errors());
        }

        return $this->respondUpdated([
            'message'=>'Updated successfully',
            'data'=>$providersModel->find($id),
        ]);
    }

    public function show($id = null){
        $providersModel = new ProvidersModel();
        $data = array(
            'provider' => $providersModel->fin($id)
        );

        return $this->respond($data);
    }

}
