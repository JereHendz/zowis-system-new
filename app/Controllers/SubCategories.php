<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\SubCategoriesModel;

class SubCategories extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        //Get all sub_categories
        $subCategoriesModel = new SubCategoriesModel();       

        $data = array(
            'sub_categories' => $subCategoriesModel->findAll(),            
        );

        return $this->respond($data);
    }

    public function show($id = null)
    {
        //Get a specify sub_category
        $subCategoriesModel = new SubCategoriesModel();

        $data = array(
            'sub_category' => $subCategoriesModel->find($id),
        );

        return $this->respond($data);
    }

    public function create()
    {
        $subCategoriesModel = new SubCategoriesModel();

        // Get the form of sub_category
        $form = $this->request->getJSON(true);

        // Create user array, set up default information
        $data = [
            'idCategory' => $form['idCategory'],
            'name' => $form['name'],
            'description' => $form['description'],
            'whoCreated' => $form['whoCreated'],
            'createDate' => date('Y-m-d H:m:s'),
        ];

        // Validating  information and save record
        if (!$id = $subCategoriesModel->insert($data)) {
            return $this->failValidationErrors($subCategoriesModel->errors());
        }

        // Response using response trait of codeigniter 4
        return $this->respondCreated(['message' => 'Subcategoría creada correctamente', 'data' => $data]);
    }

    public function update($id = null)
    {
        $subCategoriesModel = new SubCategoriesModel();
        $form = $this->request->getJSON(true);

        if (empty($form)) {
            return $this->failValidationErrors('Nothing to update');
        }

        if (!$subCategoriesModel->find($id)) {
            return $this->failNotFound();
        }

        if (!$subCategoriesModel->update($id, $form)) {
            return $this->failValidationErrors($subCategoriesModel->errors());
        }

        return $this->respondUpdated([
            'message' => 'Updated successfully',
            'data' => $subCategoriesModel->find($id),
        ]);
    }

    public function getSubCategoriesByIdCategory($id = null)
    {
        //Get a specify sub_category
        $subCategoriesModel = new SubCategoriesModel();

        $data = array(
            'sub_category' => $subCategoriesModel->getSubCategoriesByIdCategory($id),
        );

        return $this->respond($data);
    }
}
