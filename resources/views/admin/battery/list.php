<?php echo view('admin.header')->render() ?>
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                <a href="#" class="current">电池列表</a></div>
            <h1>电池列表</h1>
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
                                    <th>序号<?php $i = 0; ?></th>
                                    <th>电池Id</th>
                                    <th>电压等级</th>
                                    <th>设备号</th>
                                    <th>Imei</th>
                                    <th>当前所属</th>
                                    <th>电池状态</th>
                                    <th>输出状态</th>
                                    <th>创建时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php /** @var \App\Models\Battery $data */
                                foreach ($datas as $data) { ?>
                                    <tr class="gradeX">
                                        <td><?php echo ++$i; ?></td>
                                        <td><?php echo $data->battery_id ?></td>
                                        <td><?php echo $data->battery_level; ?></td>
                                        <td><?php echo $data->udid; ?></td>
                                        <td><?php echo $data->imei; ?></td>
                                        <td><?php echo $data->belong; ?></td>
                                        <td><?php echo \App\Services\BatteryService::getStateNameByBatteryId($data->battery_id); ?></td>
                                        <td><?php echo \App\Services\BatteryService::isBatteryOutputByBatteryId($data->battery_id) ? '开' : '关'; ?></td>
                                        <td><?php echo $data->created_at; ?></td>
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