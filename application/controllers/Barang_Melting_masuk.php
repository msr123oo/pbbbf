<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_masuk extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        // check_not_login();
        $this->load->model('M_barang_melting_masuk');
        $this->load->model('M_barang_melting_keluar');
        $this->load->model('M_barang_melting');
        $this->load->model('M_supplier');

    }
    private function convertDate($date)
    {
        return explode('/', $date)[2]."-".explode('/', $date)[1]."-".explode('/', $date)[0];
    }
	public function index()
	{
		// $data['row'] = $this->customer_m->get();
		$data['result'] = $this->M_barang_melting_masuk->get()->result_array();
        for($i=0; $i<count($data['result']);$i++){
            $d['no_batch'] = $data['result'][$i]['no_batch'];
            $jml_barang_keluar = $this->M_barang_melting_keluar->jml_barang_keluar($d)->row_array();
            // $a=0;
            // for($o=0; $o<count($donasi);$o++){
            //     $a+=$donasi[$o]['donasi'];
            // }
            $stok=$data['result'][$i]['qty']-$jml_barang_keluar['tot_barang_melting_keluar'];
            $data['result'][$i]['tot_barang_melting_keluar']=$jml_barang_keluar['tot_barang_melting_keluar'];
            $data['result'][$i]['stok']=$stok;
        }
    }