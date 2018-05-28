<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plan extends MY_Controller {

	public function index()
	{
		if(!$this->session->userdata('master_login')){
			$this->_host_auth();//일기나 스케줄은 관리자만 확인 가능
		}else{
			$contents=$this->_pagination_all(3);
			$category=$this->contents_model->get_category(0);
			$related_category=$this->_related_category($contents);
			$path1='plan';
			$path2='diary';
			$sidebar_title='Diary';
			$data = array('contents' =>$contents ,'category'=>$category,'path1'=>$path1,'path2'=>$path2,'sidebar_title'=>$sidebar_title,'session'=>3,'related_category'=>$related_category);
			$this->_head(3);
			$this->load->view('contents', array('data'=>$data), FALSE);
			$this->_footer();
		}
	}
	//관리자로그인 필요
	
	public function diary($value='')
	{
		if(!$this->session->userdata('master_login')){
			$this->_host_auth();
		}else{
			$contents=$this->_pagination_GetByP_ID($value,3);
			$category=$this->contents_model->get_category($value);
			$related_category=$this->_related_category($contents);
			$path1='plan';
			$path2='diary';
			$sidebar_title='Diary';
			$data = array('contents' =>$contents ,'category'=>$category,'path1'=>$path1,'path2'=>$path2,'sidebar_title'=>$sidebar_title,'session'=>3,'related_category'=>$related_category);
			$this->_head(3);
			$this->load->view('contents', array('data'=>$data), FALSE);
			$this->_footer();
		}
	}
	public function schedule($value='')
	{
		if(!$this->session->userdata('master_login')){
			$this->_host_auth();
		}else{
			$this->_head(3);
			$this->load->view('schedule');
			$this->_footer();
		}
	}
}
/* End of file Diary.php */
/* Location: ./application/controllers/Diary.php */

	