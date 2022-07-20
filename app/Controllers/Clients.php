<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ClientsModel;


class Clients extends ResourceController
{
    protected $format = 'json';

    public function index(){
        $clientModel = new ClientsModel();
        $data = array(
            'clients' => $clientModel->findAll()
        );

        return $this->respond($data);
    }

    public function create(){
        // Instance of user model 
        $clientModel = new ClientsModel();

        // Get the form of provider 
        $form = $this->request->getJSON(true);

        // Create provider array, set up default information
        $data = [
            'firstName'      => $form['firstName'],
            'lastName'      => $form['lastName'],
            'email'     => $form['email'],
            'document'         => $form['document'],
            'phoneNumber'     => $form['phoneNumber'],
            'idCountry'     => $form['idCountry'],
            'idMunicipio'     => $form['idMunicipio'],
            'address'     => $form['address'],
            'tipo'     => $form['tipo'],
            'whoCreated'    => $form['whoCreated'],
            'createDate'    => date('Y-m-d H:m:s'),
        ];

        // Validating  information and save record
        if (!$id = $clientModel->insert($data)) {
            return $this->failValidationErrors($clientModel->errors());
        }

        // Get the provider that has been saved
        $clientCreated = $clientModel->find($id);

        //  Response using response trait of codeigniter 4
        return $this->respondCreated(['message' => 'Cliente creado correctamente', 'data' => $clientCreated]);
    }

    public function update($id = null){
        $clientModel = new ClientsModel();
        $form=$this->request->getJSON(true);

        if(empty($form)){
            return $this->failValidationErrors('Nothing to update');
        }

        if(!$clientModel->find($id)){
            return $this->failNotFound();
        }

        if(!$clientModel->update($id, $form)){
            return $this->failValidationErrors($clientModel->errors());
        }

        return $this->respondUpdated([
            'message'=>'Updated successfully',
            'data'=>$clientModel->find($id),
        ]);
    }

    public function show($id = null){
        $clientModel = new ClientsModel();
        $data = array(
            'sale' => $clientModel->fin($id)
        );

        return $this->respond($data);
    }

}
