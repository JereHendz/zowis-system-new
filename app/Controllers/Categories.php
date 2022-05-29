<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\CategoriesModel;

class Categories extends ResourceController
{
    protected $format = 'json';
    
    public function index()
    {
        //Get all categories
        $categoriesModel = new CategoriesModel();

        $data = array(
            'categories' => $categoriesModel->findAll()
        );

        return $this->respond($data);
    }

    public function show($id = null){
        //Get a specify category
        $categoriesModel = new CategoriesModel();

        $data = array(
            'category' => $categoriesModel->find($id),
        );

        return $this->respond($data);
    }

    public function create(){
        $categoriesModel = new CategoriesModel();

        // Get the form of category
        $form = $this->request->getJSON(true);

        // Create user array, set up default information
        $data = [
            'name' => $form['name'],
            'description' => $form['description'],
            'whoCreated' => $form['whoCreated'],
            'createDate' => date('Y-m-d H:m:s'),
        ];  
                
        // Validating  information and save record
        if(!$id = $categoriesModel->insert($data)){
            return $this->failValidationErrors($categoriesModel->errors());
        }

        // Response using response trait of codeigniter 4
        return $this->respondCreated(['message'=>'Categoría creada correctamente', 'data'=>$data]);
    }

    public function update($id = null){
        $categoriesModel = new CategoriesModel();
        $form=$this->request->getJSON(true);
        
        if(empty($form)){
            return $this->failValidationErrors('Nothing to update');
        }

        if(!$categoriesModel->find($id)){
            return $this->failNotFound();
        }

        if(!$categoriesModel->update($id, $form)){
            return $this->failValidationErrors($categoriesModel->errors());
        }

        return $this->respondUpdated([
            'message'=>'Updated successfully',
            'data'=>$categoriesModel->find($id),
        ]);
    }
}
