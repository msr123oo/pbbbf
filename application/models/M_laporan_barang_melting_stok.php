<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_laporan_barang_stok extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    function id_user(){
        return $this->session->userdata("id_user");
    }
    public function jml_barang_masuk($data){
        if ($data['tgl'] ==null) {
            $where = "";
        }else{
            $where = "AND tgl<='$data[tgl]'";
        }
        $sql = "
            SELECT sum(qty) tot_barang_melting_masuk FROM `tb_barang_melting_masuk`
            WHERE id_barang = '$data[id_barang]' AND  is_deleted = 0 $where";
        return $this->db->query($sql);
    }
    public function jml_barang_keluar2($data){
        if ($data['tgl'] ==null) {
            $where = "";
        }else{
            $where = "AND c.tgl<='$data[tgl]'";
        }
        $sql = "
            SELECT sum(a.qty) tot_barang_melting_keluar FROM `tb_barang_keluar` a 
            LEFT JOIN tb_surat_jalan c ON c.no_surat_jalan = a.no_surat_jalan
            LEFT JOIN tb_barang_melting_masuk b ON a.no_batch = b.no_batch 
            WHERE b.id_barang ='$data[id_barang]' AND a.is_deleted = 0 $where"; 
        return $this->db->query($sql);
    }
   


}
