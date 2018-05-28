<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contents_model extends CI_Model {
	public function __construct()
		{
			parent::__construct();
		}

		// public function search($index)
		// {
		// 	switch ($index) {
			
		// 	case '1':
		// 		$i=0;
		// 		//$this->db->select('age');
		// 		$this->db->group_by('age');
		// 		$this->db->order_by("title", "desc"); 
		// 		$query=$this->db->get('myroad')->result_array();//배열로 리턴
		// 		//print_r($query);
		// 		foreach ($query as $key) {
		// 			$data[] = $key['age'];
		// 			$i++;
		// 		}
		// 		//return $query;//테이블의 전체데이터리턴
		// 		return $data;//테이블의 age값만 리턴
		// 		break;

		// 	case '2':
		// 		$i=0;
		// 		$this->db->group_by('sub_title');
		// 		$query=$this->db->get('interest')->result_array();
		// 		foreach ($query as $key) {
		// 			$data[]=$key['sub_title'];
		// 			$i++;
		// 		}
		// 		return $data;
		// 		break;

		// 	case '3':
		// 		$i=0;
		// 		$this->db->group_by('sub_title');
		// 		$query=$this->db->get('future')->result_array();
		// 		foreach ($query as $key) {
		// 			$data[]=$key['sub_title'];
		// 			$i++;
		// 		}
		// 		return $data;
		// 		break;

		// 	case '4':
		// 		$i=0;
		// 		$this->db->group_by('age');
		// 		$query=$this->db->get('melody')->result_array();
		// 		foreach ($query as $key) {
		// 			$data[]=$key['age'];
		// 			$i++;
		// 		}
		// 		return $data;
		// 		break;
		// 	}
		// }

	public function search($index='')
	{	
		//$this->db->select('title,id');
		$this->db->where('p_id', $index); 
		$query=$this->db->get('category')->result_array();
		return $query;
	}		
	// public function search($index='')
	// {	
	// 	//$this->db->select('title,id');
	// 	$this->db->where('p_id', $index); 
	// 	$query=$this->db->get('category')->result_array();
	// 	if($query==null){
	// 		$query="";
	// 		return $query;
	// 	}else{
	// 		$i=0;
	// 		foreach ($query as $key => $value) {
	// 		//foreach ($value as $keys => $values) {

	// 			$data['id'][$i]=$value['id'];
	// 			$data['title'][$i]=$value['title'];
	// 			$data['level'][$i]=$value['level'];
	// 			$data['p_id'][$i]=$value['p_id'];
	// 			$i++;
	// 	}
	// 	return $data['title'];
	// 	}		
		//쿼리 결과를 다시 쿼리하는법 스터디 필요//쿼리결과가 객체이므로 ->키값 사용해야함
		// $this->db->flush_cache();
		// $this->db->where('p_id',$data['id']);
		// $second=$this->db->get('category')->result_array();
		// if($second==null){
		// 	$a='abc';
		// 	return $a;
		// }else{
		// 	$a='def';
		// 	return $a;
		// }
	// }
	public function check_category($title='')
	{
		$this->db->select('id');
		$this->db->where('title', $title); 
		$query=$this->db->get('category')->row();//선택된 카테고리 명의 아이디 값을 리턴
		if($query){
			//$this->db->select('title');
			$this->db->where('p_id',$query->id);//리턴값이 있다면 아이디 값을 p_id로 가지는 자료 조회
			$data=$this->db->get('category')->result_array();
			return $data;
		}else{
			return $query;	
		}
	}
	public function add_category($p_title,$title)
	{
		$this->db->select('id, level');
		$this->db->where('title',$p_title);
		$query=$this->db->get('category')->row();
		$p_id=$query->id;
		$level=$query->level+1;
		$this->db->insert('category', array('p_id'=>$p_id,'level'=>$level,'title'=>$title));
		return $title;
	}
	public function delete_category($id)
	{
		$this->db->where('id',$id);
		$query=$this->db->get('category')->row();
		$this->db->delete('category',array('id'=>$id));
		return $query;
	}
	public function get_contents($value,$index,$limit,$offset)
	{
		//$this->db->select('id');
		//인덱스 값을 전달받아 테이블 구분
		switch ($index) {
			case '1':
				$table='aboutme';
				break;
			case '2':
				$table='melody';
				break;
			case '3':
				$table='diary';
				break;
		}
		//관리자 및 가족이면 비공개글까지 다 조회
		if($this->session->userdata('master_login')||$this->session->userdata('family_login')){
			$this->db->where('p_id',$value);
			$this->db->order_by("date","desc");
			$query=$this->db->get($table,$limit,$offset)->result();
			if($query){
				return $query;	
			}else{
				return false;
			}
		}else{
			//등급이 안되면 공개글만 조회
			$arrayName = array('p_id' =>$value ,'division'=>'공개');
			$this->db->where($arrayName);
			$this->db->order_by("date","desc");
			$query=$this->db->get($table,$limit,$offset)->result();
			if($query){
				return $query;	
			}else{
				return false;
			}
		}
	}
	public function get_category($value='')
	{
		//최상위 카테고리는 p_id 값이 0
		//카테고리 테이블의 아이디값이 컨텐츠 테이블의 p_id 값
		$this->db->where('p_id',$value);
		$query=$this->db->get('category')->result();
		if($query){
			return $query;
		}
		else{
			return false;
		}
	}
	public function get_update_contents($value='',$index)
	{
		//업데이트 할 테이블 및 테이블 안의 데이터 조회하여 리턴
		$this->db->where('id',$value);
		if($index==1){
			$query=$this->db->get('aboutme')->row_array();
		}else if($index==2){
			$query=$this->db->get('melody')->row_array();
		}else{
			$query=$this->db->get('diary')->row_array();
		}
		if($query){
			return $query;	
		}else{
			return false;
		}
	}
	public function get_update_category_selected($value='')
	{
		//전달된 아이디 값에 해당하는 상위 카테고리 전부 조회
		$sql="SELECT t1.title AS lev4, t2.title AS lev3, t3.title AS lev2, t4.title AS lev1 FROM category AS t1 LEFT JOIN category AS t2 ON t2.id = t1.p_id LEFT JOIN category AS t3 ON t3.id = t2.p_id LEFT JOIN category AS t4 ON t4.id = t3.p_id WHERE t1.id=".$value;
		//$this->db->order_by('p_id');
		$query=$this->db->query($sql)->row_array();
		return $query;
	}
	public function get_related_category($id)
	{
		foreach ($id as $key => $value) {
			$sql="SELECT t1.title AS lev4, t2.title AS lev3, t3.title AS lev2, t4.title AS lev1 FROM category AS t1 LEFT JOIN category AS t2 ON t2.id = t1.p_id LEFT JOIN category AS t3 ON t3.id = t2.p_id LEFT JOIN category AS t4 ON t4.id = t3.p_id WHERE t1.id=";
			$sql=$sql.$value;
			$category=$this->db->query($sql)->row_array();
			//선택된 카테고리마다 깊이가 다르므로 빈 배열 삭제 및 1차카테고리부터 표현되게 구현
			$query[$key]=array_filter(array_reverse($category));
		}
		foreach ($query as $key => $value) {
			$string="";
			foreach ($value as $keys => $values) {
				$string=$string.$values.' > ';
				// print_r($string);
			}
			$strings[$key]=$string;
			$related_category[$key]=substr($strings[$key],0,-2);
		}
		return $related_category;
	}
	public function get_update_category_all($index='')
	{
		// $sql="SELECT t1.title AS title4, t1.p_id AS lev4, t2.title AS title3,t2.p_id AS lev3, t3.title AS title2,t3.p_id AS lev2, t4.title AS title1,t4.p_id AS lev1 FROM category AS t1 LEFT JOIN category AS t2 ON t2.id = t1.p_id LEFT JOIN category AS t3 ON t3.id = t2.p_id LEFT JOIN category AS t4 ON t4.id = t3.p_id WHERE t1.id=".$value;
		$sql="SELECT t1.p_id AS p_id4, t2.p_id AS p_id3, t3.p_id AS p_id2, t4.p_id AS p_id1 FROM category AS t1 LEFT JOIN category AS t2 ON t2.id = t1.p_id LEFT JOIN category AS t3 ON t3.id = t2.p_id LEFT JOIN category AS t4 ON t4.id = t3.p_id WHERE t1.id=".$index;
		// $sql="SELECT t1.title AS p_id4, t2.title AS p_id3, t3.title AS p_id2, t4.title AS p_id1 FROM category AS t1 LEFT JOIN category AS t2 ON t2.p_id = t1.id LEFT JOIN category AS t3 ON t3.p_id = t2.id LEFT JOIN category AS t4 ON t4.p_id = t3.id WHERE t1.id=".$index;
		$query=$this->db->query($sql)->row_array();
		$i=0;
		foreach ($query as $key => $value) {
			$this->db->where('p_id', $value);
			$query2[$i]=$this->db->get('category')->result_array();
			$i++;
		}
		return $query2;
	}
	// public function get_category($value)
	// {
	// 	$this->db->where('p_id',$value);
	// 	$query=$this->db->get('category')->result();
	// 	if($query){
	// 		$i=0;
	// 		$j=0;
	// 		foreach ($query as $key => $value) {
	// 			$this->db->where('p_id', $value->id);
	// 			$data[$i]=$this->db->get('category')->result();
	// 			if($data[$i]){
	// 				foreach ($data[$i] as $keys => $values) {
	// 					//$data2[$j]=$values->id;
	// 					$this->db->where('p_id', $values->id);
	// 					$data2[$j]=$this->db->get('category')->result();
	// 					$j++;
	// 				}
	// 			}
	// 			$i++;
	// 		}
	// 		//$a=array_values(array_filter(array_map('trim',$data2)));
	// 		if($data){$a=array_values(array_filter($data));}
	// 		if($data2){$b=array_values(array_filter($data2));}
	// 		//$category['first']=$query;
	// 		//$category['second']=$a;
	// 		//$category['third']=$b;
	// 		$category=$query;
	// 		return $category;
	// 	}
	// 	else{
	// 		return false;
	// 	}
	// }
	public function add_contents($value='')
	{
		$this->db->select('id');
		$this->db->where('id', $value['category_id']); 
		$query=$this->db->get('category')->row();
		$p_id=$query->id;
		$title=$value['title'];
		$division=$value['division'];
		$description=$value['description'];
		$this->db->set('date', 'now()', false);
		if($value['tableIndex']==1){
			$data=$this->db->insert('aboutme', array('p_id'=>$p_id,'title'=>$title,'description'=>$description,'division'=>$division));
		}else if($value['tableIndex']==2){
			$data=$this->db->insert('melody', array('p_id'=>$p_id,'title'=>$title,'description'=>$description));
		}else if($value['tableIndex']==3){
			$data=$this->db->insert('diary', array('p_id'=>$p_id,'title'=>$title,'description'=>$description));
		}
		return $data;
	}
	// public function add_contents($value='')
	// {
	// 	$this->db->select('id');
	// 	$this->db->where('title', $value['p_id_title']); 
	// 	$query=$this->db->get('category')->row();
	// 	$p_id=$query->id;
	// 	$title=$value['title'];
	// 	$division=$value['division'];
	// 	$description=$value['description'];
	// 	$this->db->set('date', 'now()', false);
	// 	if($value['tableIndex']==1){
	// 		$data=$this->db->insert('aboutme', array('p_id'=>$p_id,'title'=>$title,'description'=>$description,'division'=>$division));
	// 	}else if($value['tableIndex']==2){
	// 		$data=$this->db->insert('melody', array('p_id'=>$p_id,'title'=>$title,'description'=>$description));
	// 	}else if($value['tableIndex']==3){
	// 		$data=$this->db->insert('diary', array('p_id'=>$p_id,'title'=>$title,'description'=>$description));
	// 	}
	// 	return $data;
	// }
	public function add_comment($value)
	{
		if($value['table_index']==1){
			$table='aboutme';
		}else if($value['table_index']==2){
			$table='melody';
		}else{
			$table='diary';
		}
		$this->db->select('id');
		$this->db->where('id',$value['contents_id']);
		$query=$this->db->get($table)->row();
		$p_id=$query->id;
		$user_id=$this->session->userdata('user_id');
		$this->db->set('date', 'now()', false);
		$this->db->insert('comment', array('table_index'=>$value['table_index'],'user_id'=>$user_id,'p_id'=>$p_id,'comment'=>$value['comment']));
		$data=$this->db->insert_id();
		$this->db->where('id', $data);
		$data=$this->db->get('comment')->row_array();
		return $data;
	}
	public function get_comment($value)
	{
		$this->db->order_by('date','desc');
		$this->db->where('p_id',$value['contents_id']);
		$this->db->where('table_index',$value['table_index']);
		$query=$this->db->get('comment')->result_array();
		if($query){
			return $query;
		}else{
			return false;	
		}
	}
	public function delete_comment($value='')
	{
		$this->db->select('table_index, p_id');
		$this->db->where('id',$value);
		$query=$this->db->get('comment')->row();
		$this->db->delete('comment',array('id'=>$value));
		return $query;
	}
	public function update_contents($value='',$id,$index)
	{
		// $this->db->select('id');
		// $this->db->where('title', $value['p_id_title']); 
		// $query=$this->db->get('category')->row();
		// $p_id=$query->id;
		$this->db->where('id', $id);
		$this->db->set('date', 'now()', false);
		if($index==1){
			$query=$this->db->update('aboutme',$value); 
		}else if($index==2){
			$query=$this->db->update('melody',$value); 
		}else{
			$query=$this->db->update('diary',$value); 
		}
		
		return $query;
	}
	public function delete_contents($value='',$index)
	{
		// $this->db->where('id', $value);
		// $data=$this->db->get('contents')->row();
		if($index==1){
			$this->db->delete('aboutme',array('id'=>$value));	
		}else if($index==2){
			$this->db->delete('melody',array('id'=>$value));
		}else{
			$this->db->delete('diary',array('id'=>$value));
		}
		// return $data->p_id;
		return true;
	}
	public function recent_contents($value,$limit,$offset)
	{
		//공개/비공개 글을 나누기 위해 관리자 및 가족회원인지 구분
		if($this->session->userdata('master_login')||$this->session->userdata('family_login')){
			$this->db->order_by("date","desc");//최신글 부터 앞쪽으로 오게
			if($value==1){
				$query=$this->db->get('aboutme',$limit,$offset)->result();//데이터베이스 테이블 구분
			}else if($value==2){
				$query=$this->db->get('melody',$limit,$offset)->result();
			}else{
				$query=$this->db->get('diary',$limit,$offset)->result();
			}
			return $query;
		}else{
			$this->db->order_by("date","desc");
			$this->db->where('division','공개');//등급이 안되면 공개글만 오픈
			if($value==1){
				$query=$this->db->get('aboutme',$limit,$offset)->result();
			}else if($value==2){
				$query=$this->db->get('melody',$limit,$offset)->result();
			}else{
				$query=$this->db->get('diary',$limit,$offset)->result();
			}
			return $query;
		}
	}
	public function recent_contents_count($value='')
	{
		//공개/비공개 글을 나누기 위해 관리자 및 가족회원인지 구분
		if($this->session->userdata('master_login')||$this->session->userdata('family_login')){
			$this->db->order_by("date","desc");//최신글 부터 앞쪽으로 오게
			if($value==1){
				$this->db->from('aboutme');
				$query=$this->db->count_all_results();//데이터베이스 테이블 구분
			}else if($value==2){
				$this->db->from('melody');
				$query=$this->db->count_all_results();
			}else{
				$this->db->from('diary');
				$query=$this->db->count_all_results();
			}
			return $query;
		}else{
			$this->db->order_by("date","desc");
			$this->db->where('division','공개');//등급이 안되면 공개글만 오픈
			if($value==1){
				$this->db->from('aboutme');
				$query=$this->db->count_all_results();
			}else if($value==2){
				$this->db->from('melody');
				$query=$this->db->count_all_results();
			}else{
				$this->db->from('diary');
				$query=$this->db->count_all_results();
			}
			return $query;
		}
	}
	public function get_contents_count($value,$index)
	{
		//$this->db->select('id');
		//인덱스 값을 전달받아 테이블 구분
		switch ($index) {
			case '1':
				$table='aboutme';
				break;
			case '2':
				$table='melody';
				break;
			case '3':
				$table='diary';
				break;
		}
		//관리자 및 가족이면 비공개글까지 다 조회
		if($this->session->userdata('master_login')||$this->session->userdata('family_login')){
			$this->db->where('p_id',$value);
			$this->db->order_by("date","desc");
			$query=$this->db->get($table)->num_rows();
			if($query){
				return $query;	
			}else{
				return false;
			}
		}else{
			//등급이 안되면 공개글만 조회
			$arrayName = array('p_id' =>$value ,'division'=>'공개');
			$this->db->where($arrayName);
			$query=$this->db->get($table)->num_rows();
			if($query){
				return $query;	
			}else{
				return false;
			}
		}
	}
}

/* End of file Contents_model.php */
/* Location: ./application/models/Contents_model.php */