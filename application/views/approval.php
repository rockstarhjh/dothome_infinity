<!-- <?php 
	print_r($data);
?> -->
<span class="d-block p-2 bg-dark text-center text-white">승급요청 현황</span>
<table class="table table-bordered table-dark">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">회원닉네임</th>
      <th scope="col">신청날짜</th>
      <th scope="col">회원등급</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      foreach ($data as $key => $value) {
        ?>
        <tr>
          <th scope="row"><?=$key+1?></th>
          <td><?=$value['user_name']?></td>
          <td><?=$value['date']?></td>
          <td><select>
            <option value=<?=$value['user_id']?>><?=$value['user_role']?></option>
            <option value=<?=$value['user_id']?>>가족</option>
          </select></td>
        </tr>
      <?php 
      }
      ?>
  </tbody>
</table>
  <div>
    <button type="button" class="btn btn-primary" onclick="approval()">승인</button>
    <button type="button" class="btn btn-danger" onclick="reject()">반려</button>
  </div>

<script type="text/javascript">
  function approval() {
    var role=[];
    var user_id=[];
    var optionSelected = $('select').each(function(index,item){
      role.push($(this).find("option:selected").text());
      user_id.push($(this).find("option:selected").val());
    });
    data={'user_id':user_id, 'role':role};//등급요청 유저 아이디 및 등급 가져오기
    var r;
        r=confirm('정말로 승인하시겠습니까?');
        if(r==true){
          $.ajax({
                url:'/auth/upgrade_role',
                type:'post',
                data:{"data":data},
                dataType:'json',
                success:function(data){
                  alert("회원 등급이 변경 되었습니다.");
                  window.location.href = '/contents/add_contents';
                }
          })
        }else{
          alert('취소되었습니다.');
          window.location.href = '/contents/add_contents';
        }
  }
  function reject() {
    var user_id = [];
    var optionSelected = $('select').each(function(index,item){
      user_id.push($(this).find("option:selected").val());
    });
  	var r;
        r=confirm('정말로 반려하시겠습니까?');
        if(r==true){
          $.ajax({
                url:'/auth/reject',
                type:'post',
                data:{"data":user_id},//반려하려는 유저아이디 전달
                dataType:'json',
                success:function(data){
                  alert("반려 되었습니다.");
                  window.location.href = '/contents/add_contents';
                }
          })
        }else{
          alert('취소되었습니다.');
          window.location.href = '/contents/add_contents';
        }
  }
</script>