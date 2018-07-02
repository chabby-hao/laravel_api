<?php echo view('admin.header')->render() ?>
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                <a href="#" class="current">列表</a></div>
            <h1>列表</h1>
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
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>设备号</th>
                                    <th>端口号</th>
                                    <th>时间戳</th>
                                    <th>干接点继电器状态1</th>
                                    <th>干接点继电器状态2</th>
                                    <th>电表当前电量</th>
                                    <th>电能</th>
                                    <th>电表当前电压</th>
                                    <th>电表当前电流</th>
                                    <th>电表当前功率因素</th>
                                    <th>设备用电池电量备号</th>
                                    <th>端口可用性</th>
                                    <th>设备号</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php /** @var \App\Models\HostPortInfos $row */?>
                                @foreach ($datas as $row)
                                    <tr class="gradeX">
                                        {{ $row->udid }}
                                        {{ $row->port }}
                                        {{ $row->create_time }}
                                        {{ $row->node_rely_status1 }}
                                        {{ $row->node_rely_status2 }}
                                        {{ $row->ammeter_energy }}
                                        {{ $row->ammeter_volt }}
                                        {{ $row->ammeter_cur }}
                                        {{ $row->ammeter_power }}
                                        {{ $row->ammeter_power_scale }}
                                        {{ $row->battery_volt }}
                                        {{ $row->port_usable }}
                                    </tr>
                                @endforeach
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