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
        <div class="widget-content">
            <form class="form-search">
                <div class="control-group">
                    <div class="controls controls-row">
                        <div class="inline-block">
                            <label>设备号</label>
                            <input value="<?php echo Request::input('device_no') ?>" name="device_no" type="text"/>
                        </div>

                        <div class="inline-block">
                            <label>端口号</label>
                            <input value="<?php echo Request::input('port_no') ?>"  name="port_no" type="text"/>
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
                                <th>设备号</th>
                                <th>端口号</th>
                                <th>时间戳</th>
                                <th>从机版本</th>
                                <th>输入电压</th>
                                <th>输出电压</th>
                                <th>电流</th>
                                <th>电能</th>
                                <th>功率</th>
                                <th>打开/关闭电源配置</th>
                                <th>功率因子</th>
                                <th>丝杆状态</th>
                                <th>丝杆配置</th>
                                <th>丝杆位置</th>
                                <th>继电器开关状态</th>
                                <th>继电器粘连告警</th>
                                <th>过流保护告警</th>
                                <th>丝杠不到位告警</th>
                                <th>漏电告警</th>
                                <th>端口可用性</th>
                                <th>是否在充电</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php /** @var \App\Models\SalvePortInfos $row */?>
                            @foreach ($datas as $row)
                                <tr class="gradeX">
                                    <td>{{ $row->udid }}</td>
                                    <td>{{ $row->port }}</td>
                                    <td>{{ \Illuminate\Support\Carbon::createFromTimestamp($row->create_time)->toDateTimeString() }}        </td>
                                    <td>{{ $row->sw_ver }}</td>
                                    <td>{{ $row->volt_input }}</td>
                                    <td>{{ $row->volt_output }}</td>
                                    <td>{{ $row->cur }}</td>
                                    <td>{{ $row->cap }}</td>
                                    <td>{{ $row->power }}</td>
                                    <td>{{ $row->power_on }}</td>
                                    <td>{{ $row->power_scale }}</td>
                                    <td>{{ $row->screw_status }}</td>
                                    <td>{{ $row->screw_on }}</td>
                                    <td>{{ $row->screw_pos }}</td>
                                    <td>{{ $row->rely_status }}</td>
                                    <td>{{ $row->rely_alarm }}</td>
                                    <td>{{ $row->cur_alarm }}</td>
                                    <td>{{ $row->screw_alarm }}</td>
                                    <td>{{ $row->loudian_alarml }}</td>
                                    <td>{{ $row->port_usable }}</td>
                                    <td>{{ $row->is_charge }}</td>
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