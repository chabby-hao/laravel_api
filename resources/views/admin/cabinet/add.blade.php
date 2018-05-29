<?php echo view('admin.header')->render() ?>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
            <a href="#" class="current">添加换电柜</a></div>
        <h1>添加换电柜</h1>
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
                                <label class="control-label"><span class="text-error">*</span>柜子编号 :</label>
                                <div class="controls">
                                    <input name="cabinet_no" value="" type="text" class="span11"/>
                                    <span class="help-block">例：10000000</span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><span class="text-error">*</span>柜门数量 :</label>
                                <div class="controls">
                                    <input name="cabinet_num" type="text" class="span11"/>
                                    <span class="help-block">例：7</span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"><span class="text-error">*</span>安装地址 :</label>
                                <div class="controls">
                                    <input name="address" type="text" class="span11"/>
                                    <span class="help-block">例：万和家园110号充电棚</span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><span class="text-error">*</span>经度(lng) :</label>
                                <div class="controls">
                                    <input name="lng" type="text" class="span11"/>
                                    <span class="help-block">例：121.4141178131</span>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label"><span class="text-error">*</span>纬度(lat) :</label>
                                <div class="controls">
                                    <input name="lat" type="text" class="span11"/>
                                    <span class="help-block">例：31.1704363853</span>
                                </div>
                            </div>

                            <!--                                <div class="control-group">-->
                            <!--                                    <label for="checkboxes" class="control-label">应用平台 :</label>-->
                            <!--                                    <div class="controls">-->
                            <!--                                        <div data-toggle="buttons-radio" class="btn-group myradio" dataname="app_os">-->
                            <!--                                                <button value="--><?php // ?><!--" class="btn" type="button">44</button>-->
                            <!--                                                <button value="--><?php // ?><!--" class="btn" type="button">44</button>-->
                            <!--                                                <button value="--><?php // ?><!--" class="btn" type="button">44</button>-->
                            <!--                                        </div>-->
                            <!--                                        <span class="help-block">只能选择一种操作系统，请求时将进行校验，系统平台错误时请求无效</span>-->
                            <!--                                    </div>-->
                            <!--                                </div>-->

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

