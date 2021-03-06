<?php echo view('admin.header')->render() ?>
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                <a href="#" class="current">退款列表</a></div>
            <h1>退款列表</h1>
        </div>
        <div class="container-fluid">
            <hr>
<!--            <div></div>
            <div class="widget-content">
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
<!--                            <span class="pull-right"><a href="" class="btn btn-info"></a></span>-->
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>手机号</th>
                                    <th>退款时间</th>
                                    <th>退款金额</th>
                                    <th>退款状态</th>
<!--                                    <th>操作</th>-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php /** @var \App\Models\UserRefunds $refund */
                                foreach ($refunds as $refund) { ?>
                                    <tr class="gradeX">
                                        <td><?php echo $refund->phone; ?></td>
                                        <td><?php echo $refund->created_at; ?></td>
                                        <td><?php echo $refund->refund_amount; ?></td>
                                        <td><?php echo \App\Models\UserRefunds::getStateMap($refund->state); ?></td>
                                        <!--<td>
                                            <a href="" class="btn btn-info">设置</a>
                                        </td>-->
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