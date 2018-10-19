<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tanyajawab extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_TanyaJawab');
        $this->load->model('M_User');
        
    }

    public function index()
    {
        $data['pertanyaan']=$this->M_TanyaJawab->getListPertanyaan();
        $this->load->view('index_forum',$data);
        
    }
    public function Tanya()
    {
        if($this->input->get('id_tanya')){
            $id_pertanyaan = $this->input->get('id_tanya');
            $data['pertanyaan'] = $this->M_TanyaJawab->getPertanyaan($id_pertanyaan);
            $data['id_tanya']=$id_pertanyaan;
        }
        $data['title'] = "Tanya";
        $data['bahasa'] = $this->M_TanyaJawab->getBahasa();
        $this->load->view('tanya', $data);
    }

    public function PageTanya()
    {
        $id_pertanyaan = $this->input->get('id_tanya');
        if(!$this->M_TanyaJawab->isPertanyaanAvailable($id_pertanyaan)){
            $this->load->view('not_found');
            return;
        }
        else{
            $data['pertanyaan'] = $this->M_TanyaJawab->getPertanyaan($id_pertanyaan);
            $data['banyakpenjawab'] = $this->M_TanyaJawab->getJawaban($id_pertanyaan)->num_rows();
            $data['jawaban'] = $this->M_TanyaJawab->getJawaban($id_pertanyaan)->result();
            $data['id_tanya'] = $id_pertanyaan;
            $this->load->view('pagetanyajawab', $data);
        }
    }

    public function SudahTanya()
    {
        $arr = array('sudah_tanya' => $this->M_TanyaJawab->getSudahTanya());
        echo json_encode($arr);
    }
    
    public function getJawaban()
    {
        $jawaban = $this->M_TanyaJawab->getJawabanById($this->input->post('id_jawaban'));
        echo json_encode($jawaban);
    }
    public function editJawaban()
    {
        $this->M_TanyaJawab->editJawaban($this->input->post('id_jawaban'));
    }

    public function setPenyelesaian()
    {
        $this->M_TanyaJawab->setJawaban($this->input->post('id_tanya'),$this->input->post('id_jawaban'));
    }

    public function hapusPenyelesaian()
    {
        $this->M_TanyaJawab->hapusJawabanSelesai($this->input->post('id_tanya'));
    }
    public function getStatusNotif()
    {
        $id_tanya = $this->input->post('id_tanya');
        $id_user = $this->input->post('id_user');
        if($this->M_TanyaJawab->getUserTanyaNotif($id_user,$id_tanya)){
            if($this->M_TanyaJawab->checkStatusNotif($id_user,$id_tanya)){
                $arr = array("action"=>"nyala");
                echo json_encode($arr);
                return;
            }
            else{
                $arr = array("action"=>"mati");
                echo json_encode($arr);
                return;
            }
        }else{
            $arr = array("action"=>"mati");
            echo json_encode($arr);
            return;
        }
    }
    public function setNotif()
    {
        $id_tanya = $this->input->post('id_tanya');
        $id_user = $this->input->post('id_user');
        if($this->M_TanyaJawab->getUserTanyaNotif($id_user,$id_tanya)){
            if($this->M_TanyaJawab->checkStatusNotif($id_user,$id_tanya)){
                $this->M_TanyaJawab->hapusNotif($id_user,$id_tanya);
                $arr = array("action"=>"hapus");
                echo json_encode($arr);
                return;
            }
            else{
                $this->M_TanyaJawab->nyalaNotif($id_user,$id_tanya);
                $arr = array("action"=>"nyala");
                echo json_encode($arr);
                return;
            }
        }else{
            $this->M_TanyaJawab->insertDapatNotif($id_user,$id_tanya);
            $arr = array("action"=>"nyala");
            echo json_encode($arr);
            return;
        }
        
    }
    public function hapusJawaban()
    {
        $this->M_TanyaJawab->hapusJawaban($this->input->post('id_jawab'));
    }
}

/* End of file Controllername.php */
?>