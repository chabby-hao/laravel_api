<?php echo view('admin.header')->render() ?>
    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
                <a href="#" class="current">福利卡列表</a></div>
            <h1>福利卡列表</h1>
        </div>
        <div class="container-fluid">
            <hr>
            <div></div>
            <div class="widget-content">
                <!--<form class="form-search">
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
                </form>-->
            </div>
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
                                    <th>卡id</th>
                                    <th>卡名</th>
                                    <th>公司</th>
                                    <th>二维码</th>
                                    <th>设备号（棚号）</th>
                                    <th>白名单</th>
                                    <th>生成日期</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php /** @var \App\Models\WelfareCards $card */
                                foreach ($data as $card) { ?>
                                    <tr class="gradeX">
                                        <td><?php echo $card->id ?></td>
                                        <td><?php echo $card->card_name ?></td>
                                        <td><?php echo $card->company ?></td>
                                        <td><img width="100px" src="<?php echo $card->url_img; ?>" alt=""></td>
                                        <td><?php echo implode("<br/>", $card->device_no) ?></td>
                                        <td>
                                            <?php if($card->limit_user){ ?>
                                            <?php $phones = implode("<br/>", $card->phones); ?>
                                            <a onclick="myalert('<?php echo $phones; ?>')" class="btn btn-info" href="javascript:;">点击查看</a>
                                            <?php  }else{?>
                                                不限制
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $card->created_at ?></td>
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
<script>

</script>
