window.gChart = (function () {
    var __data__;
    var mychart = echarts;

    var xAxis = {
        type: 'category',
        boundaryGap: false,
        axisLine: {onZero: false},
        data: [
            0,
        ],
    };
    var optionExt;
    var legend = {
        data: [],
        x: 'line'
    };
    var yAxis = [];
    var seriesData = [];
    optionExt = {
        legend: legend,
        xAxis: xAxis,
        yAxis: yAxis,
        series: seriesData,
    };

    var checkboxConfig = {'list':[
        {name: '请求数', key: 'request_num_total'},
        {name: '展示数', key: 'show_num_total'},
        {name: '点击数', key: 'click_num_total'},
        {name: '填充率', key: 'fillRatio'},
        {name: '点击率', key: 'clickRatio'},
        {name: 'ecpm', key: 'ecpm'},
        {name: 'cpc', key: 'cpc'},
    ]};

    var renderHtml = Mustache.render($("#template_checkbox").html(), checkboxConfig);
    $('#target_checkbox').html(renderHtml);

    var form_search = $('#form-search');

    var template = $('#template_tabledata').html();
    Mustache.parse(template);   // optional, speeds up future uses

    //checkbox的选中状态数组队列
    var arrCheckbox = [];

    return {
        init: function (dom) {
            dom.style.height = '350px';
            console.log(mychart);
            console.log(dom);
            mychart = echarts.init(document.getElementById('g-echart'));
            mychart.showLoading();
            // 指定图表的配置项和数据
            var option = {
                animation: true,
                color: ['#B0E0E6', '#9BCD9B', '#d48265', '#91c7ae', '#749f83', '#ca8622', '#bda29a', '#6e7074', '#546570', '#c4ccd3'],
                title: {
                    text: '趋势图',
                    //subtext: '数据来自西安兰特水电测控技术有限公司',
                    x: 'center',
                    align: 'center'
                },
                grid: {
                    bottom: 80
                },
                /*toolbox: {
                 feature: {
                 dataZoom: {
                 yAxisIndex: 'none'
                 },
                 restore: {},
                 saveAsImage: {}
                 }
                 },*/
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        animation: false
                    }
                },
                /*legend: {
                 data: ['流量', '降雨量'],
                 x: 'line'
                 },*/
                dataZoom: [
                    {
                        show: true,
                        realtime: true,
                        //start: 65,
                        //end: 85
                    },
                    {
                        show: true,
                        //type: 'inside',
                        realtime: true,
                        //start: 65,
                        //end: 85
                    }
                ],
                xAxis: xAxis,
                yAxis: [
                    {
                        name: 'A',
                        type: 'value',
                    },
                    {
                        name: 'B',
                        type: 'value',
                    }
                ],
                series: [
                    {
                        name: 'A',
                        type: 'line',
                        lineStyle: {
                            normal: {
                                width: 3
                            }
                        },
                        data: [
                            0
                        ]
                    },
                    {
                        name: 'B',
                        type: 'line',
                        yAxisIndex: 1,
                        lineStyle: {
                            normal: {
                                width: 3
                            }
                        },
                        markArea: {
                            //silent: true,
                            data: [[{
                                xAxis: '2009/9/10\n7:00'
                            }, {
                                xAxis: '2009/9/20\n7:00'
                            }]]
                        },
                        data: [
                            0
                        ]
                    },
                ]
                //yAxis: {}
            };
            // 使用刚指定的配置项和数据显示图表。
            mychart.setOption(option);//基础配置
            return this;
        },
        run: function(){
            var checkbox = function () {

                var __this__ = this;

                this.clearCheckBox = function () {
                    $(".mycheck").each(function () {
                        $(this).prop("checked", false);
                    });
                };

                this.clickEvt = function () {
                    $(".mycheck").click(function () {
                        var prop = $(this).prop("checked");
                        var key = $(this).val();
                        var value = $(this).attr('datavalue');
                        if (prop) {
                            //选中
                            __this__.addNew(key, value);
                        } else {
                            //取消选中,不允许手动取消
                            $(this).prop("checked", true)
                            /*for(var x=0; x< arrCheckbox.length; x++)
                             {
                             if(arrCheckbox[x] == key){
                             arrCheckbox.splice(x, 1);
                             legend.data.splice(x, 1);
                             legend.selectedMode = 'single';
                             yAxis.splice(x, 1);
                             seriesData.splice(x, 1);
                             myChart.setOption(optionExt);
                             break;
                             }
                             }*/
                        }
                    });
                };

                //选择新的统计项到图表
                this.addNew = function (key, name) {
                    arrCheckbox.push(key);//压入最新key到arrCheckbox

                    if (arrCheckbox.length > 2) {
                        var tmpKey = arrCheckbox.shift();
                        //console.log(tmpKey);
                        //console.log($("." + key));
                        $("." + tmpKey).prop("checked", false).parents("span").removeClass("checked");
                    }

                    $("." + key).prop('checked', true).parents("span").addClass("checked");

                    if (legend.data.length < 2) {
                        //新增
                    } else {
                        //替换掉旧的
                        legend.data.shift();
                        yAxis.shift();
                        seriesData.shift();
                    }
                    legend.data.push(name);
                    yAxis.push({
                        name: name,
                        type: "value",
                        splitLine: {
                            show: false
                        }
                    });
                    var dataconf = __data__.list.map(function (item) {
                        return item[key];
                    });
                    seriesData.push({
                        name: name,
                        type: 'line',
                        areaStyle: {
                            normal: {}
                        },
                        lineStyle: {
                            normal: {
                                width: 3
                            }
                        },
                        data: dataconf
                    });
                    mychart.setOption(optionExt);
                }

                return this;
            };

            //定义全局图表工具类
            var gChart = checkbox();

            gChart.clickEvt();

            mychart.hideLoading();

            form_search.ajaxForm({
                //url: "{{:U('root/report/ajaxGetSumData')}}",
                success: function (data) {
                    __data__ = data;
                    xAxis.data = __data__.list.map(function (item) {
                        return item.day;
                    });
                    //console.log(xAxis);
                    mychart.setOption(optionExt);
                    mychart.hideLoading();
                    var html = Mustache.render(template, data);
                    $('#target').html(html);
                    $('.data-table').dataTable({
                        "bJQueryUI": true,
                        "sPaginationType": "full_numbers",
                        "sDom": '<""l>t<"F"fp>',
                        "bPaginate": true,
                        "bFilter": false
                    });

                    gChart.addNew('request_num_total', '请求数');
                    gChart.addNew('show_num_total', '展示数');
                }
            });
            //初始化页面的时候请求下数据
            form_search.submit();

            $("#btn-search").click(function () {
                mychart.showLoading();
                form_search.submit();
            });
        }
        /*setData: function (data) {
            __data__ = data;
            gChart.addNew('request_num_total', '请求数');
            gChart.addNew('show_num_total', '展示数');
        },*/
        /*setOption: function (option) {
            mychart.setOption(option);
        }*/
    }
})();