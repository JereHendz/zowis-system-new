<?php

namespace App\Models;

use CodeIgniter\Model;

class ProducsModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    =
    [
        'id',
        'productCode',
        'productName',
        'description',
        'stockProduct',
        'stockLimit',
        'percentageProfit',
        'status',
        'whodidit',
        'whoCreated',
        'createDate',
        'updateDate',
        'idSubCategory',
        'productDiscount',
        'barcode',
        'unitSalePriceAvg',
        'unitPurchasePriceAvg'

    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getProductCode()
    {
        $this->select('count(*) productCode');
        $this->table("products");
        $query = $this->get();
        return ($query->getNumRows() > 0) ? $query->getResultArray() : array();
    }

    public function getProductWithImage()
    {
        $this->select('products.id, products.productName, products.description, images_products.link,
         "" barcode, products.unitSalePriceAvg, products.productDiscount',false);
        $this->table("products");
        $this->join("product_details","product_details.idProduct=products.id");
        $this->join("images_products","images_products.idProduct=products.id");
        $this->where("images_products.priority",1);
        $this->where("images_products.visibleCustomer",6);
        $this->groupBy("products.id");
        $query = $this->get();
        return ($query->getNumRows() > 0) ? $query->getResultArray() : array();
    }

    public function getAvgCost($idProduct)
    {

        $sql="SELECT SUM(product_details.unitPurchasePrice*product_details.stock) inventoryValue, products.stockProduct FROM product_details
        JOIN products ON products.id=product_details.idProduct
              where product_details.idProduct=$idProduct";
        $query = $this->db->query($sql); 
        return ($query->getNumRows() > 0) ? $query->getResultArray():array();
    }

    public function getProductsAndBrand()
    {
        $this->select('product_details.idProduct, product_details.idBrand, brands.name');
        $this->from("product_details");
        $this->join("brands","brands.id=product_details.idBrand");
        $this->groupBy("product_details.idProduct, product_details.idBrand");
        $query = $this->get();
        return ($query->getNumRows() > 0) ? $query->getResultArray() : array();
    }

    // public function saveDetailProduct($data)
    // {
    //     $this->table('product_details');
    //     $this->save('product_details',$data);
    //     return ($this->affected_rows() != 1) ? false : true;
    // }
}
