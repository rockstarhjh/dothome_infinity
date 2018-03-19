<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {
	//로그인 관련 기능들 모은 컨트롤러
	
	public function index()
	{
		$this->_head();
		//$this->load->library('form_validation');
		$this->_footer();
	}
	public function master($value='')
	{
		//관리자 로그인 페이지 호출 및 리턴url 값 전달
		$this->_head();
		$this->load->view('master_login',array('returnURL' =>$this->input->get('returnURL')));//리턴 URL값을 뷰단에 전달
		$this->_footer();
	}
	//contents/add_contents로 변경
	// public function master_mode($value='')
	// {
	// 	$this->_head();
	// 	if(!$this->session->userdata('master_login')){
	// 		$this->session->set_flashdata('message', '로그인을 먼저 해주세요.');
	// 		redirect('/auth/master');
	// 	}else{
	// 		$this->_form();
	// 	}
	// 	$this->_footer();
	// }

	// function authentication($value='')
	// {
	// 	$authentication=$this->config->item('authentication');
		
	// 	if($authentication['id']==$this->input->post('master_id') && $authentication['password']==$this->input->post('master_password')){
	// 		$this->session->set_userdata('master_login', true);
	// 		$this->session->set_flashdata('message', '관리자님 안녕하세요.');
	// 		$returnURL=$this->input->get('returnURL');
	// 		redirect($returnURL?$returnURL:'/contents/add_contents');//간편한 if문 문법임 ? :
	// 	}	
	// 	else{
	// 		$this->session->set_flashdata('message', '관리자만 접근할수 있습니다.');
	// 		redirect('/auth/master','refresh');
	// 	}

	// }
	function authentication($value='')
	{
		$this->load->model('user_model');
		$user=$this->user_model->getByUserId(array(
				'id' =>$this->input->post('id') ,
				'password'=>$this->input->post('password')
			 )
			);//폼을 통해 아이디 및 비번 값 전달
		//같은 아이디의 데이터가 있다면 관리자인지 확인
		if($user){
			$role=$this->user_model->getRoleType($user->id);
			if($user->user_id==$this->input->post('id') && password_verify($this->input->post('password'),$user->password) && $role['status']=='master_login'){
				$this->session->set_userdata($role['status'], true);//세션설정 관리자로
				$this->session->set_userdata('is_login', true);//세션설정 로그인으로
				$this->session->set_userdata('user_id', $user->id);//나중에 등업신청을 위해 아이디값 세션으로 저장
				$this->session->set_userdata('user_Nickname', $user->nickname);
				$this->session->set_flashdata('message', '관리자님 안녕하세요.');
				$returnURL=$this->input->get('returnURL');
				redirect($returnURL?$returnURL:'/contents/add_contents');//간편한 if문 문법임 ? :
			}	
			else{
				$this->session->set_flashdata('message', '관리자정보를 다시 확인해 주세요.');
				redirect('/auth/master','refresh');
			}
		}else{
				$this->session->set_flashdata('message', '관리자만 접근할수 있습니다.');
				redirect('/auth/master','refresh');
		}
		// if($authentication['id']==$this->input->post('master_id') && $authentication['password']==$this->input->post('master_password')){
		// 	$this->session->set_userdata('master_login', true);
		// 	$this->session->set_flashdata('message', '관리자님 안녕하세요.');
		// 	$returnURL=$this->input->get('returnURL');
		// 	redirect($returnURL?$returnURL:'/contents/add_contents');//간편한 if문 문법임 ? :
		// }	
		// else{
		// 	$this->session->set_flashdata('message', '관리자만 접근할수 있습니다.');
		// 	redirect('/auth/master','refresh');
		// }

	}
	function login($value='')
	{
		$cur_url='/'.$this->input->get('returnURL');
		//$cur_url='/'.uri_string();//함수호출 uri 값 리턴
		$returnURL=rawurlencode(site_url($cur_url));//인코드 후 URL값 리턴
		$this->_head();
		$this->load->view('login', array('returnURL' =>$returnURL));
		$this->_footer();
	}
	function check_login($value='')
	{
		$this->load->model('user_model');
		$user=$this->user_model->getByUserId(array(
				'id' =>$this->input->post('id') ,
				'password'=>$this->input->post('password')
			 )
			);
		if($user){
			$role=$this->user_model->getRoleType($user->id);
			if($user->user_id==$this->input->post('id') && password_verify($this->input->post('password'),$user->password)){
				$this->session->set_userdata($role['status'], true);
				$this->session->set_userdata('is_login', true);
				$this->session->set_userdata('user_Nickname', $user->nickname);
				$this->session->set_userdata('user_id', $user->id);
				$this->session->set_flashdata('message', '닉네임 '.$user->nickname.'회원님 안녕하세요. 당신은 '.$role['role']->role_name.'회원 입니다.');
				$returnURL=$this->input->get('returnURL');
				redirect($returnURL?$returnURL:'/');
			}
		}else{
			$this->session->set_flashdata('message', '일치하는 사용자가 없습니다.');
			redirect('/auth/login');
		}
	}
	function logout($value='')
	{
		session_destroy();
		redirect('/','refresh');
	}
	
	function register($value='')
	{
		$this->_head();

		$this->load->library('form_validation');
		$config = array(
	        array(
	                'field' => 'id',
	                'label' => '아이디',
	                'rules' => 'required|min_length[5]|max_length[12]|is_unique[user.user_id]',
	                'errors' => array(
	                        'required' => '아이디를 입력해야합니다.',
	                        'min_length'=>'아이디는 5자 이상이어야 합니다.',
	                        'max_length'=>'아이디는 12자 이하이어야 합니다.',
	                        'is_unique'=>'중복된 아이디가 있습니다.</br> 다른 아이디를 선택해 주세요.'
	                ),
	        ),
	        array(
	                'field' => 'nickname',
	                'label' => '닉네임',
	                'rules' => 'required|min_length[2]|max_length[12]',
	                'errors' => array(
	                        'required' => '닉네임을 입력해야합니다.',
	                ),
	        ),
	        array(
	                'field' => 'password',
	                'label' => '비밀번호',
	                'rules' => 'required|min_length[5]',
	                'errors' => array(
	                        'required' => '비밀번호를 입력해야합니다.',
	                        'min_length'=>'비밀번호는 5자 이상이어야 합니다.',
	                ),
	        ),
	        array(
	                'field' => 're_password',
	                'label' => '비밀번호 확인',
	                'rules' => 'required|matches[password]',
	                'errors' => array(
	                        'required' => '비밀번호를 확인해야합니다.',
	                        'matches'=>'비밀번호와 일치해야 합니다.',
	                ),
	        )
		);
		$this->form_validation->set_rules($config);
		if($this->form_validation->run()){
			$hash=password_hash($this->input->post('password'),PASSWORD_BCRYPT);//비크립트 암호화 설정
			$this->load->model('user_model');
			$data=$this->user_model->add(array(
				'id' =>$this->input->post('id') ,
				'password'=>$hash,
				'nickname'=>$this->input->post('nickname'),	
			));
			$this->session->set_flashdata('message', '회원가입에 성공했습니다.');
			redirect($returnURL?$returnURL:'/');
			//print_r($data);
		}else{
			$this->load->view('register');
		}
				
		$this->_footer();
	}
	public function request($value='')
	{
		$user_id=$this->session->userdata('user_id');
		$this->load->model('user_model');
		$list=$this->user_model->get_request_list_By_UserID($user_id);//로그인 아이디값으로 요청된 자료가 데이터베이스에 있는지 확인
		if($list){
			$this->session->set_flashdata('message', '이미 승인요청 했습니다. 관리자가 승인할때까지 기다리세요.');
			$returnURL=$this->input->get('returnURL');
			redirect($returnURL?$returnURL:'/');
		}else{
			$this->session->set_flashdata('message', '승인요청 되었습니다. 관리자가 승인할때까지 기다리세요.');
			$this->request_to_master($user_id);
			$returnURL=$this->input->get('returnURL');
			redirect($returnURL?$returnURL:'/');
		}
	}
	public function request_to_master($value='')
	{
		$this->load->model('user_model');
		$this->user_model->add_request_user_list($value);//등급요청 데이터베이스에 저장
		return true;
	}
	public function approval($value='')
	{
		$this->load->model('user_model');
		$data=$this->user_model->get_request_list_all();
		$this->_head();
		$this->load->view('approval',array('data'=>$data));
		$this->_footer();		
	}
	public function upgrade_role($value='')
	{
		$data=$this->input->post('data');
		foreach ($data['role'] as $key => $value) {
			$user[$key]['role']=$value;
			if($value=='일반'){
				$user[$key]['role_id']=3;
			}else{
				$user[$key]['role_id']=2;
			}
		}
		foreach ($data['user_id'] as $key => $value) {
			$user[$key]['user_id']=$value;
		}
		$this->load->model('user_model');
		$result=$this->user_model->upgrade_role($user);
		echo json_encode(true);
	}
	public function reject($value='')
	{
		$data=$this->input->post('data');
		// print_r($data);
		$this->load->model('user_model');
		$result=$this->user_model->delete_request_all($data);
		echo json_encode($result);
	}
	public function leave($value='')
	{
		//세션 유저아이디 정보를 받아서 데이터베이스에서 삭제 즉 탈퇴
		$user_id=$this->session->userdata('user_id');
		$this->load->model('user_model');
		$userdata=$this->user_model->getUserdataById($user_id);
		session_destroy();
		// redirect('/','refresh');
		//print_r($userdata);
		$data=$this->user_model->delete_userdata($user_id);
		echo json_encode($userdata->nickname);
	}
	public function get_userdata($value='')
	{
		$user_id=$this->session->userdata('user_id');
		$this->load->model('user_model');
		$userdata=$this->user_model->getUserdataById($user_id);
		$data['nickname']=$userdata->nickname;
		$data['date']=substr($userdata->date,0,10);
		$data['role_name']=$this->user_model->getRoleNameById($user_id);
		 // print_r($data);
		echo json_encode($data);
	}
	public function get_userNickname($value='')
	{
		$user_id=$this->session->userdata('user_id');
		$this->load->model('user_model');
		$userdata=$this->user_model->getUserdataById($user_id);
		$data['nickname']=$userdata->nickname;
		echo json_encode($data['nickname']);
		return $data['nickname'];
	}
	public function changeNickname($value='')
	{
		$user['nickname']=$this->input->post('data');
		$user['id']=$this->session->userdata('user_id');
		$this->load->model('user_model');
		$this->user_model->changeNickname($user);
		//print_r($user['nickname']);
		echo json_encode($user);
	}
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */

