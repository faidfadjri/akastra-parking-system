<?php

namespace App\Controllers;

use App\Config\Enum\ParkingStatus;
use App\Controllers\BaseController;
use App\Models\KapasitasModel;
use App\Models\ParkirModel;

class Summary extends BaseController
{

    protected $parkir;
    protected $kapasitas;

    public function __construct()
    {
        $this->parkir       = new ParkirModel();
        $this->kapasitas    = new KapasitasModel();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $date = $this->request->getVar('date');

        # cek if data is exist or not
        $data = $this->parkir->select('*')->where('DATE(created_at)', $date)->get()->getResultArray();
        if (!$data) { # if data doesn't exist
            session()->setFlashdata('pesan', 'Data parkir tanggal ' . $date . ' belum tersedia');
            return redirect()->to('/');
        }

        $kapasitas        = $this->kapasitas->select('SUM(capacity) as total, SUM(CASE WHEN category = "GR" THEN capacity END) as GR, SUM(CASE WHEN category = "BP" THEN capacity END) as BP, SUM(CASE WHEN category = "AKM" THEN capacity END) as AKM ')->get()->getRowArray();
        $exist            = $this->parkir->select('COUNT(CASE WHEN category != 0 THEN id END) as total ,COUNT(CASE WHEN category = "GR" THEN id END) as GR, COUNT(CASE WHEN category = "BP" THEN id END) as BP,COUNT(CASE WHEN category = "AKM" THEN id END) as AKM')->where('DATE(created_at)', $date)->get()->getRowArray();



        $category         = ['GR', 'BP', 'AKM'];
        foreach ($category as $cat) {
            $status           = $this->parkir->select('status')->where('category', $cat)->where('DATE(created_at)', $date)->groupBy('status')->get()->getResultArray();
            foreach ($status as $index => $row) {
                ${$cat . "Summary"}[$index] = [
                    'status' => $row['status'],
                    'result' => $this->parkir->select('COUNT(id) as result ')->where('category', $cat)->where('DATE(created_at)', $date)->where('status', $row['status'])->get()->getRowArray()['result']
                ];
            }
        }

        $user             = $this->parkir->select('user')->where('created_at', $date)->get()->getFirstRow();
        $user ? $user = $user->user : $user = 'undefined';
        $readyforDelivery = $this->parkir
            ->select('*')
            ->where('status', ParkingStatus::READY_FOR_DELIVERY)
            ->where("(DATE(created_at) = CURDATE() OR DATE(created_at) = CURDATE() - INTERVAL 1 DAY)", null, false)
            ->get()
            ->getResultArray();

        $data = [
            'lokasi'       => '',
            'capacity'     => $kapasitas,
            'exist'        => $exist ? $exist : 0,
            'GRSummary'    => isset($GRSummary) ? $GRSummary : 0,
            'BPSummary'    => isset($BPSummary) ? $BPSummary : 0,
            'AKMSummary'   => isset($AKMSummary) ? $AKMSummary : 0,
            'date'         => $date,
            'lastDate'     => $date,
            'user'         => $user,
            'readyForDeliv' => $readyforDelivery
        ];
        return view('pages/home', $data);
    }
}
