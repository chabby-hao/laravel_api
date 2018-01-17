<!DOCTYPE html>
<html lang="zh-cn" xmlns="http://www.w3.org/1999/html">
<head>
    <title>安心充后台管理系统</title><meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="/css/matrix-login.css" />
    <link rel="stylesheet" href="/css/matrix-style.css"/>
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!--<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>-->

</head>
<body>
<div id="loginbox">
    <form id="loginform" class="form-vertical" action="" method="post">
        <div class="control-group normal_text"> <h3><img src="/img/logo.png" alt="Logo" /></h3></div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_lg"><i class="icon-user"></i></span><input name="name" type="text" placeholder="Username" />
                </div>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_ly"><i class="icon-lock"></i></span><input name="pwd" type="password" placeholder="Password" />
                </div>
            </div>
        </div>
        <div class="form-actions">
            <span class="pull-left"><a href="#" class="flip-link btn btn-info hide" id="to-recover">Lost password?</a></span>
            <span class="pull-right"><button class="btn btn-success" >Login</button></span>
        </div>
    </form>
    <form id="recoverform" action="#" class="form-vertical">
        <p class="normal_text">Enter your e-mail address below and we will send you instructions how to recover a password.</p>

        <div class="controls">
            <div class="main_input_box">
                <span class="add-on bg_lo"><i class="icon-envelope"></i></span><input type="text" placeholder="E-mail address" />
            </div>
        </div>

        <div class="form-actions">
            <span class="pull-left"><a href="#" class="flip-link btn btn-success" id="to-login">&laquo; Back to login</a></span>
            <span class="pull-right"><a class="btn btn-info"/>Reecover</a></span>
        </div>
    </form>
</div>
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/matrix.login.js"></script>
</body>
</html>

<script src="/js/jquery-helper/jquery-helper.js"></script>
<script src="/js/jquery-form/jquery.form.js"></script>
<script>

    $(function () {

        var myform = $("#loginform");
        myform.ajaxForm({
            dataType:'json',
            success: function(res){
                if(ajax_check_res(res)){

                }
            }
        })
    })

</script>