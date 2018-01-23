<?php echo view('admin.header')->render() ?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
            <a href="#" class="current">添加福利卡</a></div>
        <h1>添加福利卡</h1>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span8">
                <div class="widget-box">
                    <div class="widget-title"><span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>详情</h5>
                    </div>
                    <div class="widget-content">
                        <form id="myform" action="<?php ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
                            <!--                                <input type="hidden" name="id" value=""/>-->

                            <div class="control-group">
                                <label class="control-label"><span class="text-error">*</span>设备号（棚号） :</label>
                                <div class="controls">
                                    <?php foreach (\App\Models\DeviceInfo::getAllDeviceNo() as $deviceNo) { ?>
                                        <label>
                                            <input type="checkbox" name="device_no[]" value="<?php echo $deviceNo; ?>"/>
                                            <?php echo $deviceNo; ?></label>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><span class="text-error">*</span>卡名 :</label>
                                <div class="controls">
                                    <input name="card_name" value="" type="text" class="span11"/>
                                    <span class="help-block">例：安骑测试卡</span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><span class="text-error">*</span>公司名 :</label>
                                <div class="controls">
                                    <input name="company" value="" type="text" class="span11"/>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><span class="text-error">*</span>手机号(excel文件):</label>
                                <div class="controls">
                                    <input name="phones" type="file" class="file-uploading"/>
                                    <span class="help-block">例：<a class="text-info" href="/demo/导入模板.xls">导入模板</a></span>
                                </div>
                            </div>


                            <div class="form-actions">
                                <button type="button" id="mysubmit" class="btn btn-success">添加</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo view('admin.footer')->render() ?>
<script type="text/javascript" src="/js/bootstrap-filestyle.min.js"></script>
<script>
    $(":file").filestyle();

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
    });

</script>

