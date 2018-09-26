<?php echo view('admin.header')->render() ?>
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                <a href="#" class="current">设备编辑</a></div>
            <h1>设备编辑</h1>
        </div>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span8">
                    <div class="widget-box">
                        <div class="widget-title"><span class="icon"> <i class="icon-align-justify"></i> </span>
                            <h5>编辑</h5>
                        </div>
                        <div class="widget-content">
                            <form id="myform" method="post" class="form-horizontal">
                                <input type="hidden" name="device_no" value="<?php echo Request::input('device_no') ?>"/>
                                <div class="control-group">
                                    <label class="control-label"><span class="text-error">*</span>地址 :</label>
                                    <div class="controls">
                                        <input name="address" value="<?php echo $data->address ?>" type="text" class="span11"/>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label"><span class="text-error">*</span> 纬度lat:</label>
                                    <div class="controls">
                                        <input name="lat" value="<?php echo $data->lat ?>" type="text" class="span11"/>
                                        <span class="help-block">请输入百度坐标系</span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label"><span class="text-error">*</span> 经度lng :</label>
                                    <div class="controls">
                                        <input name="lng" value="<?php echo $data->lng ?>" type="text" class="span11"/>
                                        <span class="help-block">请输入百度坐标系</span>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="button" id="mysubmit" class="btn btn-success">提交</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>

    $(function(){
        var myform = $("#myform");

        $("#mysubmit").click(function(){
            myform.submit();
        });

        myform.ajaxForm({
            dataType: 'json',
            //beforeSubmit : test,//ajax动画加载
            success: function(data)
            {
                if(ajax_check_res(data)){
                    //myalert('保存成功');
                }
            }
        });
    });

</script>
<?php echo view('admin.footer')->render() ?>

