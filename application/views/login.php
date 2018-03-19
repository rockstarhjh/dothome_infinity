<div class="row">
	<div class="col-sm"></div>
	<div class="col-sm">
		<form action="/auth/check_login?returnURL=<?=$returnURL?>" method="post">
		  <div class="form-group">
		    <label for="exampleInputEmail1">아이디</label>
		    <input type="text" class="form-control" name="id" id="id" aria-describedby="emailHelp" placeholder="아이디 입력">
		  </div>
		  <div class="form-group">
		    <label for="exampleInputPassword1">비밀번호</label>
		    <input type="password" class="form-control" name="password" id="password" placeholder="비밀번호 입력">
		  </div>
		  <div class="form-check">
		  </div>
		  <button type="submit" class="btn btn-primary">로그인</button>
		</form>
	</div>
	<div class="col-sm"></div>
</div>