<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {
	//메인페이지, MY컨트롤러 상속
	public function index()
	{
		$data="";
		$this->_head(0);
		$this->load->view('main', $data, FALSE);
		$this->_footer();
		
	}
	// public function portfolio($id)
	// {
	// 	$this->_head();
	// 	echo "portfolio";
	// 	$this->_footer();
	// }
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */