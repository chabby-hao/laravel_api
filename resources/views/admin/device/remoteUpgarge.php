<?php echo view('admin.header')->render() ?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
            <a href="#" class="current">远程升级</a></div>
        <h1>远程升级</h1>
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

                            <div class="control-group">
                                <label class="control-label"><span class="text-error">*</span>设备号 :</label>
                                <div class="controls">
                                    <input name="device_no" type="text" class="span11"/>
                                    <span class="help-block">例：10000000</span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><span>从机升级文件</span></label>
                                <div class="controls">
                                    <select class="span11" name="slave_file">
                                        <?php foreach ($slave_bin_files as $file){ ?>
                                            <option value="<?php echo $file ?>"><?php echo $file ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                            </div>

                            <div class="form-actions control-group">
                                <label class="controls-label">
                                    <button type="button" id="mysubmit" class="btn btn-success">升级</button>
                                </label>
                                <div id="success" class="alert alert-success hide">升级成功</div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php echo view('admin.footer')->render() ?>

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
                    $("#success").show();
                    //myalert('保存成功');
                }
            }
        });
    });

</script>
