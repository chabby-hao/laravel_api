<?php echo view('admin.header')->render() ?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
            <a href="#" class="current">远程反向隧道</a></div>
        <h1>远程反向隧道</h1>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span8">
                <div class="widget-box">
                    <div class="widget-title"><span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>详情</h5>
                    </div>
                    <div class="widget-content">
                        <form id="myform" action="<?php ?>" method="post" class="form-horizontal">
                            <!--                                <input type="hidden" name="id" value=""/>-->
                            <div class="control-group">
                                <label class="control-label"><span class="text-error">*</span>设备号 :</label>
                                <div class="controls">
                                    <input id="device_no" name="device_no" value="" type="text" class="span11"/>
                                    <span class="help-block">例：100000001</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">用户名@url :</label>
                                <div class="controls">
                                    <input name="user_url" value="" type="text" class="span11"/>
                                    <span class="help-block">例：root@chongdianpeng.vipcare.com</span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">端口号 :</label>
                                <div class="controls">
                                    <input name="port_no" type="text" class="span11"/>
                                    <span class="help-block">一般为1-10的纯数字,例：7</span>
                                </div>
                            </div>

                            <div class="form-actions">
                                <input name="open" value="1" type="hidden">
                                <button type="submit" class="btn btn-success">开启反向隧道</button>
                                <button type="button" id="close" class="btn btn-warning">关闭所有反向隧道</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    $(function () {
        var myform = $("#myform");

        $("#mysubmit").click(function () {
            myform.submit();
        });

        myform.ajaxForm({
            dataType: 'json',
            //beforeSubmit : test,//ajax动画加载
            success: function (data) {
                if (ajax_check_res(data)) {
                    //myalert('保存成功');
                }
            }
        });

        $("#close").click(function () {
            $.ajax({
                url:'<?php echo URL::action('Admin\DeviceController@remoteTunnel') ?>',
                data:{
                    close:1,
                    device_no:deviceNo,
                },
                success: function (res) {
                    if(ajax_check_res(res)){

                    }
                }
            })
        })

    });

</script>
<?php echo view('admin.footer')->render() ?>

