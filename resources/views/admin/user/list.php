<?php echo view('admin.header')->render() ?>
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                <a href="#" class="current">用户列表</a></div>
            <h1>用户列表</h1>
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
                                    <th>用户id</th>
                                    <th>手机号</th>
                                    <th>注册时间</th>
                                    <th>余额</th>
                                    <th>赠送金</th>
                                    <th>福利卡</th>
<!--                                    <th>操作</th>-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php /** @var \App\Models\User $user */
                                foreach ($users as $user) { ?>
                                    <tr class="gradeX">
                                        <td><?php echo $user->id ?></td>
                                        <td><?php echo $user->phone ?></td>
                                        <td><?php echo $user->created_at ?></td>

                                        <td><?php echo $user->user_balance ?></td>
                                        <td><?php echo $user->present_balance ?></td>
                                        <td><?php echo implode('<br/>', $user->card_name); ?></td>
<!--                                        <td>-->
<!--                                            <a href="" class="btn btn-info">设置</a>-->
<!--<!--                                            <a href="javascript:;" class="btn btn-danger del">删除</a>-->
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