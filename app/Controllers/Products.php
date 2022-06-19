<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProducsModel;
use App\Models\CategoriesModel;
use App\Models\SubCategoriesModel;
use App\Models\BrandsModel;
use App\Models\ProvidersModel;
use App\Models\ImagesModel;
use App\Models\ProductDetailModel;



// use CodeIgniter\Files\File;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

date_default_timezone_set('America/El_Salvador');
Configuration::instance([
    'cloud' => [
        'cloud_name' => 'devjson',
        'api_key' => '128981583667973',
        'api_secret' => 'GIxV8I5f_7wzDq-RYMKwLBbiQWc'
    ],
    'url' => [
        'secure' => true
    ]
]);

class Products extends ResourceController
{
    public function index()
    {
        //Get all products
        $productsModel = new ProducsModel();
        $data = array(
            'listProducts' => $productsModel->findAll()
        );

        return $this->respond($data);
    }

    public function getInformationToCreateProduct()
    {
        $category = new CategoriesModel();
        $subCategory = new SubCategoriesModel();
        $brands = new BrandsModel();
        $provider = new ProvidersModel();


        $data = array(
            "categories" => $category->getCategoriesByStatus(1),
            "subCategories" => $subCategory->getSubCategoriesByStatus(1),
            "brands" => $brands->getBrandsByStatus(1),
            "providers" => $provider->getProvidersByStatus(1),

        );

        return $this->respond($data);
    }

    public function create()
    {

        $productsModel = new ProducsModel();
        $imagesModel = new ImagesModel();
        $productDetailModel = new ProductDetailModel();



        $files = $this->request->getFiles();

        $informationProduct = json_decode($this->request->getPost('informationProduct'), true);
        $whoCreated = 0;
        $idProduct = false;
        if (count($informationProduct) > 0) {
            $whoCreated = $informationProduct['whoCreated'];
            $productCode = $productsModel->getProductCode();
            $productCode = $productCode != false ? $productCode['productCode'] + 1 : 0;
            $arrayProduct = array(
                "productCode" => $productCode,
                "productName" => $informationProduct['productName'],
                "description" => $informationProduct['description'],
                "stockProduct" => $informationProduct['stockProduct'],
                "stockLimit" => floatval($informationProduct['stockLimit']),
                "percentageProfit" => floatval($informationProduct['percentageProfit']),
                "idSubCategory" => intval($informationProduct['idSubCategory']),
                "whoCreated" => $whoCreated,
                "createDate" => date('Y-m-d H:i:s')
            );

            if (!$idProduct = $productsModel->insert($arrayProduct)) {
                return $this->failValidationErrors($productsModel->errors());
            }
        }


        // Detail product
        $productDetail = json_decode($this->request->getPost('detailProduct'), true);


        if (count($productDetail) > 0) {
            $arrayProductDetail = array(
                "barcode" => $productDetail['barcode'],
                "idProvider" => intval($productDetail['idProvider']),
                "idBrand" => intval($productDetail['idBrand']),
                "quantity" => intval($productDetail['quantity']),
                "unitPurchasePrice" => floatval($productDetail['unitPurchasePrice']),
                "unitSalePrice" => floatval($productDetail['unitSalePrice']),
                "idBranchOffice" => intval($productDetail['idBranchOffice']),
                "idWineries" => $productDetail['idWineries'],
                "idFirstLevelLocation" => $productDetail['idFirstLevelLocation'],
                "idSecondLevelLocation" => $productDetail['idSecondLevelLocation'],
                "idThirdLevelLocation" => $productDetail['idThirdLevelLocation'],
                "whoCreated" => $whoCreated,
                "idProduct" => $idProduct,
                "createDate" => date('Y-m-d H:i:s')
            );

            if (!$idProductDetail = $productDetailModel->insert($arrayProductDetail)) {
                return $this->failValidationErrors($productDetailModel->errors());
            }
            $updateProduct = array(
                "stockProduct" => $productDetail['quantity']
            );
            if (!$productsModel->update($idProduct, $updateProduct)) {
                return $this->failValidationErrors($productsModel->errors());
            }
        }

        $uploadPath = "../Uploads/";
        $Fecha = date("YmdHis");
        foreach ($files as $img) {

            if ($img->isValid() && !$img->hasMoved()) {
                $realName = $img->getName();
                $nameCloudinary = explode('.', $realName)[0] . $Fecha;
                $pathName = $img->getRealPath();
                $uploaded = (new UploadApi())->upload($pathName, [
                    'folder' => 'zowis/',
                    'public_id' => $nameCloudinary,
                ]);

                $arrayImages = array(
                    "link" => $uploaded['url'],
                    "whoCreated" => $whoCreated,
                    "idProduct" => $idProduct,
                    "createDate" => date('Y-m-d H:i:s')
                );


                if (!$saveImage = $imagesModel->insert($arrayImages)) {
                    return $this->failValidationErrors($imagesModel->errors());
                }

                //  echo $uploaded['url'];        

                // To upload files with codeigniter
                // $newName = $img->getRandomName();
                // $img->move($uploadPath, $newName);

            }
        }

        return $this->respondCreated(['message' => 'Create Successfully', 'data' => $idProduct]);
    }

    public function getAllProductsWithImages()
    {
        $productModel = new ProducsModel();
        $pr = $productModel->getProductWithImage();
        $products = array();
        foreach ($pr as $key => $value) {
            $products = array(
                "id" => $value['id'],
                "img" => "ecommerce/01.jpg",
                "name" => "Man's Shirt",
                "note" => "Simply dummy text of the printing",
                "discription" => "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.",
                "discountPrice" => "350.00",
                "status" => "none",
                "price" => 100.00,
                "stock" => "In stock",
                "review" => "(250 review)",
                "category" => "Man",
                "colors"=>array(
                    "White",
                    "gray"
                ),
                "size"=> array(
                    "M",
                    "L",
                    "XL"
                ),
                "tags"=> array(
                    "Diesel",
                    "Hudson",
                    "Lee"
                ),
                "variants"=> array(
                    "w"=>array(
                        "color"=> "White",
                        "images"=> "ecommerce/01.jpg"
                    ),
                    "y"=>array(
                        "color"=> "gray",
                        "images"=> "ecommerce/04.jpg"
                    ),
                    "f"=>array(
                        "color"=> "black",
                        "images"=> "ecommerce/02.jpg"
                    ),
                    "e"=>array(
                        "color"=> "pink",
                        "images"=> "ecommerce/03.jpg"
                    )
                )
            );
        }

        return json_encode($products);
        // {
        //     "id": 1,
        //     "img": "ecommerce/01.jpg",
        //     "name":"Man's Shirt",
        //     "note": "Simply dummy text of the printing",
        //     "discription": "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.",
        //     "discountPrice": "350.00",
        //     "status":"none",
        //     "price": 100.00,
        //     "stock": "In stock",
        //     "review": "(250 review)",
        //     "category": "Man",
        //     "colors": [
        //         "White",
        //         "gray"
        //     ],
        //     "size": [
        //         "M",
        //         "L",
        //         "XL"
        //     ],
        //     "tags": [
        //         "Diesel",
        //         "Hudson",
        //         "Lee"
        //     ],
        //     "variants": [
        //         {
        //             "color": "White",
        //             "images": "ecommerce/01.jpg"
        //         },
        //         {
        //             "color": "gray",
        //             "images": "ecommerce/04.jpg"
        //         },
        //         {
        //             "color": "black",
        //             "images": "ecommerce/02.jpg"
        //         },
        //         {
        //             "color": "pink",
        //             "images": "ecommerce/03.jpg"
        //         }
        //     ]
        // },
    }
}
