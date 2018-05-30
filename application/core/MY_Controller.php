<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		
	}
	function _head($id='',$title='')
	{
		$maintitle='락스타-개인블로그';
		$maindesc='코딩,언어,취미,창업,개인사에대한 블로그입니다.';
		$mainsubject='락스타의 코딩,언어,취미,창업,개인사 기록';
		$mainkeyword='황선율, 코딩, 창업, 스타트업, 코드이그나이터,  육아일기, 육아, 락스타';
		$title1='락스타의 정리공간';
		$desc1='코딩(웹/앱), 특히 php, javascript, java와 같은 언어에 대한 개인적인 학습내용 및 여러상용 프로그램 UG-NX, Unity, Adobe 프로그램 등의 사용법, 영어, 일본어에 대한 회화내용, 창업 아이디어, 취미, 음악, 악기(기타) 등과 같은 관심분야에 대해 총 정리한 페이지 입니다.';
		$subject1='코딩, 프로그램, 영어, 일본어, 게임, 창업에 대한 락스타의 정리내용입니다.';
		$keyword1='코딩, 코드이그나이터, 프렌즈, 라이브아카데미, 생활일본어';
		$title2='황선율, melody';
		$desc2='황선율, 선율이의 사진&동영상 및 육아일기 기록';
		$subject2='선율이(melody)의 앨범 및 육아일기 기록';
		$keyword2='황선율, 선율이, melody, 선율이 육아일기, 선율이 사진,선율이 동영상';
		if($id==0){
			$meta_title=$maintitle;
			$meta_description=$maindesc;
			$meta_subject=$mainsubject;
			$meta_keyword=$mainkeyword;
		}else if($id==1){
			if($title){
				$meta_title=$title;
			}else{
				$meta_title=$title1;
			}			
			$meta_description=$desc1;
			$meta_subject=$subject1;
			$meta_keyword=$keyword1;
		}else if($id==2){
			$meta_title=$title2;
			$meta_description=$desc2;
			$meta_subject=$subject2;
			$meta_keyword=$keyword2;
		}else{
			$meta_title=$maintitle;
			$meta_description=$maindesc;
			$meta_subject=$mainsubject;
			$meta_keyword=$mainkeyword;
		}
		$data=array('meta_title'=>$meta_title,'meta_description'=>$meta_description,'meta_subject'=>$meta_subject,'meta_keyword'=>$meta_keyword);
		$this->load->view('head', array('data'=>$data), false);
		$this->load->view('menu');
	}
	function _footer($id="")
	{
		$this->load->view('footer');
	}
	function _host_auth($value='')
	{
		//관리자 로그인이 되어있지 않다면 로그인 요청 후 관리자로그인 페이지로 리다이렉션(아래 if문에서 master_login값이 false라면 자바스크립트로 confirm으로 요청하여 로그인하겠다면 master 뷰페이지로 리다이렉션 시키는 방법이 안됨)
		// /n을 php에서 쓸경우 //n이라 해야함
			$cur_url='/'.uri_string();//함수호출 uri 값 리턴
			$returnURL=rawurlencode(site_url($cur_url));//인코드 후 URL값 리턴
			$prevPage = $_SERVER["HTTP_REFERER"];
			$msg="관리자만 접근할수 있습니다.\\n로그인 하시겠습니까?";
			echo "<script>				
					var r;
					r=confirm('$msg');
					if(r==true){
						window.location.href='/auth/master?returnURL=".$returnURL."';
					}
					else{
						window.location.href='$prevPage';
					}
				
			</script>";//confirm 후 리다이렉션
	}
	function _related_category($contents)
	{
		if($contents){
			foreach ($contents as $key => $value) {
				$id[$key]=$value->p_id;
			}
			$related_category=$this->contents_model->get_related_category($id);
		}else{
			$related_category=null;
		}
		return $related_category;
	}
	function _pagination_all($value)
	{
		if($value==1){
			$path='aboutme';
		}else if($value==2){
			$path='melody';
		}else{
			$path='plan';
		}
		$this->load->model('contents_model');
		$this->load->library('pagination');
		//가져올 관련글 전체 열수 조회
		$count=$this->contents_model->recent_contents_count($value);
		$config['base_url'] = base_url().$path.'/index/';
		$config['total_rows'] = $count;
		$config['per_page'] = 5;
		$config['uri_segment'] = 3;
		$config['num_links'] = 2;
		$config['num_tag_open']  = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open']  = '<li class="page-item"><a class="page-link">';
		$config['cur_tag_close'] = '</a></li>';
		$config['attributes'] = array('class' => 'page-link');
		$this->pagination->initialize($config);
		//컨텐츠 조회, limit, offset 설정
		$contents=$this->contents_model->recent_contents($value,$config['per_page'],$this->uri->segment(3));
		return $contents;
	}
	function _pagination_GetByP_ID($value,$index)
	{
		if($index==1){
			$path='aboutme/story/';
		}else if($index==2){
			$path='melody/story/';
		}else{
			$path='plan/diary/';
		}
		$this->load->model('contents_model');
		$this->load->library('pagination');
		$count=$this->contents_model->get_contents_count($value,$index);
		$config['base_url'] = base_url().$path.$value;
		$config['total_rows'] = $count;
		$config['per_page'] = 5;
		$config['uri_segment'] = 4;
		$config['num_links'] = 2;
		$config['num_tag_open']  = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open']  = '<li class="page-item"><a class="page-link">';
		$config['cur_tag_close'] = '</a></li>';
		$config['attributes'] = array('class' => 'page-link');
		$this->pagination->initialize($config);
		$contents=$this->contents_model->get_contents($value,$index,$config['per_page'],$this->uri->segment(4));
		return $contents;
	}

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */