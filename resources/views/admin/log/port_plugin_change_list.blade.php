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
                                <th>ID</th>
                                <th>设备号</th>
                                <th>端口号</th>
                                <th>上次异物状态</th>
                                <th>当前异物状态</th>
                                <th>时间戳(设备上报的)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php /** @var \App\Models\PortPluginChanges $row */?>
                            @foreach ($datas as $row)
                                <tr class="gradeX">
                                    <td>{{ $row->id }}               </td>
                                    <td>{{ $row->device_id }}               </td>
                                    <td>{{ $row->port }}               </td>
                                    <td>{{ $row->pre_plugin }}               </td>
                                    <td>{{ $row->plugin }}               </td>
                                    <td>{{ \Illuminate\Support\Carbon::createFromTimestamp($row->time_stamp)->toDateTimeString() }}        </td>
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