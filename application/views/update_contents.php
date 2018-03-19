<!-- <?php print_r($data); ?> -->
<div id="all_category">
	<?php 
		foreach ($data['list'] as $key => $list) {
			$selectedList=$data['category'][$key];
			switch($key){
				case 0:
				$labelName='1st 카테고리';
				$selectId_Name='category';
				$divId_Name='first';
				break;
				case 1:
				$labelName='2nd 카테고리';
				$selectId_Name='2nd_category';
				$divId_Name='second';
				break;
				case 2:
				$labelName='3rd 카테고리';
				$selectId_Name='3rd_category';
				$divId_Name='third';
				break;
				case 3:
				$labelName='4th 카테고리';
				$selectId_Name='4th_category';
				$divId_Name='fourth';
				break;
			}
			?>
	<div class="form-group input-group" id="<?=$divId_Name?>">
	<label class="input-group-text label"><?=$labelName?></label>
		<select class="form-control" id="<?=$selectId_Name?>" name="<?=$selectId_Name?>" value="<?=$key+1?>">
			<?php 
				foreach ($list as $keys => $values) {
					if($selectedList==$values){
					?>
			<option selected value="<?=$data['id'][$key][$keys]?>"><?=$values?></option>	
				<?php
					}else{
					?>
			<option value="<?=$data['id'][$key][$keys]?>"><?=$values?></option>
				<?php	
				}
				}?>
		</select>
	</div>
	<?php	
		}
	?>
</div>
  <?php echo validation_errors(); ?>
<form action="/contents/update_contents/<?=$data['contents']['id']?>?returnURL=<?=$data['returnURL']?>&section=<?=$data['section']?>" method="post" enctype="multipart/form-data">
	  <div class="form-group input-group">
	    <label class="input-group-text">제목</label>
	    <select id="secret" name="secret">
	    	<?php 
	    		if($data['contents']['division']=='공개'){
	    		?>
	    	<option selected value='공개'>공개</option>
	    	<option value='비공개'>비공개</option>
	    		<?php
	    		}else{
	    		?>
	    	<option value='공개'>공개</option>
	    	<option selected value='비공개'>비공개</option>	
	    		<?php
	    		}
	    	?>
	    </select>
	    <input type="text" class="form-control" name="title" id="exampleFormControlInput1" placeholder="title" value="<?=$data['contents']['title'];?>">
	  </div>
	  <div class="form-group">
	    <label class="input-group-text">내용</label>
	    <textarea class="form-control ckfinder" id="editor" name="description" value="<?=$data['contents']['id'];?>"><?=$data['contents']['description'];?></textarea>
	  </div>
	  <button type="submit" class="btn btn-primary" id="save_contents"onclick="save()">저장</button>
	  <input type="hidden" id="p_id" name="p_id">
	  <input type="hidden" id="enumValue" name="enumValue">
</form>
<script src="/static/lib/ckeditor/ckeditor.js"></script>
<!-- <script src="/static/lib/ckfinder/ckfinder.js"></script> -->
<script>
	//텍스트에어리어에 ck에디터 적용 및 업로드시 요청 페이지 설정
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
	            				var htmlStr='<div class="form-group input-group" id="second"><label class="input-group-text" for="exampleFormControlSelect2">2nd 카테고리</label><select class="form-control" id="2nd_category" name="2nd_category" value="2"><option selected disabled hidden value="0">2nd category</option>'; 
	            			break;
	            			case '2':
	            				$('#third').remove();
	            				$('#fourth').remove();
	            				var htmlStr='<div class="form-group input-group" id="third"><label class="input-group-text">3rd 카테고리</label><select class="form-control" id="3rd_category" name="3rd_category" value="3"><option selected disabled hidden value="0">3rd category</option>';
           					break;
           					case '3':
           						$('#fourth').remove();
           						var htmlStr='<div class="form-group input-group" id="fourth"><label class="input-group-text">4th 카테고리</label><select class="form-control" id="4th_category" name="4th_category" value="4"><option selected disabled hidden value="0">4th category</option>';
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
	    var a=$('#category').find("option:selected").val();
	    var b=$('#2nd_category').find("option:selected").val();
	    var c=$('#3rd_category').find("option:selected").val();
	    var d=$('#4th_category').find("option:selected").val();
    	if(d&&d!=="0"){
    		var p_id = d;
    	}else if(c&&c!=="0"){
    		var p_id = c;
    	}else if(b&&b!=="0"){
    		var p_id = b;
    	 }else{
    	 	var p_id = a;
    	 }
    	return p_id;
    };
    //하위메뉴 데이터베이스에 추가 기능
   	function save(){
   		var p_id=add();
   		var enumValue=$('#secret').val();
		$('#p_id').val(p_id);
		$('#enumValue').val(enumValue);
		$('form').submit();
		$('form').submit(function(){
   				$('#save_contents').attr('disabled', 'disabled');
   			});
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
