<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\BrandsModel;

class Brands extends ResourceController
{
    protected $format    = 'json';

    public function index()
    {
        $brands = new BrandsModel();
        $data=array(
            "listBrands"=>$brands->findAll()
        );
        
        return $this->respond($data);
    }

    public function create()
    {

        // Instance of user model 
        $brandModel = new BrandsModel();

        // Get the form of login 
        $data = $this->request->getJSON(true);

        // Add to array brand the createdDate field
        $data['createDate'] = date('Y-m-d H:m:s');

        // Validating  information and save record
        if (!$id = $brandModel->insert($data)) {

            return $this->failValidationErrors($brandModel->errors());
        }

        // Get the employee that has been saved
        $brandInserted = $brandModel->find($id);

        //  Response using response trait of codeigniter 4
        return $this->respondCreated(['message' => 'Marca creada correctamente', 'data' => $brandInserted]);
    }

    public function update($id = null){
        $form=$this->request->getJSON(true);
        $brands = new BrandsModel();

        if(empty($form)){
            return $this->failValidationErrors('Nothing to update');
        }

        if(!$brands->find($id)){
            return $this->failNotFound();
        }

        $form['updateDate']=date('Y-m-d H:m:s');

        if(!$brands->update($id, $form)){
            return $this->failValidationErrors($brands->errors());
        }
        return $this->respondUpdated([
            'message'=>'Updated successfully',
            'data'=>$brands->find($id),
        ]);
    }
}
