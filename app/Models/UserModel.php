<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tb_user';
    protected $allowedFields    = ['email', 'password', 'role'];
    protected $useTimestamps    = true;

    public function GetUserByEmail($email)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select('*')->where('email', $email);
        return $builder->get()->getRowArray();
    }
    
    public function InsertUser($data)
    {

        $queryData = [
            'email'     => $data['username'],
            'password'  => "",
            'role'      => $data['role_name'],
        ];

        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        return $builder->insert($queryData);
    }
}