<?php echo view('admin.header')->render() ?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
            <a href="#" class="current">添加电池</a></div>
        <h1>添加电池</h1>
    </div>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span8">
                <div class="widget-box">
                    <div class="widget-title"><span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>详情</h5>
                    </div>
                    <div class="widget-content">
                        <form id="myform" action="" method="post" class="form-horizontal">
                            <!--                                <input type="hidden" name="id" value=""/>-->
                            <div class="control-group">
                                <label class="control-label"><span class="text-error">*</span>电池ID :</label>
                                <div class="controls">
                                    <input name="battery_id" value="" type="text" class="span11"/>
                                    <span class="help-block">例：G494E392HFN934H93H</span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><span class="text-error">*</span>IMEI :</label>
                                <div class="controls">
                                    <input name="imei" type="text" class="span11"/>
                                    <span class="help-block">例：35755100440454949</span>
                                </div>
                            </div>


                            <div class="control-group">
                                <label class="control-label"><span class="text-error">*</span>电压等级(V) :</label>
                                <div class="controls">
                                    <input name="battery_level" type="text" class="span11"/>
                                    <span class="help-block">例：60</span>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" id="mysubmit" class="btn btn-success">保存</button>
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

