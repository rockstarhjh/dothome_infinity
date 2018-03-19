<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function add($value)
	{
		//회원가입을 위해 전달받은 정보를 DB에 넣고 그 결과를 리턴
		$this->db->set('user_id',$value['id']);
		$this->db->set('nickname',$value['nickname']);
		$this->db->set('password',$value['password']);
		$this->db->set('date','now()',false);
		$this->db->insert('user');
		$result=$this->db->insert_id();
		$data=$this->add_role_type($result);
		return $data;
	}
	public function add_role_type($value='')
	{
		$this->db->set('role_id',3);
		$this->db->set('user_id',$value);
		$this->db->set('role_name','일반');
		$this->db->insert('role_type');
		$id=$this->db->insert_id();
		$result=$this->db->get_where('role_type',array('id'=>$id))->row();
		return $result;
	}
	public function getByUserId($value)
	{
		//로그인시 아이디를 바탕으로 회원정보를 조회하여 그 결과를 리턴
		$result=$this->db->get_where('user', array('user_id'=>$value['id']))->row();
		return $result;
	}
	public function getUserNicknameByUserId($id)
	{
		if(is_array($id)){
			foreach ($id as $key => $value) {
				$this->db->select('nickname');
				$result[$key]=$this->db->get_where('user', array('id'=>$value))->row();
			}
			return $result;
		}else{
			$this->db->select('nickname');
			$result=$this->db->get_where('user', array('id'=>$id))->row();
			return $result->nickname;
		}
	}
	public function getRoleType($value='')
	{
		$result=$this->db->get_where('role_type', array('user_id'=>$value))->row();
		if($result->role_id==1){
			$status='master_login';
		}else if($result->role_id==2){
			$status='family_login';
		}else{
			$status='general_login';
		}
		$data['role']=$result;
		$data['status']=$status;
		return $data;
	}
	public function add_request_user_list($value='')
	{
		$this->db->select('id, user_id, nickname');
		$data['user']=$this->db->get_where('user', array('id'=>$value))->row();
		$data['role']=$this->db->get_where('role_type',array('user_id'=>$data['user']->id))->row();
		$this->db->set('user_id',$data['role']->user_id);
		$this->db->set('user_name',$data['user']->nickname);
		$this->db->set('user_role',$data['role']->role_name);
		$this->db->set('role_id',$data['role']->role_id);
		$this->db->set('date','now()',false);
		$this->db->insert('request');
		return true;
	}
	public function get_request_count($value='')
	{
		$data=$this->db->count_all('request');
		return $data;
	}
	public function get_request_list_By_UserID($value='')
	{
		$data=$this->db->get_where('request',array('user_id'=>$value))->result_array();//요청한 user_id 값으로 데이터가 있는지 확인하여 리턴
		return $data;
	}
	public function get_request_list_all($value='')
	{
		$data=$this->db->get_where('request')->result_array();//저장된 요청 데이터 전부 조회후 리턴
		return $data;
	}
	public function upgrade_role($value='')
	{
		foreach ($value as $key => $values) {
			$this->db->where('user_id',$values['user_id']);
			$this->db->set('role_id',$values['role_id']);
			$this->db->set('role_name',$values['role']);
			$this->db->update('role_type'); 
		}
		$this->delete_request($value);//등급 수정후 등급요청 자료 삭제
		return true;
	}
	public function delete_request($value='')
	{
		foreach ($value	as $key => $values) {
			if($values['role_id']==2){
				$this->db->delete('request', array('user_id' => $values['user_id'])); 
			}
		}
	}
	public function delete_request_all($value='')
	{
		foreach ($value as $key => $values) {
			$this->db->delete('request', array('user_id'=>$values)); 
		}
		return true;
	}
	public function getUserdataById($value='')
	{
		// $this->db->select('nickname');
		$data=$this->db->get_where('user',array('id'=>$value))->row();
		return $data;
	}
	public function delete_userdata($value='')
	{
		$this->db->delete('user', array('id'=>$value));
		return true;
	}
	public function getRoleNameById($value='')
	{
		$this->db->select('role_name');
		$result=$this->db->get_where('role_type', array('user_id'=>$value))->row_array();
		return $result;
	}
	public function changeNickname($value='')
	{
		$this->db->where('id',$value['id']);
		$this->db->set('nickname',$value['nickname']);
		$this->db->update('user');
		return true; 
	}
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */