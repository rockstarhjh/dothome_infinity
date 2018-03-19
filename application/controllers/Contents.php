<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contents extends MY_Controller {

	public function index()
	{
		
	}
	// public function check($value='')
	// {
	// 	$index=$this->input->post('index');
	// 	//echo $index;
	// 	$this->load->model('contents_model');
	// 	$data=$this->contents_model->search($index);
	// 	//print_r($data);
	// 	//print_r($data['title']);
	// 		$i=0;
	// 		foreach ($data as $key => $value) {
	// 		//foreach ($value as $keys => $values) {

	// 			$data['id'][$i]=$value['id'];
	// 			$data['title'][$i]=$value['title'];
	// 			$data['level'][$i]=$value['level'];
	// 			$data['p_id'][$i]=$value['p_id'];
	// 			$i++;
	// 		}
	// 	echo json_encode($data['title']);
	// }
	public function check_category($value='')
	{
		$title=$this->input->post('title');
		$this->load->model('contents_model');
		$data=$this->contents_model->check_category($title);
		echo json_encode($data);
	}
	// public function third_check($value='')
	// {
	// 	$title=$this->input->post('title');
	// 	$this->load->model('contents_model');
	// 	$data=$this->contents_model->search2($title);
	// 	echo json_encode($data);
	// }
	public function add_category($value='')
	{
		$title=$this->input->post('data');
		//print_r($title);
		$this->load->model('contents_model');
		$data=$this->contents_model->add_category($title['p_title'],$title['title']);
		echo json_encode($data);
	}
	public function delete_category($value='')
	{
		$id=$this->input->post('data');
		//print_r($title);
		$this->load->model('contents_model');
		$data=$this->contents_model->delete_category($id);
		//print_r($data);
		echo json_encode($data->title);
	}
	public function update_contents($index='')
	{
		$returnURL=$this->input->get('returnURL');
		$data['section']=$this->input->get('section');//수정할 테이블 인덱스 값 가져오기
		$data['returnURL']=rawurlencode($returnURL);
		$this->load->model('contents_model');
		$data['contents']=$this->contents_model->get_update_contents($index,$data['section']);
		$id=$data['contents']['p_id'];
		$category=$this->contents_model->get_update_category_selected($id);//수정할 카테고리 부모 및 자신 다 가져오기
		$category=array_filter(array_reverse($category));//카테고리에 따른 깊이가 다르므로 배열 전환후 빈 배열 날리기
		$i=0;
		foreach ($category as $key => $value) {
			$data['category'][$i]=$value;
			$i++;
		}
		$query=$this->contents_model->get_update_category_all($id);//첫번째 카테고리는 디폴트로 전부여야 하므로 다 가져오기
		$query=array_reverse(array_filter($query));
		//print_r($query);
		$i=0;
		foreach ($query as $key => $value) {
			$data['list'][$key]=array();
			foreach ($value as $keys => $values) {
				$data['list'][$key][$i]=$values['title'];
				$i++;
			}
		}
		$i=0;
		foreach ($query as $key => $value) {
			$data['id'][$key]=array();
			foreach ($value as $keys => $values) {
				$data['id'][$key][$i]=$values['id'];
				$i++;
			}
		}
		//print_r($data);
		$this->_head();
		$this->_form();
		if($this->form_validation->run()){
			$chk=$this->contents_model->update_contents(array(
				'title' =>$this->input->post('title') ,
				'description'=>$this->input->post('description'),
				'p_id'=>$this->input->post('p_id'),
				'division'=>$this->input->post('enumValue'),
			),$index,$data['section']);
			if($chk){
			$this->session->set_flashdata('message', '저장했습니다.');
			}else{
			$this->session->set_flashdata('message', '저장에 실패했습니다.');	
			}
			redirect($returnURL);
		}else{
			$this->load->view('update_contents',array('data' =>$data));
		}
		$this->_footer();
	}
	public function delete_contents($index='')
	{
		$returnURL=$this->input->get('returnURL');
		$sectionIndex=$this->input->get('section');
		$data=$this->input->post('fileName');
		if($data){
			foreach ($data as $key => $value) {
				$fileName=".".$value;
				if(file_exists($fileName)){
					unlink("$fileName");
				}
			}
		}
		$this->load->model('contents_model');
		$this->contents_model->delete_contents($index,$sectionIndex);

		redirect($returnURL,'refresh');
	}
	public function add_contents($value='')
	{
		$this->_head();
		// if(!$this->session->userdata('master_login')){
		// 	$this->session->set_flashdata('message', '로그인을 먼저 해주세요.');
		// 	redirect('/auth/master');
		// }else{
			$this->load->model('contents_model');
			$this->load->model('user_model');
			$data=$this->contents_model->search(0);//최상위 카테고리 목록 가져오기
			$list=$this->user_model->get_request_count();//등급요청 수 가져오기
			$this->_form();
			if($this->form_validation->run()){
				$chk=$this->contents_model->add_contents(array(
					'title' =>$this->input->post('title') ,
					'description'=>$this->input->post('description'),
					'p_id_title'=>$this->input->post('p_id_title'),
					'tableIndex'=>$this->input->post('sectionIndex'),
					'division'=>$this->input->post('enumValue'),
				));
				if($chk){
				$this->session->set_flashdata('message', '저장했습니다.');
				redirect('/contents/add_contents');
				}else{
					$this->session->set_flashdata('message', '저장에 실패했습니다.');
				}
				$this->load->view('master_mode');	
			}else{
				$this->load->view('master_mode',array('data' =>$data,'request'=>$list));
			}
		// }
		$this->_footer();
	}
	public function comment($value='')
	{
		$data=$this->input->post('data');
		$this->load->model('contents_model');
		$result=$this->contents_model->add_comment($data);
		$this->load->model('user_model');
		$result['nickname']=$this->user_model->getUserNicknameByUserId($result['user_id']);
		echo json_encode($result);
	}
	public function get_comment($value='')
	{
		$data=$this->input->post('data');
		$this->load->model('contents_model');
		$this->load->model('user_model');
		$result=$this->contents_model->get_comment($data);
		if($result){
			foreach ($result as $key => $value) {
				$user_id[$key]=$value['user_id'];
			}
			$nickname=$this->user_model->getUserNicknameByUserId($user_id);
			foreach ($nickname as $key => $value) {
				$result[$key]['nickname']=$value->nickname;
			}
			echo json_encode($result);
		}else{
			return false;
		}
	}
	public function delete_comment($value='')
	{
		$comment_id=$this->input->post('data');
		$this->load->model('contents_model');
		$result=$this->contents_model->delete_comment($comment_id);
		echo json_encode($result);
	}
	function _form($value='')
	{
		$this->load->library('form_validation');	
			$config = array(
		        array(
		                'field' => 'title',
		                'label' => '제목',
		                'rules' => 'required',
		                'errors' => array(
		                        'required' => '제목을 입력해야합니다.',
		                ),
		        ),
		        array(
		                'field' => 'description',
		                'label' => '내용',
		                'rules' => 'required',
		                'errors' => array(
		                        'required' => '내용을 입력해야합니다.',
		                ),
		        ),
		        );
		$this->form_validation->set_rules($config);
	}
	// public function upload_receive($value='')
	// {
	// 	$config['upload_path']          = './static/img/uploads/';
 //        $config['allowed_types']        = 'gif|jpg|png';
 //        // $config['max_size']             = 100;
 //        // $config['max_width']            = 1024;
 //        // $config['max_height']           = 768;

 //        $this->load->library('upload', $config);

 //        if ( ! $this->upload->do_upload("upload"))
 //        {
 //            //$error = array('error' => $this->upload->display_errors());
 //            echo "<script>alert('업로드에 실패 했습니다. ".$this->upload->display_errors('','')."')</script>";
 //        }
 //        else
 //        {
 //            // $data = array('upload_data' => $this->upload->data());
	//         $CKEditorFuncNum = $this->input->get('CKEditorFuncNum');
	// 	    $data = $this->upload->data();
	// 	    $filename = $data['file_name'];
		    
	// 	    $url = '/static/img/uploads/'.$filename;
	// 	     //$exifData = exif_read_data('static/img/uploads/'.$data['file_name']);
	// 	     //print_r($exifData);

 //            echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('".$CKEditorFuncNum."', '".$url."', '전송에 성공 했습니다')</script>"; 
 //        }
	// }
	public function upload_receive($value='')
	{
		$config['upload_path']          = './static/img/uploads/';
        $config['allowed_types']        = 'gif|jpg|png';
        // $config['max_size']             = 100;
        // $config['max_width']            = 1024;
        // $config['max_height']           = 768;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload("upload"))
        {
            //$error = array('error' => $this->upload->display_errors());
            echo "<script>alert('업로드에 실패 했습니다. ".$this->upload->display_errors('','')."')</script>";
        }
        else
        {
            // $data = array('upload_data' => $this->upload->data());
	        $CKEditorFuncNum = $this->input->get('CKEditorFuncNum');
		    $data = $this->upload->data();
		    $filename = $data['file_name'];
		    
		    $url = '/static/img/uploads/'.$filename;
		    $exifData = exif_read_data('static/img/uploads/'.$filename);
		    //print_r($exifData['Orientation']);
		    //$this->session->set_flashdata('message', $exifData['Orientation']);

		     // 270도 돌려야 정상적으로 출력됨
            if( isset($exifData['Orientation']) )
            {
                if($exifData['Orientation'] == 6)
                {
                    $degree = 270;
                }
                // 반시계방향으로 90도 돌려줘야 정상
                else if ($exifData['Orientation'] == 8)
                {
                    $degree = 90;
                }
                //여기가 자꾸 에러가 나고 적용이 되지 않는데 312~313번째 줄처럼 코드변경후 사용 가능해짐
                else if ($exifData['Orientation'] == 3)
                {
                    $degree = 180;
                }else{
                	$degree = 0;
                }
                //print_r($degree); 
                if($degree && !$degree==0) {
                	//print_r('abd');
                	//print_r($exifData['FileType']);
                    if($exifData['FileType'] == 1)
                    {
                        $source = imagecreatefromgif('static/img/uploads/'.$data['file_name']);
                        $source = imagerotate ($source , $degree, 0);
                        imagegif($source, 'static/img/uploads/'.$data['file_name']);
                    }
                    else if($exifData['FileType'] == 2)
                    {
                    	@ini_set('gd.jpeg_ignore_warning', 1);
                        $source = @imagecreatefromjpeg('static/img/uploads/'.$data['file_name']);
                        $source = imagerotate ($source , $degree, 0);
                        imagejpeg($source, 'static/img/uploads/'.$data['file_name']);
                        // $exifData = exif_read_data('static/img/uploads/'.$filename);
                        // print_r($exifData);
                    }
                    else
                    {
                        $source = imagecreatefrompng('static/img/uploads/'.$data['file_name']);
                        $source = imagerotate ($source , $degree, 0);
                        imagepng($source, 'static/img/uploads/'.$data['file_name']);
                    }
                    // else if($exifData['FileType'] == 3)
                    // {
                    //     $source = imagecreatefrompng('static/img/uploads/'.$data['file_name']);
                    //     $source = imagerotate ($source , $degree, 0);
                    //     imagepng($source, 'static/img/uploads/'.$data['file_name']);
                    // }
 
                    imagedestroy($source);
                }
            }
            $config['image_library'] = 'gd2';
            $config['source_image'] = 'static/img/uploads/'.$data['file_name'];
            $img_path=$config['source_image'];
            $img_width=getimagesize($img_path);
            if($img_width[0]<1080){
            	print_r($img_width[0]);
        		$config['width'] = $img_width[0];
            }else{
            	$config['width'] = 1080;	
            }
            $config['maintain_ratio'] = TRUE;
 
            $this->load->library('image_lib', $config);
 
            $this->image_lib->resize();
            // print_r($CKEditorFuncNum);
            // print_r($url);
            echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction('".$CKEditorFuncNum."', '".$url."', '전송에 성공 했습니다')</script>"; 
        }
	}
}


/* End of file Contents.php */
/* Location: ./application/controllers/Contents.php */