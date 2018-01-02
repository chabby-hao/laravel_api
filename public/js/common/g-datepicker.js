/**
 * Created by chabby on 16/12/9.
 */
window.gDatepicker = (function () {
    var nowDate = Date.today().toString('yyyy-MM-dd');

    var minDate = Date.today().addDays(-7).toString('yyyy-MM-dd');

    return {
        dateRangePicker: function (domNode) {
            var startDate = arguments[1] || minDate;
            var endDate = arguments[2] || nowDate;
            domNode.daterangepicker({
                "showWeekNumbers": true,
                "locale": {
                    "format": "YYYY/MM/DD",
                    "separator": " - ",
                    "applyLabel": "确认",
                    "cancelLabel": "取消",
                    "fromLabel": "开始",
                    "toLabel": "截止",
                    "customRangeLabel": "Custom",
                    "weekLabel": "周",
                    "daysOfWeek": [
                        "日",
                        "一",
                        "二",
                        "三",
                        "四",
                        "五",
                        "六"
                    ],
                    "monthNames": [
                        "一月",
                        "二月",
                        "三月",
                        "四月",
                        "五月",
                        "六月",
                        "七月",
                        "八月",
                        "九月",
                        "十月",
                        "十一月",
                        "十二月"
                    ],
                    "firstDay": 1
                },
                "startDate": startDate,
                "endDate": endDate,
                //"maxDate": "12/07/2016"
                "maxDate": nowDate
            }, function (start, end, label) {
                console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
            });
        },
    };
})();