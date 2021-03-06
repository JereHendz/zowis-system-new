<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeeModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'employees';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['idRol','idCountry','idMunicipio',
    'firstName','lastName','dui','address','email','phoneNumber','img',
    'status','whodidit','whoCreated','createDate','updateDate'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'email'        => 'is_unique[employees.email]',
    ];
    protected $validationMessages   = [
        'email'=>[
            'is_unique' => 'Lo sentimos mucho el email ya ha sido registrado, por favor ingrese otro.',
        ],
    ];
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

    public function getEmplooyees()
    {
        # code...
        $sql="SELECT employees.*, municipios.idDepto FROM employees
        LEFT JOIN municipios ON municipios.id=employees.idMunicipio";
        $query = $this->db->query($sql);     
        return ($query->getNumRows()>0) ? $query->getResultArray():array();
    }

}
