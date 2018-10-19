<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_TanyaJawab extends CI_Model
{
    public function isPertanyaanAvailable($id_pertanyaan)
    {
        $query = $this->db->where('id_tanya', $id_pertanyaan)->get('pertanyaan');
        if($query->num_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }
    public function getIdPertanyaan()
    {
        return $this->db->where('judul',$this->input->post('judul'))->where('pertanyaan',nl2br($this->input->post('pertanyaan')))->where('id_bahasa',$this->input->post('bahasa'))->where('user_id',$this->input->post('id_user'))->get('pertanyaan')->row();
    }
    public function getBahasa()
    {
        return $this->db->order_by('bahasa', 'asc')->get('bahasa')->result();
    }

    public function InsertPertanyaan()
    {
        $date = date('Y-m-d H:i:s');
        $pertanyaan = nl2br($this->input->post('pertanyaan'));
        $data = array(
            'user_id' => $this->input->post('id_user'),
            'judul' =>
            $this->input->post('judul')
            ,
            'pertanyaan' =>
            $pertanyaan
            ,
            'id_bahasa' =>
            $this->input->post('bahasa'),
            'waktu'=>$date
        );
        return $this->db->insert('pertanyaan', $data);
    }

    public function getPertanyaan($id_pertanyaan)
    {
        return $this->db->join('bahasa', 'bahasa.id_bahasa=pertanyaan.id_bahasa','left')
        ->join('user', 'user.user_id=pertanyaan.user_id','left')
        ->where('id_tanya', $id_pertanyaan)
        ->get('pertanyaan')->row();
    }
    public function getListPertanyaan()
    {
        return $this->db->join('bahasa', 'bahasa.id_bahasa=pertanyaan.id_bahasa','left')
        ->join('user', 'user.user_id=pertanyaan.user_id','left')->get('pertanyaan')->result();
        
    }
    public function updatePertanyaan()
    {
        $tanya=nl2br($this->input->post('pertanyaan'));
        $data = array(
            'judul' =>
            $this->input->post('judul')
            ,
            'pertanyaan' =>
            $tanya
            ,
            'id_bahasa' =>
            $this->input->post('bahasa')
        );
        return $this->db->where('id_tanya',$this->input->post('id_tanya'))->update('pertanyaan', $data);
    }
public function hapusPertanyaan($id_tanya)
{
    return $this->db->where('id_tanya',$id_tanya)->delete('pertanyaan');
}

public function setJawaban($id_tanya,$id_jawaban)
{
    $arr = array('id_jawaban'=>$id_jawaban);
    return $this->db->where('id_tanya',$id_tanya)->update('pertanyaan', $arr);
    
}

public function hapusJawabanSelesai($id_tanya)
{
    $arr = array('id_jawaban'=>null);
    return $this->db->where('id_tanya',$id_tanya)->update('pertanyaan', $arr);
}
    public function getJawaban($id_pertanyaan)
    {
        return $this->db->order_by('waktu','asc')->join('user', 'user.user_id=jawaban.user_id','left')
        ->where('id_tanya', $id_pertanyaan)->get('jawaban');
    }
    public function insertJawaban()
    {
        $date = date('Y-m-d H:i:s');
        $jawaban=nl2br($this->input->post('jawaban'));
        $arr = array('id_tanya' => $this->input->post('id_tanya')
        , 'user_id'=>$this->input->post('user_id'),'jawaban'=>$jawaban,'waktu'=>$date);
        $this->db->insert('jawaban', $arr);
        return $this->db->order_by('id_jawaban','desc')->limit(1)->get('jawaban')->row();
    }

    public function getJawabanById($id_jawaban)
    {
        return $this->db->where('id_jawaban',$id_jawaban)->get('jawaban')->row();
    }
    public function editJawaban($id_jawaban)
    {
        $jawaban=nl2br($this->input->post('jawaban'));
        $arr=array(
            'jawaban'=>$jawaban
        );
        return $this->db->where('id_jawaban',$id_jawaban)->update('jawaban', $arr);
    }

    public function hapusJawaban($id_jawaban)
    {
        return $this->db->where('id_jawaban',$id_jawaban)->delete('jawaban');
        
    }
    public function getSudahTanya()
    {
        $queue = $this->db->where('user_id',$this->input->post('user_id'))->where('id_tanya',$this->input->post('id_tanya'))->get('laporan');
        if($queue->num_rows()>0){
            return true;
        }
        else{
            return false;
        }
    }

    public function insertLapor()
    {
        $arr = array(
            'user_id'=>$this->input->post('user_id'),
            'id_tanya'=>$this->input->post('id_tanya'),
            'id_jenis_laporan'=>$this->input->post('alasan')
        );
        return $this->db->insert('laporan', $arr);
    }

    public function insertLaporJawaban()
    {
        $arr = array(
            'user_id'=>$this->input->post('user_id'),
            'id_jawab'=>$this->input->post('id_jawab'),
            'id_jenis_laporan'=>$this->input->post('alasan')
        );
        return $this->db->insert('lapor_jawaban', $arr);
    }

    public function getJenisLaporan($id)
    {
        return $this->db->where('id_jenis_laporan',$id)->get('jenis_laporan')->row();
        
    }

    public function insertDapatNotif($user,$tanya)
    {
        $arr = array('user_id' => $user,'id_tanya'=>$tanya );
        return $this->db->insert('dapat_notif', $arr);
    }
    public function getUserNotif($tanya,$id_user)
    {
       return $this->db->query("select * from `dapat_notif` where `id_tanya`='".$tanya."' and `nyala`=1 and `user_id` !='".$id_user."'")->result();
    }
    public function checkStatusNotif($user,$tanya)
    {
        $query = $this->db->where('user_id',$user)->where('id_tanya',$tanya)->get('dapat_notif')->row();
        if($query->nyala==1){
            return true;
        }
        else{
            return false;
        }
        
    }
    public function getUserTanyaNotif($user,$tanya)
    {
        if($this->db->where('user_id',$user)->where('id_tanya',$tanya)->get('dapat_notif')->num_rows()>0){
            return true;
        }else{
            return false;
        }
    }
    public function hapusNotif($user,$tanya)
    {
        $arr = array('nyala'=>0);
        return $this->db->where('user_id',$user)->where('id_tanya',$tanya)->update('dapat_notif',$arr);
    }
    
    public function nyalaNotif($user,$tanya)
    {
        $arr = array('nyala'=>1);
        return $this->db->where('user_id',$user)->where('id_tanya',$tanya)->update('dapat_notif',$arr);
    }
}
?>