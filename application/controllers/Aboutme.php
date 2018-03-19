<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aboutme extends MY_Controller {

	public function index()
	{
		//페이지네이션 함수 호출,인덱스 1은 데이터베이스 테이블 aboutme로 구분을 위해 인자 전달
		$contents=$this->_pagination_all(1);
		//인자없는 index페이지의 경우 0을 인자로 줘서 최상위 카테고리 가져오기
		$category=$this->contents_model->get_category(0);
		//컨텐츠별 관련 카테고리 보여주기
		$related_category=$this->_related_category($contents);
		//path1 path2는 비슷한 코드의 컨트롤러에서 어디서 호출인지 구분하기 위해 설정
		$path1='aboutme';
		$path2='story';
		$sidebar_title='About Me';
		$data = array('contents' =>$contents ,'category'=>$category,'path1'=>$path1,'path2'=>$path2,'sidebar_title'=>$sidebar_title,'session'=>1,'related_category'=>$related_category);
		$this->_head();
		$this->load->view('contents', array('data'=>$data), FALSE);
		$this->_footer();		

	}
	public function story($value)
	{
		$contents=$this->_pagination_GetByP_ID($value,1);
		$category=$this->contents_model->get_category($value);
		$related_category=$this->_related_category($contents);
		$path1='aboutme';
		$path2='story';
		$sidebar_title='About Me';
		$data = array('contents' =>$contents ,'category'=>$category,'path1'=>$path1,'path2'=>$path2,'sidebar_title'=>$sidebar_title,'session'=>1,'related_category'=>$related_category);
		$this->_head();
		$this->load->view('contents', array('data'=>$data), FALSE);
		$this->_footer();		
	}
}

/* End of file Portfolio.php */
/* Location: ./application/controllers/Portfolio.php */