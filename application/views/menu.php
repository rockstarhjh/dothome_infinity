<div class="container wrap" id="winWidth">
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top" id="navBar">
  <div class="container">
    <a class="navbar-brand" id="logo" href="/">rockstar<img src="/static/img/main/electric-guitar.svg" style="height:30px;"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            About me
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="/aboutme/story/1">살아온길</a>
            <div class="dropdown-divider"></div>           
            <a class="dropdown-item" href="/aboutme/story/2">관심분야</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="/aboutme/story/3">내일의 나</a>
          </div>
        </li>
         <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Melody
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="/melody/story/4">선율이</a>
              <div class="dropdown-divider"></div>           
              <a class="dropdown-item" href="/melody/story/5">육아일기</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Plan
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="/plan/diary/6">diary</a>
              <div class="dropdown-divider"></div>           
              <a class="dropdown-item" href="/plan/schedule">schedule</a>
          </div>
        </li>
      </ul>
      <!-- <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      </form> -->
      <ul class="nav justify-content-end">
        <?php   
            if($this->session->userdata('master_login')){
        ?>
        <li class="nav-item">
          <a class="nav-link" href="/contents/add_contents?returnURL=<?=uri_string()?>">관리자모드</a>
        </li>
        <?php
        }
        ?>
        <?php   
            if($this->session->userdata('is_login')){
        ?>
              <div class="dropdown show" id="user_table">
                <a class="btn" href="#" role="button" id="user_info" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="userdata()">
                  내정보
                </a>
                <!-- 정보창 여기에 표시 -->
              </div>
        <?php
        }
        ?>
        <?php   
            if($this->session->userdata('is_login')){
        ?>
              <li class="nav-item">
                <a class="nav-link" href="/auth/logout">로그아웃</a>
              </li>
        <?php  
            }  
            else{
        ?>
               <li class="nav-item">
                  <a class="nav-link" href="/auth/login?returnURL=<?=uri_string()?>">로그인</a>
              </li>     
        <?php
            }
        ?>
        <?php   
            if($this->session->userdata('is_login') && $this->session->userdata('general_login')){
        ?>
              <li class="nav-item">
                <a class="nav-link" href="/auth/request?returnURL=<?=uri_string()?>">승급요청</a>
              </li>
        <?php
        }
        ?>
        
        <?php   
            if($this->session->userdata('is_login') && !$this->session->userdata('master_login')){
        ?>
              <li class="nav-item">
                <a class="nav-link" href="#" onclick="delete_userdata()">회원탈퇴</a>
              </li>

        <?php  
            }  
            if(!$this->session->userdata('is_login')){
        ?>       
        <li class="nav-item">
          <a class="nav-link" href="/auth/register?returnURL=<?=uri_string()?>">회원가입</a>
        <?php
          }
        ?>
        </li>
      </ul>
    </div>
  </div>  
</nav>
