<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleDetailModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'sale_detail';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idSale','idProduct','quantity','salePrice','discount','total','whodidit','whoCreated','createDate','updateDate'];

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

    public function showSaleDetail($idVenta)
    {
        $sql = "SELECT sd.*, p.*, ip.link
                FROM sale_detail sd LEFT JOIN products p ON sd.idProduct = p.id
                LEFT JOIN (SELECT * FROM images_products WHERE priority = 1) AS ip ON sd.idProduct = ip.idProduct
                WHERE idSale = '$idVenta'";
        $query = $this->db->query($sql);
        return ($query->getNumRows()>0) ? $query->getResultArray():array();
    }
}
