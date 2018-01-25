<?php echo view('admin.header')->render() ?>
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                <a href="#" class="current">反馈列表</a></div>
            <h1>反馈列表</h1>
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
<!--                            <span class="pull-right"><a href="" class="btn btn-info"></a></span>-->
                        </div>
                        <div class="widget-content nopadding">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>手机号</th>
                                    <th>反馈内容</th>
                                    <th>反馈时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php /** @var \App\Models\Feedbacks $feedback */
                                foreach ($feedbacks as $feedback) { ?>
                                    <tr class="gradeX">
                                        <td><?php echo $feedback->phone; ?></td>
                                        <td><?php echo $feedback->content; ?></td>
                                        <td><?php echo $feedback->created_at; ?></td>
                                        <td>
                                            <a href="" class="btn btn-info">设置</a>
<!--                                            <a href="javascript:;" class="btn btn-danger del">删除</a>-->
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