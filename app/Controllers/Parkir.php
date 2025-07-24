<?php

namespace App\Controllers;

use App\Config\Enum\ParkingStatus;
use App\Controllers\BaseController;
use App\Models\KapasitasModel;
use App\Models\ParkirModel;
use Exception;

class Parkir extends BaseController
{
    protected $parkir;
    protected $kapasitas;
    protected array $statuses;

    public function __construct()
    {
        $this->parkir    = new ParkirModel();
        $this->kapasitas = new KapasitasModel();
        $this->statuses = ParkingStatus::all();

        date_default_timezone_set('Asia/Jakarta');
    }

    public function loginWithApi($username, $password)
    {
        $user  = $this->parkir->_getUserByEmail($username);
        if (!$user) {
            return redirect()->to(base_url());
        } else {
            if ($password != $user['password']) {
                return redirect()->to(base_url());
            } else {


                $session = session();
                $user    = [
                    'logged_in' => true,
                    'email'     => $username,
                    'user'      => $username,
                    'role'      => $user['role']
                ];

                $session->set('user', $user);
                return redirect()->to(base_url());
            }
        }
    }

    public function index()
    {
        $date = date('Y-m-d');
        if (isset($_GET['date'])) {
            $date = $_GET['date'];
        }

        $parkirNow        = $this->parkir->select('*')->where('created_at', $date)->get()->getResultArray();
        if (!$parkirNow) {
            $lastDateExist = $this->parkir->select('created_at')->orderBy('created_at', 'DESC')->get()->getFirstRow();
            if ($lastDateExist) {
                $lastDateExist = $lastDateExist->created_at;
                $parkirNow = $this->parkir->select('*')->where('created_at', $lastDateExist)->get()->getResultArray();
                $date      = $lastDateExist;
            }
        }

        $category         = ['GR', 'BP', 'AKM'];
        foreach ($category as $cat) {
            $status           = $this->parkir->select('status')->where('category', $cat)->where('DATE(created_at)', $date)->groupBy('status')->get()->getResultArray();
            ${$cat . "Summary"} = [];
            foreach ($status as $index => $row) {
                ${$cat . "Summary"}[$index] = [
                    'status' => $row['status'],
                    'result' => $this->parkir->select('COUNT(id) as result ')->where('category', $cat)->where('DATE(created_at)', $date)->where('status', $row['status'])->get()->getRowArray()['result']
                ];
            }
        }

        $kapasitas        = $this->kapasitas->select('SUM(capacity) as total, SUM(CASE WHEN category = "GR" THEN capacity END) as GR, SUM(CASE WHEN category = "BP" THEN capacity END) as BP, SUM(CASE WHEN category = "AKM" THEN capacity END) as AKM ')->get()->getRowArray();
        $exist            = $this->parkir->select('COUNT(CASE WHEN category != 0 THEN id END) as total ,COUNT(CASE WHEN category = "GR" THEN id END) as GR, COUNT(CASE WHEN category = "BP" THEN id END) as BP,COUNT(CASE WHEN category = "AKM" THEN id END) as AKM')->where('DATE(created_at)', $date)->get()->getRowArray();

        $user             = $this->parkir->select('user')->where('created_at', $date)->get()->getFirstRow();
        $user ? $user = $user->user : $user = 'undefined';

        $readyforDelivery = $this->parkir
            ->select('*')
            ->where('status', ParkingStatus::READY_FOR_DELIVERY)
            ->where("(DATE(created_at) = CURDATE() OR DATE(created_at) = CURDATE() - INTERVAL 1 DAY)", null, false)
            ->get()
            ->getResultArray();

        $data = [
            'lokasi'        => '',
            'capacity'      => $kapasitas,
            'exist'         => $exist,
            'GRSummary'     => $GRSummary,
            'BPSummary'     => $BPSummary,
            'AKMSummary'    => $AKMSummary,
            'date'          => $date,
            'user'          => $user,
            'readyForDeliv' => $readyforDelivery
        ];
        return view('pages/home', $data);
    }

    public function cari_parkir(array $array, int $position)
    {
        $key = array_search($position, array_column($array, 'position'));
        return $key;
    }

    public function depan($date = null, $seatId = null)
    {
        if (!isset($date)) {
            $date          = date('Y-m-d');
        }

        $parkirGroups  = range('A', 'F');
        $parkir = $this->parkir->_getAllParkirByLocation("DEPAN", $date, $seatId);

        $grupA = array();
        $grupB = array();
        $grupC = array();
        $grupD = array();
        $grupE = array();
        $grupF = array();

        foreach ($parkirGroups as $grup) {
            $keys = array_keys(array_combine(array_keys($parkir), array_column($parkir, 'grup')), $grup);
            foreach ($keys as $data) {
                array_push(${"grup" . $grup}, $parkir[$data]);
            }
        }

        $listModel = $this->parkir->_getListModel();
        $data  = [
            'grupA'             => $grupA,
            'grupB'             => $grupB,
            'grupC'             => $grupC,
            'grupD'             => $grupD,
            'grupE'             => $grupE,
            'grupF'             => $grupF,
            'model'             => $listModel,
            'lokasi'            => 'DEPAN',
            'controller'        => $this,
            'date'              => $date,
            'statuses'          => $this->statuses
        ];
        return view('pages/depan', $data);
    }

    public function stall_bp($date = null, $seatId = null)
    {
        if (!isset($date)) {
            $date          = date('Y-m-d');
        }
        $parkirGroups  = range('I', 'O');
        $parkir = $this->parkir->_getAllParkirByLocation("STALL_BP", $date, $seatId);

        $grupI = array();
        $grupJ = array();
        $grupK = array();
        $grupL = array();
        $grupM = array();
        $grupN = array();
        $grupO = array();

        foreach ($parkirGroups as $grup) {
            $keys = array_keys(array_combine(array_keys($parkir), array_column($parkir, 'grup')), $grup);
            foreach ($keys as $data) {
                array_push(${"grup" . $grup}, $parkir[$data]);
            }
        }

        $listModel = $this->parkir->_getListModel();


        $ovenLeftLabels   = ['Final Check', 'Poles Pemasangan', 'Stall Poles', 'Stall Poles'];
        $ovenRightLabels  = ['Perbaikan Panel', 'Stall Dempul', 'Stall Dempul', 'Stall Backup'];

        $data = [
            'lokasi'            => 'STALL_BP',
            'grupI'             => $grupI,
            'grupJ'             => $grupJ,
            'grupK'             => $grupK,
            'grupL'             => $grupL,
            'grupM'             => $grupM,
            'grupN'             => $grupN,
            'grupO'             => $grupO,
            'model'             => $listModel,
            'controller'        => $this,
            'ovenLeftLabels'    => $ovenLeftLabels,
            'ovenRightLabels'   => $ovenRightLabels,
            'date'              => $date,
            'statuses'          => $this->statuses
        ];
        return view('pages/bp', $data);
    }

    public function stall_gr($date = null, $seatId = null)
    {
        if (!isset($date)) {
            $date          = date('Y-m-d');
        }

        $parkirGroups  = range('G', 'H');
        $parkir = $this->parkir->_getAllParkirByLocation("STALL_GR", $date, $seatId);

        $grupG = array();
        $grupH = array();

        foreach ($parkirGroups as $grup) {
            $keys = array_keys(array_combine(array_keys($parkir), array_column($parkir, 'grup')), $grup);
            foreach ($keys as $data) {
                array_push(${"grup" . $grup}, $parkir[$data]);
            }
        }

        //----- Stall Labels
        $labels = ['CUCI', 'STP', 'STP', 'SBE', 'GR/SBE', 'AC', 'SBE', 'GR', 'SBI/GR', 'GR/SBE', 'GR/SBE', 'GR', 'SBE', 'QS', 'SPR', 'DG'];

        $listModel = $this->parkir->_getListModel();
        $data = [
            'lokasi'            => 'STALL_GR',
            'grupG'             => $grupG,
            'grupH'             => $grupH,
            'labels'            => $labels,
            'controller'        => $this,
            'model'             => $listModel,
            'date'              => $date,
            'statuses'          => $this->statuses
        ];
        return view('pages/gr', $data);
    }

    public function akm($date = null, $seatId = null)
    {
        if (!isset($date)) {
            $date          = date('Y-m-d');
        }
        $parkirGroups  = ['P'];
        $parkir = $this->parkir->_getAllParkirByLocation("AKM", $date, $seatId);

        $grupP = array();
        foreach ($parkirGroups as $grup) {
            $keys = array_keys(array_combine(array_keys($parkir), array_column($parkir, 'grup')), $grup);
            foreach ($keys as $data) {
                array_push(${"grup" . $grup}, $parkir[$data]);
            }
        }

        $listModel = $this->parkir->_getListModel();
        $date      = date('Y-m-d');

        $data = [
            'lokasi'            => 'AKM',
            'model'             => $listModel,
            'date'              => $date,
            'grupP'             => $grupP,
            'controller'        => $this,
            'statuses'          => $this->statuses
        ];
        return view('pages/akm', $data);
    }

    public function login()
    {
        $data = [
            'lokasi' => ''
        ];
        return view('pages/login', $data);
    }

    public function authentication()
    {
        $email = $_POST['email'];
        $pass  = $_POST['password'];

        $user  = $this->parkir->_getUserByEmail($email);
        if (!$user) {
            return json_encode(array(
                'message'  => 'Email tidak ditemukan',
                'code'     => 404
            ));
        } else {
            if ($pass != $user['password']) {
                return json_encode(array(
                    'message'  => 'Password tidak Match',
                    'code'     => 401
                ));
            } else {


                $session = session();
                $user    = [
                    'logged_in' => true,
                    'email'     => $email,
                    'user'      => $email,
                    'role'      => $user['role']
                ];

                $session->set('user', $user);

                return json_encode(array(
                    'message'  => 'Login Success',
                    'code'     => 200
                ));
            }
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }



    //--- update visit 06/09/2022
    public function visit()
    {
        $date      = $_POST['date'];
        $getParkir = $this->parkir->select('*')->where("DATE(created_at)", $date)->get()->getResultArray();
        if (!$getParkir) {
            session()->setFlashdata('pesan', 'Data parkir tanggal ' . $date . ' belum tersedia');
            return redirect()->to('/');
        } else {
            return redirect()->to(base_url() . '/parkir/depan/' . $date);
        }
    }


    //------ Non pages function

    public function get_detail()
    {
        $date = $_POST['date'];
        $date ? $date : $date = date('Y-m-d');
        if ($this->request->isAJAX()) {
            if (isset($_POST['grup']) && isset($_POST['posisi'])) {
                $data   = $this->parkir->_getParkirDetail($_POST['posisi'], $_POST['grup'], $date);
                if ($data) {
                    return json_encode(array(
                        'data'      => $data,
                        'code'      => 200,
                        'message'   => "Berhasil mendapatkan data"
                    ));
                } else {
                    return json_encode(array(
                        'code'      => 404,
                        'message'   => 'Data tidak ditemukan'
                    ));
                }
            }
        }
    }

    public function update_posisi()
    {
        $grup      = '';
        $posisi    = '';
        $newGrup   = '';
        $newPosisi = '';
        $date      = $_POST['date'];

        if (isset($_POST['grup'])) {
            $grup = $_POST['grup'];
        }

        if (isset($_POST['posisi'])) {
            $posisi = $_POST['posisi'];
        }

        if (isset($_POST['newGrup'])) {
            $newGrup = $_POST['newGrup'];
        }

        if (isset($_POST['newPosisi'])) {
            $newPosisi = $_POST['newPosisi'];
        }

        $dataAwal = $this->parkir->select('*')->where('grup', $grup)->where('position', $posisi)->where('created_at', $date)->get()->getRowArray();
        $update   = $this->parkir->set('position', $newPosisi)->set('grup', $newGrup)->where('id', $dataAwal['id'])->where('created_at', $date)->update();
        if ($update) {
            return json_encode(array(
                'model_code' => $dataAwal['model_code'],
                'license_plate' => $dataAwal['license_plate'],
                'category'      => $dataAwal['category']
            ));
        }
    }

    public function tambah_parkir()
    {
        helper(['form']);
        $data  = $_POST['parking'];
        $rules = [
            'parking.grup' => 'required',
            'parking.position' => 'required',
            'parking.jenis_parkir' => 'required',
            'parking.category' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->validator->getErrors();
        }


        $nopol = strtoupper($_POST['license_plate']);
        $date  = date('Y-m-d');

        $result = false;

        $prevPos  = 0;
        $prevGrup = 0;

        $vehicle = $this->parkir->select('*')->where('created_at', $date)->where('license_plate', $nopol)->get()->getRowArray();
        if ($vehicle) {
            $prevPos  = $vehicle['position'];
            $prevGrup = $vehicle['grup'];
        } else {
            $result = $this->addDataIfParkingEmpty($date);
        }
        $data['license_plate'] = $nopol;

        if (isset($_POST['id'])) {
            if ($_POST['id']) {
                $data['id'] = $_POST['id'];
            }
        }

        $save           = $this->parkir->save($data);
        $date           = date('Y-m-d');

        //-- Update user security
        $this->parkir->set('user', session()->get('user')['user'])->where('created_at', $date)->update();
        if ($save) {
            return json_encode(array(
                'code'          => 200,
                'message'       => "Data berhasil di simpan!",
                'data'          => $data,
                'prevPos'       => $prevPos,
                'prevGrup'      => $prevGrup,
                'redirect'      => $result,
                'redirect_url'  => base_url() . '/parkir/' . strtolower(preg_replace('/\s+/', '', $data['lokasi']))
            ));
        } else {
            return json_encode(array(
                'code'      => 500,
                'message'   => "Database Error!"
            ));
        }
    }

    public function update_parkir()
    {
        helper(['form']);
        $data  = $_POST['parking'];
        $rules = [
            'parking.grup' => 'required',
            'parking.position' => 'required',
            'parking.jenis_parkir' => 'required',
            'parking.category' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->validator->getErrors();
        }


        $date = $data['date'];

        $nopol = strtoupper($_POST['license_plate']);
        $vehicle = $this->parkir->select('*')->where('created_at', $date)->where('license_plate', $nopol)->get()->getRowArray();
        if ($vehicle) {
            $prevPos  = $vehicle['position'];
            $prevGrup = $vehicle['grup'];
        } else {
            $this->addDataIfParkingEmpty($date);
        }
        $data['license_plate'] = $nopol;

        if (isset($_POST['id'])) {
            if ($_POST['id']) {
                $data['id'] = $_POST['id'];
            }
        }

        $save = $this->parkir->save($data);
        if ($save) {
            return json_encode(array(
                'code'          => 200,
                'message'       => "Data berhasil di simpan!",
                'data'          => $data,
            ));
        } else {
            return json_encode(array(
                'code'      => 500,
                'message'   => "Database Error!"
            ));
        }
    }

    public function addDataIfParkingEmpty($date)
    {
        $parkirNow        = $this->parkir->select('*')->where('created_at', $date)->where('position !=', '0')->where('grup !=', '0')->get()->getResultArray();
        if (!$parkirNow) {
            $lastDateExist = $this->parkir->select('created_at')->orderBy('created_at', 'DESC')->get()->getFirstRow();
            if (!$lastDateExist) {
                return false;
            } else {
                $lastDateExist = $lastDateExist->created_at;
            }
            $lastData      = $this->parkir->select('*')->where('created_at', $lastDateExist)->get()->getResultArray();

            #---- remove timestamp column
            $lastData = array_map(
                function (array $elem) {
                    unset($elem['created_at']);
                    unset($elem['updated_at']);
                    return $elem;
                },
                $lastData
            );

            #---- try insert batch if fails will retry again
            $retry = false;
            do {
                try {
                    $insertBatch   = $this->parkir->insertBatch($lastData);
                    $insertBatch ? $retry = false : $retry = true;

                    //-- Update user security
                    $this->parkir->set('user', session()->get('user')['user'])->where('created_at', $date)->update();
                } catch (Exception $e) {
                    $retry = true;
                    continue;
                }

                break;
            } while ($retry);

            if (!$retry) return true;
        }

        return false;
    }

    public function delete_parkir()
    {
        if ($this->request->isAJAX()) {
            if (isset($_POST['posisi']) && isset($_POST['grup'])) {
                $posisi = $_POST['posisi'];
                $grup   = $_POST['grup'];
                $date   = date('Y-m-d');

                $delete = $this->parkir->_deleteParkir($posisi, $grup, $date);
                if ($delete) {
                    return json_encode(array(
                        'message' => 'Berhasil di hapus',
                        'code'    => 200
                    ));
                } else {
                    return json_encode(array(
                        'message' => 'Sistem Error',
                        'code'    => 500
                    ));
                }
            }
        }
    }

    public function search_car()
    {
        $request = service('request');
        $keyword = trim($request->getPost('keyword'));

        // Jika keyword kosong, kembalikan result kosong
        if ($keyword === '') {
            return $this->response->setJSON([
                'code'    => 200,
                'message' => 'Keyword kosong',
                'result'  => []
            ]);
        }

        $result = $this->parkir
            ->select('tb_parking.id, tb_parking.lokasi, tb_model_kendaraan.model_code, tb_parking.license_plate, tb_parking.created_at')
            ->join('tb_model_kendaraan', 'tb_parking.model_code = tb_model_kendaraan.model_code')
            ->like('license_plate', $keyword)
            ->orderBy('tb_parking.created_at', 'DESC')
            ->limit(4)
            ->get()
            ->getResultArray();

        if ($result) {
            return $this->response->setJSON([
                'code'    => 200,
                'message' => 'Berhasil Mendapatkan Data Kendaraan',
                'result'  => $result
            ]);
        }

        return $this->response->setJSON([
            'code'    => 404,
            'message' => 'Data Kendaraan tidak ditemukan'
        ]);
    }


    public function get_history()
    {
        $data = $this->parkir->select('*')->groupBy('created_at')->orderBy('created_at', 'desc')->get()->getResultArray();
        if ($data) {
            return json_encode(array(
                'code'  => 200,
                'data'  => $data
            ));
        }
    }
}
