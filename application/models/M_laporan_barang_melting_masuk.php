<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_laporan_barang_melting_masuk extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    function id_user(){
        return $this->session->userdata("id_user");
    }
    public function get($tgl = null,$tgl2 = null){
        if ($tgl ==null && $tgl2 == null) {
            $where = "";
        }else{
            $where = "AND a.tgl>='$tgl' AND  a.tgl<='$tgl2'";
        }
        $sql = "
            SELECT a.*,b.nama_suplier,c.* FROM tb_barang_melting_masuk a
            LEFT JOIN tb_suplier b ON a.id_suplier = b.id_suplier
            LEFT JOIN tb_barang_melting c ON a.id_barang = c.id_barang
            WHERE a.is_deleted = 0 $where ORDER BY a.tgl ASC";
        return $this->db->query($sql);
    }
    public function jml_barang_masuk($data){
        // $kode_user = $this->kode_user();
        $sql = "
            SELECT sum(qty) tot_barang_masuk FROM `tb_barang_melting_masuk`
            WHERE id_barang = '$data[id_barang]' AND  is_deleted = 0";
        return $this->db->query($sql);
    }


}
