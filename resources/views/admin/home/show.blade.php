<?php echo view('admin.header')->render() ?>
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                <a href="#" class="current">数据统计</a></div>
        </div>
        <div class="container-fluid">

<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>我的数据</title>
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
		    		<div class="user">adminadmin</div>
		      	<a href="javascript:;" class="login_out"><img src="{{ asset('images/legendImages/login_out.png') }}"/></a>
		    </div>
		</nav>
	</div>
	<!--我的数据-->
	<div class="container-fluid comm_box" style="margin:100px 15px;">
		<div class="clearfix" style="padding: 40px 0 20px;">
			<a class="today_data go_back" href="javascript:history.go(-1);">
				<img src="{{ asset('images/legendImages/goback.png') }}"/>我的数据
			</a>
		</div>
		<div class="row comm_row">
			<div class="col comm_col">
				<div class="comm_col_top clearfix">
					<p class="fl">充电棚数</p>
					<p class="fr">个</p>
				</div>
				<p class="mine_comm_col_mid color_grey" data-mine-info = "cdpCount"></p>
				<p class="mine_comm_col_foot"></p>
			</div>
		</div>
		<div class="row comm_row">
			<div class="col comm_col">
				<div class="comm_col_top clearfix">
					<p class="fl">充电口数</p>
					<p class="fr">个</p>
				</div>
				<p class="mine_comm_col_mid color_grey" data-mine-info = "cdkCount"></p>
				<p class="mine_comm_col_foot"></p>
			</div>
		</div>
		<div class="row comm_row">
			<div class="col comm_col">
				<div class="comm_col_top clearfix">
					<p class="fl">充电用户</p>
					<p class="fr">个</p>
				</div>
				<p class="mine_comm_col_mid color_pink" data-mine-info = "userCount"></p>
				<p class="mine_comm_col_foot"></p>
			</div>
		</div>
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
        		url:'/admin/home/show',
        		type:'post',
        		async:true,
			dataType:'json',
			cache: false,
			data:{},
        		success:function(res){
        			console.log(res);
        			if(res.data.code==200){
        				$.each(res.data,function(i,result){
						$('p[data-mine-info='+i+']').html(result);
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




        </div>
    </div>
<?php echo view('admin.footer')->render() ?>