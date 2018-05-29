<?php echo view('admin.header')->render() ?>
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                <a href="#" class="current">换电列表</a></div>
            <h1>换电列表</h1>
        </div>
        <div class="container-fluid">
            <hr>
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"><span class="icon"><i class="icon-th"></i></span>
                            <h5>列表</h5>
                            <!--                            <span class="pull-right"><a href="" class="btn btn-info"></a></span>-->
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>任务Id</th>
                                    <th>手机号</th>
                                    <th>换电状态</th>
                                    <th>换电完成步骤</th>
                                    <th>柜子</th>
<!--                                    <th>第一个柜门</th>-->
                                    <th>放入电池</th>
<!--                                    <th>第二个柜门</th>-->
                                    <th>取出电池</th>
                                    <th>创建时间</th>
                                    <th>支付费用</th>
<!--                                    <th>操作</th>-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php /** @var \App\Models\ReplaceTasks $data */
                                foreach ($datas as $data) { ?>
                                    <tr class="gradeX">
                                        <td><?php echo $data->id ?></td>
                                        <td><?php echo $data->phone ?></td>
                                        <td><?php echo \App\Models\ReplaceTasks::getStateMap($data->state); ?></td>
                                        <td><?php echo \App\Models\ReplaceTasks::getStepMap($data->step); ?></td>
                                        <td><?php echo $data->cabinet_no; ?></td>
                                        <td><?php echo $data->battery_id1; ?></td>
                                        <td><?php echo $data->battery_id2 ?></td>
                                        <td><?php echo $data->created_at->toDateTimeString(); ?></td>
                                        <td><?php echo $data->actual_cost; ?></td>
<!--                                        <td>-->
<!--                                            <a href="" class="btn btn-info">设置</a>-->
<!--                                            <!-- <a href="javascript:;" class="btn btn-danger del">删除</a>-->
<!--                                        </td>-->
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="pager">
                            <?php echo $page_nav; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo view('admin.footer')->render() ?>