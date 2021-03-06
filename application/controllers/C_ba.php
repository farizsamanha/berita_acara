<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Spipu\Html2Pdf\Html2Pdf;

class C_ba extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Model_ba');
		$this->load->library('Dates');			
	}

	public function index(){
		$this->load->view('home_ba');
	}

	public function printpdf($hasil){
		$html2pdf = new Html2Pdf('P', 'A4', 'en');
		$html2pdf->pdf->SetMargins(20, 5, 20);
        $html2pdf->pdf->SetMargins(20, 5, 20);
        $html2pdf->pdf->SetFont('Times', '', 10);
		$html2pdf->pdf->AddPage(); 
		$html2pdf->pdf->WriteHTML($hasil); 
		$html2pdf->pdf->lastPage();
		$html2pdf->output('my.pdf');
	}

	public function postdata(){
		$data = array();
		$data['judul'] = $this->input->post('judul');
		$data['tanggal'] = $this->input->post('tanggal');
		$data['lokasi'] = $this->input->post('lokasi');
		$data['nama'] = $this->input->post('nama');
		$data['nik'] = $this->input->post('nik');
		$data['jabatan'] = $this->input->post('jabatan');
		$data['keterangan'] = $this->input->post('keterangan1');
		$data['nama2'] = $this->input->post('nama2');
		$data['nik2'] = $this->input->post('nik2');
		$data['jabatan2'] = $this->input->post('jabatan2');
		$data['keterangan2'] = $this->input->post('keterangan2');
		$data['kategori'] = $this->input->post('kategori');
		$data['statement'] = $this->input->post('statement');		

	    return $data;
	}

	public function postdata2(){
		$data = $this->postdata();
		
		$insert_id = $this->Model_ba->insert($data['judul'],$data['tanggal'],$data['lokasi'],$data['kategori'],$data['statement']);
	    $this->Model_ba->insert2($insert_id,$data['nik'],$data['nama'],$data['jabatan'], $data['keterangan']);
	    $this->Model_ba->insert3($insert_id,$data['nik2'],$data['nama2'],$data['jabatan2'], $data['keterangan2']); 
	
		$arr_sn_barang = $this->input->post("sn_barang");
	    $arr_tipe_barang = $this->input->post("tipe_barang");
	    $arr_tipe_keterangan = $this->input->post("keterangan_barang");	

	    for($i=0;$i<count($arr_sn_barang);$i++){	    
	   		$data['isi'][] = array(
	   			'id_berita' => $insert_id,
	   			'tipe' => $arr_tipe_barang[$i],
		   		'serial_number' => $arr_sn_barang[$i],
	    		'status' => $arr_tipe_keterangan[$i],	
	    		);    
	    }

	    $this->Model_ba->insert4($data['isi']);

	    return $data;
	}

	public function result(){
		$this->printpdf($this->load->view('cetak2',$this->postdata2(),true));
		
	}
}

