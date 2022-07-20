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
        'productDiscount'
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
        $this->select('products.id, products.productName, products.description, images_products.link',false);
        $this->table("products");
        $this->join("product_details","product_details.idProduct=products.id", 'left');
        $this->join("images_products","images_products.idProduct=products.id", 'left');
        $this->groupBy("products.id");
        $query = $this->get();
        return ($query->getNumRows() > 0) ? $query->getResultArray() : array();
    }

    // public function saveDetailProduct($data)
    // {
    //     $this->table('product_details');
    //     $this->save('product_details',$data);
    //     return ($this->affected_rows() != 1) ? false : true;
    // }
    public function searchProduct($busqueda, $tipo_busqueda, $idBranchOffice)
    {
        if($tipo_busqueda == 1){
            $where = " p.barcode = '$busqueda' ";
        }else{
            $where = " p.productName LIKE '%$busqueda%' ";
        }
        $sql = "SELECT SUM(pd.stock) AS stock, ip.link, p.*
                FROM products p LEFT JOIN (SELECT * FROM images_products WHERE priority = 1) AS ip ON p.id = ip.idProduct
                LEFT JOIN product_details pd ON p.id = pd.idProduct
                WHERE $where AND pd.idBranchOffice = '$idBranchOffice'
                GROUP BY pd.idProduct
                LIMIT 10";
        $query = $this->db->query($sql);     
        return ($query->getNumRows()>0) ? $query->getResultArray():array();
    }
}
