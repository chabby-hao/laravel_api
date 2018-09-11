<?php echo view('admin.header')->render() ?>
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                <a href="#" class="current">设备列表</a></div>
            <h1>设备列表</h1>
        </div>
        <div class="container-fluid">
            <hr>
            <div></div>
            <div class="widget-content">
                <form class="form-search">
                    <div class="control-group">
                        <div class="controls controls-row">
                            <div class="inline-block">
                                <label>设备号</label>
                                <input value="<?php echo Request::input('device_no') ?>" name="device_no" type="text"/>
                            </div>

                            <div class="inline-block">
                                <input class="btn btn-success" id="btn-search" type="submit" value="确定"/>
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
                            <span class="pull-right"><a href="<?php echo URL::action('Admin\DeviceController@add') ?>" class="btn btn-info">新增设备</a></span>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>设备号</th>
                                    <!--<th>端口号</th>
                                    <th>二维码</th>-->
                                    <th>安装地址</th>
                                    <th>状态</th>
                                    <th>端口详情</th>
                                    <th>统计</th>
                                    <th>配置</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php /** @var \App\Models\DeviceInfo $row */
                                foreach ($devices as $row) { ?>
                                    <tr class="gradeX">
                                        <td><?php echo $row['device_no'] ?></td>
                                        <!--<td><?php /*echo $row['port_no'] */?></td>
                                        <?php /*if ($row['qr_img']) { */?>
                                            <td><img width="100" height="100" src="<?php /*echo $row['qr_img'] */?>" alt="未设置"></td>
                                        <?php /*} else { */?>
                                            <td>未生成</td>
                                        --><?php /*} */?>

                                        <td><?php echo $row['address'] ?></td>
                                        <td><?php echo $row->attach ?></td>
                                        <td>
                                            <a class="btn-link" href="<?php echo URL::action('Admin\DeviceController@list',['device_no'=>$row->device_no]) ?>">点击查看 </a>
                                        </td>
                                        <td>
                                            <a class="btn-link" href="<?php echo URL::action('Admin\DeviceController@statCostDetail',['device_no'=>$row->device_no]) ?>">流水统计 </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-warning" href="<?php echo URL::action('Admin\DeviceController@deviceConfig',['device_no'=>$row->device_no]) ?>">点击配置 </a>
                                        </td>

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