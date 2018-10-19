<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PengaturFlex extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_TanyaJawab');
        
    }
    
    public function FlexTanya($id_pertanyaan)
    {
        $data['pertanyaan'] = $this->M_TanyaJawab->getPertanyaan($id_pertanyaan);
        $data['nama']=$this->input->post('nama');
        $this->load->view('flex_pertanyaan', $data);
    }

    public function FlexLapor()
    {
        $data['pertanyaan'] = $this->M_TanyaJawab->getPertanyaan($this->input->post('id_tanya'));
        $data['alasan'] = $this->M_TanyaJawab->getJenisLaporan($this->input->post('alasan'));
        $data['pelapor'] = $this->input->post('pelapor');
        $this->load->view('flex_lapor', $data);
    }
    public function FlexLaporJawaban()
    {
        $data['pertanyaan'] = $this->M_TanyaJawab->getPertanyaan($this->input->post('id_tanya'));
        $data['jawaban']= $this->M_TanyaJawab->getJawabanById($this->input->post('id_jawab'));
        $data['alasan'] = $this->M_TanyaJawab->getJenisLaporan($this->input->post('alasan'));
        $data['pelapor'] = $this->input->post('pelapor');
        $this->load->view('flex_lapor_jawaban', $data);

    }
    public function BerhasilDikirim($id_pertanyaan)
    {
        $data['pertanyaan'] = $this->M_TanyaJawab->getPertanyaan($id_pertanyaan);
        $this->load->view('flex_pertanyaan_terkirim', $data);
    }
    public function FlexJawab($id_pertanyaan)
    {
        $data['pertanyaan'] = $this->M_TanyaJawab->getPertanyaan($id_pertanyaan);
        $this->load->view('flex_jawaban_masuk', $data);
    }
    public function FlexNotifDimatikan($id_tanya)
    {
        $data['tanya']=$this->M_TanyaJawab->getPertanyaan($id_tanya);
        $this->load->view('flex_notifikasi_mati', $data, FALSE);
    }
    public function FlexNotifDinyalakan($id_tanya)
    {
        $data['tanya']=$this->M_TanyaJawab->getPertanyaan($id_tanya);
        $this->load->view('flex_notifikasi_hidup', $data, FALSE);
    }

    public function imageMap()
    {
        $this->load->view('imgmap_forum');   
    }

    public function FlexLaporThreadHapus()
    {
        $data['judul'] = $this->input->post('judul');
        $data['alasan'] = $this->M_TanyaJawab->getJenisLaporan($this->input->post('alasan'));
        $this->load->view('flex_lapor_thread_hapus', $data);
        
    }
}

/* End of file PengaturFlex.php */
?>