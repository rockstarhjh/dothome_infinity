
		<!-- Modal -->
		<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		  	<form name="submitNickname" id="submitNickname" method="post">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="ModalLabel">닉네임 변경</h5>
			        <button type="button" id="close" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			      	<input type="text" class="form-control" name="nickName" id="nickName" aria-describedby="emailHelp" placeholder="닉네임 입력">
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-primary" id="changeNicknameBtn">변경</button>
			      </div>
			    </div>
			</form>
		  </div>
		</div>
			<footer class="footer">
		      <div class="container">
		        <p class="text-center">&copy 2018. rockstar all rights reserved <span></span><a href="#" id="tothetop"><i class="fas fa-arrow-circle-up"></i></a></p>
		      </div>
		    </footer>
	</div>
		
		<script type="text/javascript">
			$(document).ready(function(){
				imgcssremove();
				sidebar_default();
				$(document).click(function(e){
        			var container=$('#user_table .dropdown-menu');
        			if(container.has(e.target).length===0){
        				container.remove();
        				if($('#user_info').hasClass('toggled')){
        					$("#user_info").toggleClass("toggled");	
        				}
        			}
				});
				$("#changeNicknameBtn").click(function(){
					var queryString = $("#nickName").val();
					var r=confirm("정말로 변경하시겠습니까?");
			    	if(r==true){
			    		$.ajax({
			    			url:'/auth/changeNickname',
				            type:'post',
				            data:{"data":queryString},
				            dataType:'json',
				            success:function(data){
				            	// $('body').removeClass('modal-open');
				            	// $("#userModal").removeClass('show').css('display','none').attr('aria-hidden',true);
				            	$("#close").trigger('click');
				            	alert("닉네임 "+data['nickname']+"로 변경되었습니다.");
				            	// window.location.href="/";
		            		}
			    		})
			    	}else{
			    		alert("취소되었습니다.")
			    		return false;
		    		}
				});
			});
			function imgcssremove() {
				$('.img-fluid').css('height','').css('width','');
			}
			function sidebar_default(){
				$('#wrapper').removeClass('toggled');
				var windowWhidth=$(window).width();
				if(windowWhidth>576){
					$("#wrapper").attr('class','toggled');
				}
			}
			function comment(argument,table_index) {
				var a='#des'+argument;
				var b=a+' .comment_box';
				var c=a+' .jumbotron';
				$(b).toggleClass("toggled");
				if($(b).hasClass('toggled')){
					if($(c).length){
						return true;
					}else{
						get_comment(argument,table_index);
						get_userNickname(argument);
					}
				}else{
					return false;
				}
			}
			function get_comment(argument,table_index) {
				var a='#des'+argument;
				var b=a+' .comment_box';
				var c=a+' .jumbotron';
				data={'contents_id':argument, 'table_index':table_index};
				$.ajax({
		    			url:'/contents/get_comment',
			            type:'post',
			            data:{"data":data},
			            dataType:'json',
			            success:function(data){
			            	$(c).remove();
			            	var htmlStr='<div class="jumbotron">';
			            	$.each(data,function(i,e){
			            		htmlStr=htmlStr+'<p><span class="font-weight-bold">';
			            		htmlStr=htmlStr+e.nickname+'</span>';
			            		htmlStr=htmlStr+'<input type="hidden" value="'+e.id+'"></input>';
			            		htmlStr=htmlStr+' : '+e.comment+'</p><hr class="lead">';
			            	});
			            	htmlStr=htmlStr+'</div>';
			            	$(b).prepend(htmlStr);
	            		}
		    		})
			}
			function get_userNickname(argument) {
				$.ajax({
		    			url:'/auth/get_userNickname',
			            type:'post',
			            data:{"data":true},
			            dataType:'json',
			            success:function(data){
			            	if(data){
			            	compare_user(argument,data);
			            	}
	            		}
		    		})
			}
			function compare_user(argument,nickname) {
				var a='#des'+argument;
				var b=a+' .comment_box .jumbotron p';
				$(b).each(function(i,e){
					// console.log($(this).text());
					if($(this).find('span').text()==nickname){
						var htmlStr='<button type="button" class="btn btn-primary btn-sm" onclick="delete_comment(this)">삭제</button>';
						$(this).append(htmlStr);
					}
				})
			}
			function delete_comment(elem) {
				var comment_id=jQuery(elem).siblings('input').val();
				$.ajax({
		    			url:'/contents/delete_comment',
			            type:'post',
			            data:{"data":comment_id},
			            dataType:'json',
			            success:function(data){
			            	jQuery(elem).parent().next().remove();
			            	jQuery(elem).parent().remove();
			            	var a='#des'+data['p_id']+' .jumbotron p';
			            	if(!$(a).length){
			            		var a='#des'+data['p_id']+' .jumbotron';
			            		$(a).remove();
			            	}
	            		}
		    		})
			}
			function comment_submit(argument,table_index){
				var a='#des'+argument;
				var b=a+' .comment_submit';
				var c=a+' .jumbotron';
				var d=a+' .comment_box';
				var comment=$(b).val();
				data={'contents_id':argument, 'comment':comment, 'table_index':table_index};
				var r=confirm("댓글 작성하시겠습니까?");
		    	if(r==true){
		    		$.ajax({
		    			url:'/contents/comment',
			            type:'post',
			            data:{"data":data},
			            dataType:'json',
			            success:function(data){
			            	alert("댓글작성 되었습니다.");
			            	var htmlStr='<p><span class="font-weight-bold">';
			            	htmlStr=htmlStr+data['nickname']+'</span>';
			            	htmlStr=htmlStr+'<input type="hidden" value="'+data['id']+'"></input>';
		            		htmlStr=htmlStr+' : '+data['comment']+'<button type="button" class="btn btn-primary btn-sm" onclick="delete_comment(this)">삭제</button></p><hr class="lead">';
		            		if($(c).length){
		            			$(c).prepend(htmlStr);
		            		}else{
		            			htmlStr='<div class="jumbotron">'+htmlStr+'</div>';
		            			$(d).prepend(htmlStr);
		            		}
	            		}
		    		})
		    	}else{
		    		alert("취소되었습니다.")
		    		return false;
		    	}
			}
			
		</script>
		<script type="text/javascript">
		    function get_imgName(id){
		        var a='#des'+id;
		        var b=a+' img';
		        var fileName=[];
		        var i=0;
		        $(b).each(function(){
		            var c= $(this).attr('src');
		            // var d = c.substring(c.lastIndexOf('/')+1);
		            var htmlStr='<input type="hidden" class="fileName'+i+'" name="fileName[]">'; 
		            fileName.push(c);
		            $('#delete').append(htmlStr);
		            i++;
		        })
		        for(i=0;i<fileName.length;i++){
		            var className=".fileName"+i;
		            $(className).val(fileName[i]);
		        }
		    }
		    function delchk(id){
		        get_imgName(id);
		        return confirm("삭제 하시겠습니까?");
		    }
		    function upchk(){
		        return confirm("수정 하시겠습니까?");
		    }
		    function delete_userdata() {
		    	var r=confirm("정말로 탈퇴하시겠습니까?");
		    	if(r==true){
		    		$.ajax({
		    			url:'/auth/leave',
			            type:'post',
			            data:{"data":true},
			            dataType:'json',
			            success:function(data){
			            	alert(data+" 회원님 그동안 감사했습니다. \n정상적으로 탈퇴되었습니다.");
			            	window.location.href="/";
	            	}
		    		})
		    	}else{
		    		alert("취소되었습니다.")
		    		return false;
		    	}
		    }
		    function userdata() {
				$("#user_info").toggleClass("toggled");
		    	$.ajax({
		    			url:'/auth/get_userdata',
			            type:'post',
			            data:{"data":true},
			            dataType:'json',
			            success:function(data){
			            	var htmlStr='<div class="dropdown-menu dropdown-menu-right show" aria-labelledby="user_table"><table class="table table-bordered text-center"><thead><tr><th scope="col" colspan="2">회원정보<button type="button" class="btn btn-primary btn-sm" id="userUpdate" data-toggle="modal" data-target="#userModal">변경</button></th></tr></thead><tbody><tr><th scope="row">닉네임</th><td>'+data['nickname']+'</td></tr><tr><th scope="row">회원등급</th><td>'+data['role_name']['role_name']+'</td></tr><th scope="row">가입날짜</th><td>'+data['date']+'</td></tr></tbody></table></div>';
			            	if($("#user_info").hasClass("toggled")){
			            		$("#user_info").after(htmlStr);
			            	}else{
			            		$("#user_info").nextAll().remove();
			            	}
	            	}
		    		})
		    	return true;
		    }
		</script>
		<!-- Optional JavaScript -->
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>

	    <script type="text/javascript">
	    	 $("#menu-toggle").click(function(e) {
		        e.preventDefault();
		        $("#wrapper").toggleClass("toggled");
		    });
	    </script>
	    <!-- <script type="text/javascript">
			$(document).ready(function() {
				var biggestHeight = 0;
				// Loop through elements children to find & set the biggest height
				$("#sidebar-wrapper *").each(function(){
				 // If this elements height is bigger than the biggestHeight
				 if ($(this).height() > biggestHeight ) {
				   // Set the biggestHeight to this Height
				   biggestHeight = $(this).height();
				 }
				});
				// Set the container height
				$("#sidebar-wrapper").height(biggestHeight);
			});
		</script> -->


		
	</body>
</html>