<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProducsModel;
use App\Models\CategoriesModel;
use App\Models\SubCategoriesModel;
use App\Models\BrandsModel;
use App\Models\ProvidersModel;




class Products extends ResourceController
{
    public function index()
    {
        //Get all products
        $productsModel = new ProducsModel();
        $data = array(
            'listProducts' => $productsModel->findAll(),
        );

        return $this->respond($data);        
    }

    public function getInformationToCreateProduct(){
        $category= new CategoriesModel();
        $subCategory= new SubCategoriesModel();
        $brands = new BrandsModel();
        $provider = new ProvidersModel();


        $data=array(
            "categories" =>$category->getCategoriesByStatus(1),
            "subCategories" =>$subCategory->getSubCategoriesByStatus(1),
            "brands" =>$brands->getBrandsByStatus(1),
            "providers" =>$provider->getProvidersByStatus(1),

        );
        
       return $this->respond($data);
    }
}
