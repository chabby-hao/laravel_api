<?php echo view('admin.header')->render() ?>
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                <a href="#" class="current">柜门列表</a></div>
            <h1>柜门列表</h1>
        </div>
        <div class="container-fluid">
            <hr>
            <div></div>
            <!--<div class="widget-content">
                <form class="form-search">
                    <div class="control-group">
                        <div class="controls controls-row">
                            <div class="inline-block">
                                <label>时间范围</label>
                                <input name="date_range" id="daterange" type="text"/>
                            </div>

                            <div class="inline-block">
                                <input class="btn btn-success" id="btn-search" type="submit" value="确定"/>
                            </div>

                        </div>
                    </div>
                </form>
            </div>-->
            <div class="row-fluid">
                <div class="span12">
                    <div class="widget-box">
                        <div class="widget-title"><span class="icon"><i class="icon-th"></i></span>
                            <h5>列表</h5>
                            <span class="pull-right"><a href="<?php echo URL::action('Admin\CabinetController@add') ?>" class="btn btn-info">新增设备</a></span>
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>柜号</th>
                                    <th>门编号</th>
                                    <th>打开状态</th>
                                    <th>是否有电池</th>
                                    <th>电池Id</th>
                                    <th>添加时间</th>
<!--                                    <th>操作</th>-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 0; ?>
                                <?php foreach ($devices as $row) { ?>
                                    <tr class="gradeX">
                                        <td><?php echo ++$i; ?></td>
                                        <td><?php echo $cabinet_no?></td>
                                        <td><?php echo $row->door_no; ?></td>
                                        <td><?php echo $row->openState ?></td>
                                        <td><?php echo $row->hasBattery ?></td>
                                        <td><?php echo $row->batteryId ?></td>
                                        <td><?php echo $row->created_at ?></td>
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