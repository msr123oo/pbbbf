<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_laporan_barang_melting_keluar extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    function id_user(){
        return $this->session->userdata("id_user");
    }
    public function get($tgl = null,$tgl2 = null){
        if ($tgl ==null && $tgl2 ==null) {
            $where = "";
        }else{
            $where = "AND c.tgl>='$tgl' AND  c.tgl<='$tgl2'";
        }
        $sql = "
            SELECT a.*,c.*,b.nama_customer,e.nama_barang,e.satuan FROM tb_barang_melting_keluar a

            LEFT JOIN tb_surat_jalan c ON a.no_surat_jalan = c.no_surat_jalan
            LEFT JOIN tb_customer b ON c.id_customer = b.id_customer
            LEFT JOIN tb_barang_melting_masuk d ON a.no_batch = d.no_batch
            LEFT JOIN tb_barang_melting e ON d.id_barang = e.id_barang

            WHERE c.is_deleted = 0 $where ORDER BY c.tgl ASC";
        return $this->db->query($sql);
    }

    public function data_barang_keluar($no_surat_jalan){
        $sql = "
            SELECT a.*,c.nama_barang,c.satuan,d.nama_suplier FROM tb_barang_melting_keluar a
            LEFT JOIN tb_barang_melting_masuk b ON a.no_batch = b.no_batch
            LEFT JOIN tb_barang_melting c ON b.id_barang = c.id_barang
            LEFT JOIN tb_suplier d ON b.id_suplier = d.id_suplier
            WHERE a.no_surat_jalan = '$no_surat_jalan' AND a.is_deleted = 0 ORDER BY a.created_at DESC";
        return $this->db->query($sql);
    }
    public function add_surat_jalan($data)
    {
        $id_user = $this->id_user();
        $sql = "
        INSERT INTO `tb_surat_jalan`(`no_surat_jalan`, `tgl`, `id_customer`, `note`, `exp`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_deleted`) 
        VALUES ('$data[no_surat_jalan]','$data[tgl]','$data[id_customer]','$data[note]','$data[exp]',NOW(),'$id_user','0000-00-00 00:00:00','','0')
        ";
        return $this->db->query($sql);
    }
    public function add_barang_keluar($data)
    {
        $id_user = $this->id_user();
        $sql = "
        INSERT INTO `tb_barang_melting_keluar`(`no_batch`, `no_surat_jalan`, `qty`, `created_at`, `created_by`, `updated_at`, `updated_by`, `is_deleted`)
        VALUES ('$data[no_batch]','$data[no_surat_jalan]','$data[qty]',NOW(),'$id_user','0000-00-00 00:00:00','','0')
        ";
        return $this->db->query($sql);
    }
    public function update($data)
    {
        $id_user = $this->id_user();
        $sql = "
            UPDATE `tb_barang_melting_masuk` 
            SET `no_batch`='$data[no_batch]',`tgl`='$data[tgl]',`id_barang`='$data[id_barang]',`id_suplier`='$data[id_suplier]',`status`='$data[status]',`qty`='$data[qty]',`updated_at`=NOW(),`updated_by`='$id_user' 
            WHERE `id_barang_masuk`='$data[id_barang_masuk]'
        ";
        return $this->db->query($sql);
        // return $sql;
    }


    public function delete($data)
    {
        $id_user = $this->id_user();
        $sql = "
            UPDATE `tb_barang_melting_masuk` 
            SET `is_deleted`='1',`updated_at`=NOW(),`updated_by`='$id_user' 
            WHERE `id_barang_masuk`='$data[id_barang_masuk]'
        ";
        return $this->db->query($sql);
    }

    public function jml_barang_keluar($data){
        $sql = "
            SELECT sum(qty) tot_barang_melting_keluar FROM `tb_barang_melting_keluar` WHERE no_batch='$data[no_batch]' AND is_deleted = 0";
        return $this->db->query($sql);
    }
    public function jml_barang_keluar2($data){
        $sql = "
            SELECT sum(a.qty) tot_barang_melting_keluar FROM `tb_barang_melting_keluar` a 
            LEFT JOIN tb_barang_melting_masuk b ON a.no_batch = b.no_batch 
            WHERE b.id_barang ='$data[id_barang]' AND a.is_deleted = 0"; 
        return $this->db->query($sql);
    }


}
