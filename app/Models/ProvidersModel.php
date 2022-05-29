<?php

namespace App\Models;

use CodeIgniter\Model;

class ProvidersModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'providers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idCountry','idMunicipio',
    'comercialName','giro','document','address','email','phoneNumber','img',
    'status','whodidit','whoCreated','createDate','updateDate'];

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

    //lista de proveedores
    public function getProviders(){
        $sql = "SELECT p.*, m.idDepto FROM providers p
                LEFT JOIN municipios m ON p.id = p.idMunicipio";
        $query = $this->db->query($sql);     
        return ($query->getNumRows()>0) ? $query->getResultArray():array();
    }

}
