<div id="all_category">
<div class="form-group input-group" id="first">
    <label class="input-group-text label" >1st 카테고리</label>
    <select class="form-control" id="category" name="category" value="1">
    	<option selected disabled hidden >1st category</option>
		<?php 
			foreach ($data as $key => $value) {
		?>
			<option value="<?=$value['id']?>"><?=$value['title']?></option>
			<?php 
			}
		?>
    </select>
</div>
</div>
  <div class="input-group mb-3">
	  <div class="input-group-prepend">
	    <div class="input-group-text">
	      <input type="checkbox" name="add_check" id="add_check">
	    </div>
	  </div>
	  <input type="text" class="form-control" id="menu_title" aria-label="Text input with checkbox" placeholder="메뉴추가/선택메뉴 있을시 하위에 메뉴추가">
	  <button type="button" class="btn btn-primary btn-sm" onclick="add_category()">메뉴추가</button>
	  <span class="badge badge-light"> </span>
	  <button type="button" class="btn btn-danger btn-sm" onclick="delete_category()">메뉴삭제</button>
  </div>
  <?php echo validation_errors(); ?>
<form action="/contents/add_contents" method="post" enctype="multipart/form-data" onsubmit="return save()">
	  <div class="form-group input-group">
	    <label class="input-group-text">제목</label>
	    <select id="secret" name="secret">
	    	<option value='공개'>공개</option>
	    	<option value='비공개'>비공개</option>
	    </select>
	    <input type="text" class="form-control" name="title" id="exampleFormControlInput1" placeholder="title" value="<?php echo set_value('title');?>">
	  </div>
	  <div class="form-group">
	    <label class="input-group-text">내용</label>
	    <textarea class="form-control ckfinder" id="editor" name="description" value="<?php echo set_value('description');?>"></textarea>
	  </div>
	  <button type="submit" class="btn btn-primary" id="save_contents">저장</button>
	  <input type="hidden" id="c_id" name="c_id">
	  <input type="hidden" id="sectionIndex" name="sectionIndex">
	  <input type="hidden" id="enumValue" name="enumValue">
</form>
<?php 
	if($request>0){
?>
<div class="alert alert-primary text-center" role="alert">
  <a href='/auth/approval' class="text-danger">승인요청건수 <?=$request?>개가 있습니다.</a>
</div>
	<?php
	}
	?>
<script src="/static/lib/ckeditor/ckeditor.js"></script>
<!-- <script src="/static/lib/ckfinder/ckfinder.js"></script> -->
<script>
	//텍스트에어리어에 ck에디터 적용 및 업로드시 요청 페이지 설정
	// CKEDITOR.replace( 'editor' );
	CKEDITOR.replace( 'editor',{
		// filebrowserBrowseUrl: '/contents/upload_receive/';
		// filebrowserUploadUrl: '/contents/upload_receive/',
		extraPlugins: 'youtube,imageuploader',
		// extraPlugins: 'imageuploader',
	} );
	CKEDITOR.on( 'instanceReady', function( evt ) {
	  evt.editor.dataProcessor.htmlFilter.addRules( {
	    elements: {
	      img: function(el) {
	        el.addClass('img-fluid');//업로드 이미지에 클래스 추가
	      }
	    }
	  });
	});
	// CKFinder.setupCKEditor( editor );
</script>
<script type="text/javascript">
	//카테고리 선택할때마다 하위 카테고리 있는지 확인하는 기능
	$('#all_category').on('change','select.form-control',function () {
     var optionSelected = $(this).find("option:selected");
     var selectVal=$(this).find("option:selected").parents('select').attr('value');
     var valueSelected  = optionSelected.val();
     var textSelected   = optionSelected.text();
     // console.log(optionSelected);
     // console.log(selectVal);
     // console.log(valueSelected);
     // console.log(textSelected);
     $.ajax({
	            url:'/contents/check_category',
	            type:'post',
	            data:{"title":textSelected},
	            dataType:'json',
	            success:function(data){
	            	if(!jQuery.isEmptyObject(data)){
	            		switch (selectVal){
	            			case '1':
	            				$('#second').remove();
	            				$('#third').remove();
	            				$('#fourth').remove();
	            				var htmlStr='<div class="form-group input-group" id="second"><label class="input-group-text label">2nd 카테고리</label><select class="form-control" id="2nd_category" name="2nd_category" value="2"><option selected disabled hidden >2nd category</option>'; 
	            			break;
	            			case '2':
	            				$('#third').remove();
	            				$('#fourth').remove();
	            				var htmlStr='<div class="form-group input-group" id="third"><label class="input-group-text label">3rd 카테고리</label><select class="form-control" id="3rd_category" name="3rd_category" value="3"><option selected disabled hidden>3rd category</option>';
           					break;
           					case '3':
           						$('#fourth').remove();
           						var htmlStr='<div class="form-group input-group" id="fourth"><label class="input-group-text label">4th 카테고리</label><select class="form-control" id="4th_category" name="4th_category" value="4"><option selected disabled hidden>4th category</option>';
           					break;
	            			default :
						    alert('하위메뉴가 없습니다.');
						    break;
	            		}
	            		for(var name in data){
	            					htmlStr+="<option value='"+data[name]['id']+"'>"+data[name]['title']+"</option>";
   						}
   						htmlStr+='</select></div>';
   						$("#all_category").append(htmlStr);
	            	}else{
	            		switch (selectVal){
	            			case '1':
	            				$('#second').remove();
	            				$('#third').remove();
	            				$('#fourth').remove();
	            			break;
	            			case '2':
	            				$('#third').remove();
	            				$('#fourth').remove();
	            			break;
	            			case '3':
	            				$('#fourth').remove();
	            			break;
	            		}
        			}
        		}
	        });
 	})
   	//메뉴추가전 체크여부 확인 및 옵션의 선택된 제목텍스트 값 리턴 기능
    function add(){
	    var a=$('#category').find("option:selected").index();
	    var b=$('#2nd_category').find("option:selected").index();
	    var c=$('#3rd_category').find("option:selected").index();
	    var d=$('#4th_category').find("option:selected").index();
    	if(d&&d!==-1){
    		var title = $('#4th_category').find("option:selected").text();
    	}
    	else if(c&&c!==-1){
    		var title = $('#3rd_category').find("option:selected").text();
    	}else if(b&&b!==-1){
    		var title = $('#2nd_category').find("option:selected").text();
    	}else if(a&&a!==-1){
    		var title = $('#category').find("option:selected").text();
    	 }else{
    	 	var title=null;
    	 }
    	return title;
    };
    //관리자모드의 카테고리 메뉴이름이 같을 시 첫번째에 입력되는 현상 수정
    //예 선율이 돌~2돌, 2돌~3돌 둘다 동영상의 메뉴가 존재할때 항상 돌~2돌의 동영상에 저장되는 현상
    function get_category_id(){
	    var a=$('#category').find("option:selected").attr('value');
	    var b=$('#2nd_category').find("option:selected").attr('value');
	    var c=$('#3rd_category').find("option:selected").attr('value');
	    var d=$('#4th_category').find("option:selected").attr('value');
    	if(d!==undefined){
    		var c_id=d;//카테고리 id
    	}
    	else if(c!==undefined){
    		var c_id=c;
    	}else if(b!==undefined){
    		var c_id=b;
    	}else if(a!==undefined){
    		var c_id=a;
    	 }else{
    	 	var c_id=null;
    	 }
    	return c_id;
    };
    function del(){
	    var a=$('#category').find("option:selected").index();
	    var b=$('#2nd_category').find("option:selected").index();
	    var c=$('#3rd_category').find("option:selected").index();
	    var d=$('#4th_category').find("option:selected").index();
    	if(d&&d!==-1){
    		var id = $('#4th_category').find("option:selected").val();
    	}
    	else if(c&&c!==-1){
    		var id = $('#3rd_category').find("option:selected").val();
    	}else if(b&&b!==-1){
    		var id = $('#2nd_category').find("option:selected").val();
    	}else if(a&&a!==-1){
    		var id = $('#category').find("option:selected").val();
    	 }else{
    	 	var id=null;
    	 }
    	return id;
    };
    function section_index(){
    	var a=$('#category').find("option:selected").attr('value');
    	if(a=="1" || a=="2" || a=="3"){
    		return 1;
    	}else if(a=="4" || a=="5"){
    		return 2;
    	}else if(a=="6"){
    		return 3;
    	}else{
    		return 4;
    	}

    }
    //하위메뉴 데이터베이스에 추가 기능
    function add_category(){
    	if(!$("#add_check").prop("checked")){
    		alert('체크를 먼저해주세요');
    	}else if(!$.trim($('#menu_title').val())){
    		alert('추가할 메뉴이름을 적어주세요');
    	}else{
    		var r;
    		r=confirm('정말로 추가하시겠습니까?');
    		if(r==true){
    			var p_title=add();//추가할 메뉴 상위의 카테고리 명 가져오기
    			var title=$.trim($('#menu_title').val());
    			data={'p_title':p_title, 'title':title};
    			//console.log(data);
    			$.ajax({
		            url:'/contents/add_category',
		            type:'post',
		            data:{"data":data},
		            dataType:'json',
		            success:function(data){
		            	alert(data+" 메뉴가 추가되었습니다.");
	            	}
	        })
    		}else{
    			alert('취소되었습니다.');
    		}
    	}
    }
    function delete_category(){
    	if(!$("#add_check").prop("checked")){
    		alert('체크를 먼저해주세요');
    	}else{
    		var r;
    		r=confirm('정말로 삭제하시겠습니까?');
    		if(r==true){
    			var id = del();//마지막 선택된 카테고리명의 아이디값 가져오기
    			//console.log(id);
    			$.ajax({
		            url:'/contents/delete_category',
		            type:'post',
		            data:{"data":id},
		            dataType:'json',
		            success:function(data){
		            	alert(data+" 메뉴가 삭제되었습니다.");
	            	}
	        })
    		}else{
    			alert('취소되었습니다.');
    		}
    	}
    }
   	function save(){
   		//var menu_title=add();//메뉴타이틀 가져오는것
   		var menu_id=get_category_id();//마지막 메뉴 id 가져오기
   		var sectionIndex=section_index();
   		var enumValue=$('#secret').val();
   		// var c=$('#3rd_category').prop('selectedIndex');
    	// var b=$('#2nd_category').prop('selectedIndex');
    	var a=$('#category').prop('selectedIndex');
    	//console.log(menu_id);
    	if(!a){
    		alert('저장전에 메뉴를 선택해주세요');
   			return false;
   		}
   		else{
   			$('#c_id').val(menu_id);
   			$('#sectionIndex').val(sectionIndex);
   			$('#enumValue').val(enumValue);
   			$('form').submit(function(){
   				$('#save_contents').attr('disabled', 'disabled');
   			});
   			return true;
   		}
   	}
   	//체인지 이벤트 발생할때 인덱스 값을 전달
	// function choice(){
	// 	index=$("#category").prop('selectedIndex');
	// 	if(index){
	// 		//alert(index);
	// 		window.location.href='/contents/check?index='+index;
	// 	}
	// };
	
	//만약에 메인카테고리에서 선택한 값이 하위메뉴를 가지고 있다면 데이터베이스에서 불러와서 보여줘라//일단 콘트롤러에서 처리
	//페이지 리로드시 메인옵션 선택값이 사라지기때문에 비동기적 처리
	// function choice(){
 //   		var index=$("#category").prop('selectedIndex');
 //       	$.ajax({
 //           url:'/contents/check',
 //           type:'post',
 //           data:{"index":index},
 //           dataType:'json',
 //           success:function(data){
 //             //alert(data);
 //             if(!data){
 //             	$('#2nd_category option').remove();
 //               //$('.second').remove();
 //               $('#2nd_category').append("<option class='second' selected disabled hidden>하위메뉴 없음</option>");
 //             }else{
 //               //alert(data);
 //               $('#2nd_category option').remove();
 //                 var i=0;
 //                 $('#2nd_category').append("<option selected disabled hidden>2nd_category</option>");
 //                 for(var name in data){
 //                   i++;
 //                  $('#2nd_category').append("<option class='second' value='"+i+"'>"+data[name]['title']+"</option>");
 //                  //alert("<option class="'2nd_category'" value='"+i+"'>"+data[name]+"</option>");
 //               }
 //               second_choice();
 //               }
 //           }
 //       })
 //   	};

   	//두번째 카테고리의 하위 카테고리가 있으면 데이터베이스 조회해서 옵션태그에 넣는 기능
   	// function choice2(event){
   	// 	//var a=$(event.target);
   	// 	//var b=caller.id;
   	// 	//alert(b.val());
   	// 	//alert(a);
   	// 	//$("#all_category").change(function(event) { alert(event.target.val()); });
   	// };
   	

   	// function second_choice(){
   	// 	if($("#2nd_category option:selected").val()){
   	// 		var title=$("#2nd_category option:selected").text();
   	// 		//alert(title);
   	// 		$.ajax({
		  //           url:'/contents/second_check',
		  //           type:'post',
		  //           data:{"title":title},
		  //           dataType:'json',
		  //           success:function(data){
		  //           	if(jQuery.isEmptyObject(data)){
		  //           		//alert('abd');
		  //           		$('#3rd_category option').remove();
		  //           		//$('.third').remove();
		  //           		str="<option class='third' selected disabled hidden>하위메뉴 없음</option>"
		  //               	$('#3rd_category').append(str);
		  //           	}else{
		  //           		$('#3rd_category option').remove();
		  //           		var i=0;
		  //           		$('#3rd_category').append("<option selected disabled hidden>3rd_category</option>");
		  //           		for(var name in data){
			 //                   i++;
			 //                   // console.log(data[name]['title']);
			 //                  $('#3rd_category').append("<option class='third' value='"+i+"'>"+data[name]['title']+"</option>");
		  //           		}
			 //            	// $.each(data,function(key,value){
			 //            	// 	i++;
			 //             //       // console.log(data[name]['title']);
			 //             //      $('#3rd_category').append("<option class='third' value='"+i+"'>"+value['title']+"</option>");
			 //             //  });
		  //           	}
		  //           	third_choice();
	   //          	}
	   //      })
   	// 	}
   	// };
   	// function third_choice(){
   	// 	if($("#3rd_category option:selected").val()){
   	// 		var title=$("#3rd_category option:selected").text();
   	// 		//alert(title);
   	// 		$.ajax({
		  //           url:'/contents/third_check',
		  //           type:'post',
		  //           data:{"title":title},
		  //           dataType:'json',
		  //           success:function(data){
		  //           	if(jQuery.isEmptyObject(data)){
		  //           		//alert('abd');
		  //           		$('#4th_category option').remove();
		  //           		//$('.third').remove();
		  //           		str="<option class='fourth' selected disabled hidden>하위메뉴 없음</option>"
		  //               	$('#4th_category').append(str);
		  //           	}else{
		  //           		$('#4th_category option').remove();
		  //           		var i=0;
		  //           		$('#4th_category').append("<option selected disabled hidden>4th_category</option>");
		  //           		for(var name in data){
			 //                   i++;
			 //                   // console.log(data[name]['title']);
			 //                  $('#4th_category').append("<option class='third' value='"+i+"'>"+data[name]['title']+"</option>");
		  //           		}
		  //           	}
	   //          	}
	   //      })
   	// 	}
   	// };
</script>
