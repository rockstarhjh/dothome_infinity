<?php if($data['category'] ){?>
<div id="wrapper" class="">
    <!-- Sidebar -->
    <nav>
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                        <?=$data['sidebar_title'];?>
                    </a>
                </li>
        	<?php 
        		if($data['category']){
        			$i=1;
    			foreach ($data['category'] as $key => $value) {
                    switch ($value->id) {
                        case '1':
                        case '2':
                        case '3':
                            $path1='aboutme';
                            $path2='story';
                            break;
                        case '4':
                        case '5':
                            $path1='melody';
                            $path2='story';
                            break;
                        case '6':
                            $path1='plan';
                            $path2='diary';
                            break;
                        default:
                            $path1=$data['path1'];
                            $path2=$data['path2'];
                            break;
                    }
        	?>
            <li><a href="/<?=$path1?>/<?=$path2?>/<?=$value->id?>"><?=$i.'.'?> <?=$value->title?></a></li>
            <?php 
            $i++;
            }}                
            ?>
            </ul>
        </div>
    </nav>
    <!-- /#sidebar-wrapper -->
     <!-- Page Content -->
    <div class="container">
        <a href="#menu-toggle" class="btn btn-secondary" id="menu-toggle"><i class="fas fa-align-justify fa-lg"></i></a>
        <section class="session">
<?php 
}else{
?>
<div>
    <div class="container">
        <section class="session">    
<?php
} 
?>
        <?php 
    			if($data['contents']){
    			foreach ($data['contents'] as $key => $value) {
    	?>
        <div id='des<?=$value->id?>'>
        <h2><?=$value->title?></h2>
        <?php if($data['related_category']){
        ?>
        <p class="text-success"><?=$data['related_category'][$key]?>/<?=$value->division?></p>
        <?php 
        } 
        ?>
        <p><?=$value->description?></p>
        <p><?=$value->date?></p>
        <?php 
        if($this->session->userdata('is_login')){
            ?>
            <button type="button" class="btn btn-info btn-sm" id="comment_btn" onclick="comment('<?=$value->id?>','<?=$data['session']?>');">댓글</button>
            <?php
        }
        ?>
        <?php 
        if($this->session->userdata('master_login')){
            $cur_url='/'.uri_string();//함수호출 uri 값 리턴
            $returnURL=rawurlencode(site_url($cur_url));//인코드 후 URL값 리턴
            ?>
            <a href="/contents/update_contents/<?=$value->id?>?returnURL=<?=$returnURL?>&section=<?=$data['session']?>" class="btn btn-success btn-sm" id="update" onclick="return upchk();">수정</a>
            <form action="/contents/delete_contents/<?=$value->id?>?returnURL=<?=$returnURL?>&section=<?=$data['session']?>" method="post"  id="delete" onsubmit="return delchk(<?=$value->id?>);">
                <button type="submit" class="btn btn-danger btn-sm">삭제</button>
            </form>
            
           <!--  <a href='/contents/delete_contents/<?=$value->id?>?returnURL=<?=$returnURL?>' class="btn btn-danger btn-sm" id="delete" onclick="return delchk(<?=$value->id?>);">삭제</a> -->
        <?php 
        }
        ?>
            <div class="comment_box">
                <div class="input-group input-group-sm mb-3 comment_input">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-sm"><?=$this->session->userdata('user_Nickname');?></span>
                  </div>
                  <input type="text" class="form-control comment_submit" aria-describedby="inputGroup-sizing-sm" placeholder="댓글 입력">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" onclick="comment_submit('<?=$value->id?>','<?=$data['session']?>')">작성</button>
                  </div>
                </div>
            </div>
         </div>
         <hr>
        <?php 
    		}
    		}else{
                if($this->session->userdata('master_login')||$this->session->userdata('family_login')){
                ?>
                <h2>관련 컨텐츠가 없습니다.</h2>
            <?php
                }else if($this->session->userdata('is_login')){
                    ?>
                <h2>관련 컨텐츠가 없거나 일반회원이라 볼수 없습니다.</h2>
                <?php    
                }else{
                    ?>
                <h2>관련 컨텐츠가 없거나 비회원이라 볼수 없습니다.</h2>    
                <?php
                } 
            }
    	?>
        </section>
        <hr>
        <nav aria-label="Page navigation example">
          <ul class="pagination pagination-sm justify-content-center">
           <!--  <li class="page-item">
              <a class="page-link" href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
              </a>
            </li> -->
        <?php 
            echo $this->pagination->create_links();
        ?>
           <!--  <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
              </a>
            </li> -->
          </ul>
        </nav>
    </div>
</div>

