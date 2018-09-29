<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>日明细</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/legendCss/bootstrapV4.min.css') }}"/>
	<link href="http://bi.vipcare.com/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/legendCss/index.css') }}"/>
</head>
<body>
	<div class="container-fluid" style="position: fixed;top: 0;left: 0;z-index: 2;background: #fff;">
		<nav class="navbar justify-content-between myNav">
			<a class="navbar-brand" href="#">
				<img src="{{ asset('images/legendImages/axc_logo.png') }}"/>
			</a>		  
		    <div class="form-inline">
		    		<a href="http://anxinchong.vipcare.com/admin/home/show" class="user">adminadmin</a>
		      	<a href="javascript:;" class="login_out"><img src="{{ asset('images/legendImages/login_out.png') }}"/></a>
		    </div>
		</nav>
	</div>
	<!--我的数据-->
	<div class="container-fluid comm_box" style="margin:100px 15px;overflow: hidden;">
		<div class="clearfix" style="padding: 40px 0 20px;">
			<a class="today_data go_back" href="javascript:history.go(-1);">
				<img src="{{ asset('images/legendImages/goback.png') }}"/>日明细
			</a>
			<div class="sel_box">
				<select id="scree" name="" class="scree shadow">
					<option value="">筛查</option>
					
				</select>
				<input id="date" name="date" value="yy-mm-dd" type="text" class="date_pick shadow" readonly="true"/>
			</div>
		</div>
		<!--表格-->
		<div class="scroll">
			<table class="table" id="myTabel">
				<thead>
					<tr>
						<th>日期</th>
						<th>充电次数（次）</th>
						<th>充电电量（度）</th>
						<th>充电时长（小时）</th>
						<th>充电费用（元）</th>
						<th>充电用户（位）</th>
						<th>分成金额（元）</th>
					</tr>
				</thead>
				<tbody class="myTbody">
					<!--<tr>
						<td>2018/09/06</td>
						<td>23</td>					
						<td>23</td>					
						<td>34</td>					
						<td>45.00</td>					
						<td>33</td>					
						<td>12.56</td>					
					</tr>				-->
				</tbody>
			</table>
		</div>
		<!--分页-->
		<div class="page_box">
			<button class="prev_page fl"><img src="{{ asset('images/legendImages/triangle.png') }}"/></button>
			<span class="page_num">1</span>
			<button class="next_page fr"><img src="{{ asset('images/legendImages/triangle.png') }}"/></button>
		</div>
	</div>
	<div class="errortips" style="display: none;"></div>
	<!--loading-->
	<div class="big_bg">
		<img class="load" src="{{ asset('images/legendImages/loading.gif') }}"/>
	</div>
</body>
<script src="{{ asset('js/legendJs/jquery-3.3.1.min.js') }}" type="text/javascript" charset="utf-8"></script>
<script src="{{ asset('js/legendJs/bootstrapV4.min.js') }}" type="text/javascript" charset="utf-8"></script>
<script src="http://bi.vipcare.com/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="http://bi.vipcare.com/vendor/bootstrap-datepicker/locales/bootstrap-datepicker.zh-CN.min.js"></script>


<script type="text/javascript">
	$(function() {
		var height = $(window).height();
   	 	$('.big_bg').height(height);
		
		$('#date').datepicker({
            format: "yyyy-mm-dd",
            language: "zh-CN",
//          startDate: '+0d',
            startView: 2,
            todayHighlight: true,
            autoclose:true,
        }).on('changeDate',function(ev){
            var time=$("#date").val();
            console.log(time);
			$('.big_bg').css({'display':'block'});
	         $.ajax({
	      		url:'/admin/home/detailData',
	      		type:'post',
	      		async:true,
				dataType:'json',
				cache: false,
				data:{
					date:time,						
				},
	      		success:function(res){
	      			console.log(res);
	      			if(res.code==200){
	      				$('.myTbody').html('');
	      				//列表
	      				var innerHtml='';
				        $.each(data.list, function(i,result) {
				        		innerHtml+='<tr><td>'+result.date+'</td><td>'+result.charge_times+'</td><td>'+result.electric_quantity+'</td><td>'+result.charge_duration+'</td><td>'+result.user_cost_amount+'</td><td>'+result.user_count+'</td><td>'+result.shared_amount+'</td></tr>';
				        });
				        $('.myTbody').append(innerHtml);
				        $('.big_bg').css({'display':'none'});
	      			}
	      		},
	      		error:function(res){
	      			console.log(res)
	      		}
		     });
        });   
        //页面加载时数据渲染
        var lastPage;    //最后一页
        $.ajax({
      		url:'/admin/home/detailData',      //表格数据
      		type:'post',
      		async:true,
			dataType:'json',
			cache: false,
			data:{
									
			},
      		success:function(res){
      			console.log(res);
      			if(res.code==200){
      				$('.myTbody').html('');
      				//列表
      				var item='';
			        $.each(res.data.list, function(i,result) {
			        		item+='<tr><td>'+result.date+'</td><td>'+result.charge_times+'</td><td>'+result.electric_quantity+'</td><td>'+result.charge_duration+'</td><td>'+result.user_cost_amount+'</td><td>'+result.user_count+'</td><td>'+result.shared_amount+'</td></tr>';
			        });
			        $('.myTbody').append(item);
			        lastPage=res.data.lastPage;
			        $('.big_bg').css({'display':'none'});
      			}
      		},
      		error:function(res){
      			console.log(res)
      		}
	     });
	     
	      $.ajax({
      		url:'/admin/home/deviceNoList',      //下拉框数据
      		type:'post',
      		async:true,
			dataType:'json',
			cache: false,
			data:{
									
			},
      		success:function(res2){
      			console.log(res);
      			if(res.code==200){
      				$('#scree').html('');
      				//列表
      				var item2='';
			        $.each(res2.data.list, function(i,result) {
			        		item2+='<option value="'+result+'">'+result+'</option>';
			        });
			        $('#scree').append(item2);
			        $('.big_bg').css({'display':'none'});
      			}
      		},
      		error:function(res){
      			console.log(res)
      		}
	     });
   
       //下拉框选择事件
		$('#scree').on('change',function(){
			var changeValue=$("option:selected",this).val();
			if(changeValue!==''){
		       console.log(changeValue);
		       $('.big_bg').css({'display':'block'});
	             $.ajax({
		      		url:'/admin/home/detailData',
		      		type:'post',
		      		async:true,
					dataType:'json',
					cache: false,
					data:{
						device_no:changeValue,						
					},
		      		success:function(res){
		      			console.log(res);
		      			if(res.code==200){
		      				$('.myTbody').html('');
		      				//列表
		      				var innerHtml='';
					        $.each(data.list, function(i,result) {
					        		innerHtml+='<tr><td>'+result.date+'</td><td>'+result.charge_times+'</td><td>'+result.electric_quantity+'</td><td>'+result.charge_duration+'</td><td>'+result.user_cost_amount+'</td><td>'+result.user_count+'</td><td>'+result.shared_amount+'</td></tr>';
					        });
					        $('.myTbody').append(innerHtml);
					        $('.big_bg').css({'display':'none'});
		      			}
		      		},
		      		error:function(res){
		      			console.log(res)
		      		}
			     })
		    }
		});
		
		//分页
		var page=function(pageNum){
			$('.big_bg').css({'display':'block'});
			$.ajax({
	      		url:'/admin/home/detailData',
	      		type:'post',
	      		async:true,
				dataType:'json',
				cache: false,
				data:{
					page:pageNum,						
				},
	      		success:function(res){
	      			console.log(res);
	      			if(res.code==200){
	      				$('.myTbody').html('');
	      				//列表
	      				var innerHtml='';
				        $.each(data.list, function(i,result) {
				        		innerHtml+='<tr><td>'+result.date+'</td><td>'+result.charge_times+'</td><td>'+result.electric_quantity+'</td><td>'+result.charge_duration+'</td><td>'+result.user_cost_amount+'</td><td>'+result.user_count+'</td><td>'+result.shared_amount+'</td></tr>';
				        });
				        $('.myTbody').append(innerHtml);
				        $('.big_bg').css({'display':'none'});
	      			}
	      		},
	      		error:function(res){
	      			console.log(res)
	      		}
		     })
		};
		
		var pageNum=$('.page_num').html();
		$('.prev_page').on('click',function(){
			if(pageNum<=1){
				pageNum==1;
				$('.page_num').html(pageNum);
				$('.errortips').html('当前已是第一页');
	        		$('.errortips').show().delay(2000).fadeOut();
			}else{
				pageNum--;
				$('.page_num').html(pageNum);
				return page;
				page(pageNum);
			}
		});
		$('.next_page').on('click',function(){
			if(pageNum<lastPage){
				pageNum++;
				$('.page_num').html(pageNum);
				return page;
				page(pageNum);
			}else if(pageNum==lastPage){
				pageNum==lastPage;
				$('.page_num').html(pageNum);
				$('.errortips').html('当前已是最后一页');
	        		$('.errortips').show().delay(2000).fadeOut();
			}
		})

	});
	
	
	
</script>
</html>



