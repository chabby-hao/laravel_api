<?php echo view('admin.header')->render() ?>
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                <a href="#" class="current">流水统计</a></div>
            <h1>流水统计</h1>
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
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>设备号</th>
                                    <th>日期</th>
                                    <th>流水（元）</th>
                                    <th>成本（元）</th>
                                    <th>分成（元）</th>
                                    <th>充电次数（次）</th>
                                    <th>电量</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i = 0; ?>
                                <?php /** @var \App\Models\DeviceCostDetail $row */
                                foreach ($datas as $row) { ?>
                                    <tr class="gradeX">
                                        <td><?php echo ++$i; ?></td>
                                        <td><?php echo $row->device_no ?></td>
                                        <td><?php echo $row->date ?></td>
                                        <td><?php echo $row->user_cost_amount; ?></td>
                                        <td><?php echo $row->device_cost_amount; ?></td>
                                        <td><?php echo $row->shared_amount; ?></td>
                                        <td><?php echo $row->charge_times; ?></td>
                                        <td><?php echo $row->electric_quantity; ?></td>
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