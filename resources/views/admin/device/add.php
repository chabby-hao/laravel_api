<?php echo view('admin.header')->render() ?>
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                <a href="#" class="current">添加设备</a></div>
            <h1>添加设备</h1>
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
                                <input type="hidden" name="id" value=""/>
                                <div class="control-group">
                                    <label class="control-label"><span class="text-error">*</span>应用名 :</label>
                                    <div class="controls">
                                        <input name="app_name" value="<?php  ?>" type="text" class="span11"/>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label for="checkboxes" class="control-label">应用平台 :</label>
                                    <div class="controls">
                                        <div data-toggle="buttons-radio" class="btn-group myradio" dataname="app_os">
                                                <button value="<?php  ?>" class="btn" type="button">44</button>
                                                <button value="<?php  ?>" class="btn" type="button">44</button>
                                                <button value="<?php  ?>" class="btn" type="button">44</button>
                                        </div>
                                        <span class="help-block">只能选择一种操作系统，请求时将进行校验，系统平台错误时请求无效</span>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label">应用下载地址 :</label>
                                    <div class="controls">
                                        <input name="app_down_url" value="<?php  ?>" type="text" class="span11"/>
                                        <span class="help-block">请填写可下载应用的地址以便后台审核</span>
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
        function formComplete()
        {
            $(".myradio").each(function(){
                var __this__ = $(this);
                var dataname = __this__.attr("dataname");
                var datavalue = __this__.find(".active").val();
                var input = $('<input class="tmp" type="hidden" name="' + dataname + '" value="' + datavalue + '" />');
                myform.append(input);
                console.log(input);
            });
        }

        $("#mysubmit").click(function(){
            formComplete();
            myform.submit();
        });

        myform.ajaxForm({
            dataType: 'json',
            //beforeSubmit : test,//ajax动画加载
            success: function(data)
            {
                if(ajax_check_res(data)){
                    myalert('保存成功');
                }
            }
        });
    });

</script>
<?php echo view('admin.footer')->render() ?>

