<div class="row">
	<div class="col-sm"></div>
	<div class="col-sm">
		<?php echo validation_errors(); ?>
		<form action="/auth/register" method="post">
		  <div class="form-group">
		    <label for="exampleInputEmail1">아이디</label>
		    <input type="text" class="form-control" name="id" id="id" aria-describedby="emailHelp" placeholder="아이디 입력" value="<?php echo set_value('id');?>">
		  </div>
		  <div class="form-group">
		    <label for="nickname">닉네임</label>
		    <input type="text" class="form-control" name="nickname" id="nickname" placeholder="닉네임 입력" value="<?php echo set_value('nickname');?>">
		  </div>
		  <div class="form-group">
		    <label for="exampleInputPassword1">비밀번호</label>
		    <input type="password" class="form-control" name="password" id="password" placeholder="비밀번호 입력" value="<?php echo set_value('password');?>">
		  </div>
		  <div class="form-group">
		    <label for="exampleInputPassword1">비밀번호확인</label>
		    <input type="password" class="form-control" name="re_password" id="re_password" placeholder="비밀번호 확인" value="<?php echo set_value('re_password');?>">
		  </div>
		  <button type="submit" class="btn btn-primary">회원가입</button>
		</form>
	</div>
	<div class="col-sm"></div>
</div>

<div class="container">
	<div class="row align-self-center">
		<div class="col-sm"></div>
		<div class="alert alert-primary" role="alert">
		  <p>회원정보는 bcrypt 방식으로 암호화 되어 관리자도 알 수 없습니다.</p>
		  다만, HTTPS는 적용되어 있지 않습니다. ㅜㅜ
		</div>
		<div class="col-sm"></div>
	</div>
</div>