			
<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>安心充</title>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/legendCss/bootstrapV4.min.css') }}"/>
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
	<!--今日数据-->
	<div class="container-fluid comm_box" style="margin-top: 100px;">
		<div class="clearfix" style="padding: 40px 0 20px;">
			<p class="fl today_data">今日数据</p>
			<p class="fl today">(2018/09/28)</p>
			<a class="fr see_more" href="http://anxinchong.vipcare.com/admin/home/dailyDetail">查看更多 <img style="margin-bottom: 4px;" src="{{ asset('images/legendImages/triangle.png') }}"/></a>
		</div>
		<div class="row comm_row">
			<div class="col comm_col">
				<div class="comm_col_top clearfix">
					<p class="fl">充电次数</p>
					<p class="fr">次</p>
				</div>
				<p class="comm_col_mid color_red" data-day-info = "charge_times"></p>
				<p class="comm_col_foot"><img class="up_down" src="{{ asset('images/legendImages/red_up.png') }}"/>较昨日增长了4.5%</p>
			</div>
			<div class="col comm_col">
				<div class="comm_col_top clearfix">
					<p class="fl">充电电量</p>
					<p class="fr">度</p>
				</div>
				<p class="comm_col_mid color_green" data-day-info = "electric_quantity"></p>
				<p class="comm_col_foot"><img class="up_down" src="{{ asset('images/legendImages/green_down.png') }}"/>较昨日下降了3.2%</p>
			</div>
		</div>
		<div class="row comm_row">
			<div class="col comm_col">
				<div class="comm_col_top clearfix">
					<p class="fl">充电时长</p>
					<p class="fr">小时</p>
				</div>
				<p class="comm_col_mid color_green" data-day-info = "charge_duration"></p>
				<p class="comm_col_foot"><img class="up_down" src="{{ asset('images/legendImages/green_down.png') }}"/>较昨日下降了2.6%</p>
			</div>
			<div class="col comm_col">
				<div class="comm_col_top clearfix">
					<p class="fl">充电费用</p>
					<p class="fr">元</p>
				</div>
				<p class="comm_col_mid color_red" data-day-info = "user_cost_amount"></p>
				<p class="comm_col_foot"><img class="up_down" src="{{ asset('images/legendImages/red_up.png') }}"/>较昨日增长了5.7%</p>
			</div>
		</div>
		<div class="row comm_row">
			<div class="col comm_col">
				<div class="comm_col_top clearfix">
					<p class="fl">充电用户</p>
					<p class="fr">位</p>
				</div>
				<p class="comm_col_mid color_red" data-day-info = "user_count"></p>
				<p class="comm_col_foot"><img class="up_down" src="{{ asset('images/legendImages/red_up.png') }}"/>较昨日增长了10.8%</p>
			</div>
			<div class="col comm_col">
				<div class="comm_col_top clearfix">
					<p class="fl">分成金额</p>
					<p class="fr">元</p>
				</div>
				<p class="comm_col_mid color_red" data-day-info = "shared_amount"></p>
				<p class="comm_col_foot"><img class="up_down" src="{{ asset('images/legendImages/red_up.png') }}"/>较昨日增长了16.3%</p>
			</div>
		</div>
	</div>
	<!--本月数据-->
	<div class="container-fluid comm_box" style="margin-bottom: 100px;">
		<div class="clearfix" style="padding: 40px 0 20px;">
			<p class="fl today_data">本月数据</p>
			<p class="fl today">(2018/09)</p>
			<a class="fr see_more" href="http://anxinchong.vipcare.com/admin/home/monthDetail">查看更多 <img style="margin-bottom: 4px;" src="{{ asset('images/legendImages/triangle.png') }}"/></a>
		</div>
		<div class="row comm_row">
			<div class="col comm_col">
				<div class="comm_col_top clearfix">
					<p class="fl">充电次数</p>
					<p class="fr">次</p>
				</div>
				<p class="comm_col_mid color_green" data-month-info = "charge_times"></p>
				<p class="comm_col_foot"><img class="up_down" src="{{ asset('images/legendImages/green_down.png') }}"/>较上月下降了3.5%</p>
			</div>
			<div class="col comm_col">
				<div class="comm_col_top clearfix">
					<p class="fl">充电电量</p>
					<p class="fr">度</p>
				</div>
				<p class="comm_col_mid color_red" data-month-info = "electric_quantity"></p>
				<p class="comm_col_foot"><img class="up_down" src="{{ asset('images/legendImages/red_up.png') }}"/>较上月增长了20.65%</p>
			</div>
		</div>
		<div class="row comm_row">
			<div class="col comm_col">
				<div class="comm_col_top clearfix">
					<p class="fl">充电时长</p>
					<p class="fr">小时</p>
				</div>
				<p class="comm_col_mid color_red" data-month-info = "charge_duration"></p>
				<p class="comm_col_foot"><img class="up_down" src="{{ asset('images/legendImages/red_up.png') }}"/>较上月增长了4.3%</p>
			</div>
			<div class="col comm_col">
				<div class="comm_col_top clearfix">
					<p class="fl">充电费用</p>
					<p class="fr">元</p>
				</div>
				<p class="comm_col_mid color_green" data-month-info = "user_cost_amount"></p>
				<p class="comm_col_foot"><img class="up_down" src="{{ asset('images/legendImages/green_down.png') }}"/>较上月下降了28.2%</p>
			</div>
		</div>
		<div class="row comm_row">
			<div class="col comm_col">
				<div class="comm_col_top clearfix">
					<p class="fl">充电用户</p>
					<p class="fr">位</p>
				</div>
				<p class="comm_col_mid color_red" data-month-info = "user_count"></p>
				<p class="comm_col_foot"><img class="up_down" src="{{ asset('images/legendImages/red_up.png') }}"/>较上月增长了12.5%</p>
			</div>
			<div class="col comm_col">
				<div class="comm_col_top clearfix">
					<p class="fl">分成金额</p>
					<p class="fr">元</p>
				</div>
				<p class="comm_col_mid color_red" data-month-info = "shared_amount"></p>
				<p class="comm_col_foot"><img class="up_down" src="{{ asset('images/legendImages/red_up.png') }}"/>较上月增长了9%</p>
			</div>
		</div>
	</div>
	
	<!--loading-->
	<div class="big_bg">
		<img class="load" src="{{ asset('images/legendImages/loading.gif') }}"/>
	</div>
</body>
<script src="{{ asset('js/legendJs/jquery-3.3.1.min.js') }}" type="text/javascript" charset="utf-8"></script>
<script src="{{ asset('js/legendJs/bootstrapV4.min.js') }}" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	$(function(){
		$('.big_bg').on('touchstart',e=>{
        		e.preventDefault()
        },false);
        $('.big_bg').css({'display':'block'});
        $.ajax({
        		url:'/admin/home/index',
        		type:'post',
        		async:true,
			dataType:'json',
			cache: false,
			data:{},
        		success:function(res){
        			console.log(res);
        			if(res.data.code==200){
        				$.each(res.data.today,function(i,result){
						$('p[data-day-info='+i+']').html(result);
					});
					$.each(res.data.month,function(i,result){
						$('p[data-month-info='+i+']').html(result);
					});
					$('.big_bg').css({'display':'none'});
        			}
        		},
        		error:function(res){
        			console.log(res)
        		}
        })

		
	})

	
	
</script>
</html>
