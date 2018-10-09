<?php echo view('admin.header')->render() ?>
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                <a href="#" class="current">设备配置</a></div>
            <h1>设备配置</h1>
        </div>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span6">
                    <div class="widget-box">
                        <div class="widget-title"><span class="icon"> <i class="icon-align-justify"></i> </span>
                            <h5>详情</h5>
                        </div>
                        <div class="widget-content">
                            <form id="myform" method="post" class="form-horizontal">
                                <input type="hidden" name="device_no" value="<?php echo Request::input('device_no') ?>"/>
                                <div class="control-group">
                                    <label class="control-label"><span class="text-error">*</span>低谷端价位 :</label>
                                    <div class="controls">
                                        <input name="univalence1" value="<?php echo $data['univalence1'] ?>" type="text" />
                                        <span class="help-block">例：1.2</span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label"><span class="text-error">*</span>高锋端价位 :</label>
                                    <div class="controls">
                                        <input name="univalence2" value="<?php echo $data['univalence2'] ?>" type="text" />
                                        <span class="help-block">例：1.2</span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label"><span class="text-error">*</span>分成比例 :</label>
                                    <div class="controls">
                                        <select name="proportion" id="">
                                            <option value="0.3">0.3</option>
                                            <option value="0.5">0.5</option>
                                        </select>
                                        <span class="help-block">例：0.5</span>
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

