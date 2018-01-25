<?php echo view('admin.header')->render() ?>
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                <a href="#" class="current">升级文件列表</a></div>
            <h1>升级文件列表</h1>
        </div>
        <div class="container-fluid">
            <hr>
            <div></div>
            <div class="widget-content">
                <form class="form-search" id="myform" method="post" enctype="multipart/form-data">
                    <div class="control-group">
                        <div class="controls controls-row">

                            <div class="inline-block">
                                <label>升级文件</label>
                                <input class="file-uploading" name="bin_file" type="file"/>
                            </div>

                            <div class="inline-block">
                                <input class="btn btn-success" id="btn-search" type="submit" value="添加升级文件"/>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"><span class="icon"><i class="icon-th"></i></span>
                            <h5>列表</h5>
<!--                            <span class="pull-right"><a href="" class="btn btn-info">新增设备</a></span>-->
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>序列号</th>
                                    <th>文件名</th>
<!--                                    <th>操作</th>-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 0; ?>
                                <?php foreach ($slave_bin_files as $row) { ?>
                                    <tr class="gradeX">
                                        <td><?php echo ++$i; ?></td>
                                        <td><?php echo $row; ?></td>
<!--                                        <td>-->
<!--                                            <a href="javascript:;" class="btn btn-danger del">删除</a>-->
<!--                                        </td>-->
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
<!--                        <div class="pager">-->
<!--                            {{$pageNav}}-->
<!--                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo view('admin.footer')->render() ?>
<script type="text/javascript" src="/js/bootstrap-filestyle.min.js"> </script>
<script>
    $(":file").filestyle({classButton: "btn btn-info"});

    $("#myform").ajaxForm({
        dataType: 'json',
        //beforeSubmit : test,//ajax动画加载
        success: function(data)
        {
            if(ajax_check_res(data)){
                //myalert('保存成功');
            }
        }
    })


</script>
