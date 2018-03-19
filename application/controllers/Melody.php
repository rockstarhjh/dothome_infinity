<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Melody extends MY_Controller {

	public function index()
	{
		$contents=$this->_pagination_all(2);
		$category=$this->contents_model->get_category(0);
		$related_category=$this->_related_category($contents);
		$path1='melody';
		$path2='story';
		$sidebar_title='Melody~';
		$data = array('contents' =>$contents ,'category'=>$category,'path1'=>$path1,'path2'=>$path2,'sidebar_title'=>$sidebar_title,'session'=>2,'related_category'=>$related_category);
		$this->_head();
		$this->load->view('contents', array('data'=>$data), FALSE);
		$this->_footer();	
	}
	public function story($value)
	{
		$contents=$this->_pagination_GetByP_ID($value,2);
		$category=$this->contents_model->get_category($value);
		$related_category=$this->_related_category($contents);
		$path1='melody';
		$path2='story';
		$sidebar_title='Melody~';
		$data = array('contents' =>$contents ,'category'=>$category,'path1'=>$path1,'path2'=>$path2,'sidebar_title'=>$sidebar_title,'session'=>2,'related_category'=>$related_category);
		$this->_head();
		$this->load->view('contents', array('data'=>$data), FALSE);
		$this->_footer();		
	}
}

/* End of file Melody.php */
/* Location: ./application/controllers/Melody.php */