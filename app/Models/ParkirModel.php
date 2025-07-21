<?php

namespace App\Models;

use CodeIgniter\Model;

class ParkirModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tb_parking';
    protected $allowedFields    = ['grup', 'position', 'others', 'jenis_parkir', 'model_code', 'license_plate', 'category', 'status', 'lokasi', 'created_at', 'updated_at', 'user'];
    protected $useTimestamps    = true;

    public function __construct()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
    }

    public function _getListModel()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table("tb_model_kendaraan");

        return $builder
            ->select('*')
            ->orderBy('model', 'ASC')
            ->get()
            ->getResultArray();
    }


    public function _getAllParkirByLocation($location, $date)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        return $builder->select('*')->where('lokasi', $location)->orderBy('grup')->orderBy('position')->where('created_at', $date)->get()->getResultArray();
    }

    public function _getListParkirGrup($grup)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        return $builder->select('*')->where('grup', $grup)->orderBy('grup')->orderBy('position')->get()->getResultArray();
    }

    public function _getParkirDetail($posisi, $grup, $date)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table("tb_model_kendaraan");
        return $builder->select('*')->join('tb_parking', 'tb_parking.model_code = tb_model_kendaraan.model_code', 'LEFT')->where('grup', $grup)->where('position', $posisi)->where('created_at', $date)->get()->getFirstRow();
    }

    public function _deleteParkir($posisi, $grup, $date)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        return $builder->where('position', $posisi)->where('grup', $grup)->where('created_at', $date)->delete();
    }

    public function _getCapacity()
    {
        return $this->query("SELECT (SELECT SUM(capacity) FROM tb_grup_parking WHERE lokasi = 'DEPAN') as parkir_depan, 
        (SELECT SUM(capacity) FROM tb_grup_parking WHERE lokasi = 'STALL_BP') as stall_bp,
        (SELECT SUM(capacity) FROM tb_grup_parking WHERE lokasi = 'STALL_GR') as stall_gr")->getRowArray();
    }
    public function _getParkirExist()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select("SUM(CASE WHEN lokasi = 'DEPAN' THEN id != 0 END) as parkir_depan, SUM(CASE WHEN lokasi = 'STALL_BP' THEN id != 0 END) as stall_bp, SUM(CASE WHEN lokasi = 'STALL_GR' THEN id != 0 END) as stall_gr");
        return $builder->get()->getRowArray();
    }

    public function _getUserByEmail($email)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table("tb_user");
        $builder->select('*')->where('email', $email);
        return $builder->get()->getRowArray();
    }
}
