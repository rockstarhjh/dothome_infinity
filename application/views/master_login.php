<!-- <script type="text/javascript">
	confirm("관리자만 접근할수 있습니다.\n로그인 하시겠습니까?")
</script> -->
<!-- 전달받은 리턴 url 값을 폼액션에 설정 및 폼 형태 구현 -->
<div class="row">
	<div class="col-sm"></div>
	<div class="col-sm">
		<form action="/auth/authentication?returnURL=<?=$returnURL?>" method="post">
		  <div class="form-group">
		    <label for="exampleInputEmail1">관리자 아이디</label>
		    <input type="text" class="form-control" name="id" id="id" aria-describedby="emailHelp" placeholder="아이디 입력">
		    <!-- <small id="emailHelp" class="form-text text-muted">관리자가 아닐경우 접속이 불가합니다.</small> -->
		  </div>
		  <div class="form-group">
		    <label for="exampleInputPassword1">비밀번호</label>
		    <input type="password" class="form-control" name="password" id="password" placeholder="비밀번호 입력">
		  </div>
		  <div class="form-check">
		    <!-- <input type="checkbox" class="form-check-input" id="exampleCheck1"> -->
		    <!-- <label class="form-check-label" for="exampleCheck1">Check me out</label> -->
		  </div>
		  <button type="submit" class="btn btn-primary">로그인</button>
		</form>
	</div>
	<div class="col-sm"></div>
</div>

