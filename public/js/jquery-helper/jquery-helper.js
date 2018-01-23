var t_redirect = null;
function ajax_check_res(data) {
    var isAlert = arguments[1] === false ? false : true;//默认第二个参数，弹出提示框
    if (data.code == 200) {
        if (data.redirect != undefined) {

            if(data.timeout == undefined || data.timeout == 0){
                location.href = data.redirect;
            }else{
                myalert('成功！页面马上跳转');
                t_redirect = setTimeout(function(){
                    location.href = data.redirect;
                }, data.timeout);
            }
            return true;
        }
        if(data.msg =='success'){
            myalert('成功');
        }
        // if (data.msg != undefined) {
        //     myalert(data.msg);
        // }
        return true;
    } else {
        if (isAlert) {
            myalert(data.msg);
            /*setTimeout(function(){
             myalert(data.data.errMessage);
             }, '500');*/
        }
        return false;
    }
}

function myalert(msg) {

    var modal = $('<div id="myAlert" class="modal hide"> <div class="modal-header myalert"> <button data-dismiss="modal" class="close" type="button">×</button> <h3>提示</h3> </div> <div class="modal-body"> <p>' + msg + '</p> </div> <div class="modal-footer"> <!--<a data-dismiss="modal" class="btn btn-primary" href="#">Confirm</a>--> <a data-dismiss="modal" class="btn alert2" href="#">OK</a> </div> </div> ');

    var body = $("body");
    body.append(modal);
    var width = modal.width();
    modal.modal().css({marginLeft: '-' + width / 2 + 'px'});

    $(".alert2").click(function () {
        clearTimeout(t_redirect);
    })

    $(".modal-backdrop").animate().css({opacity: 0.3});
    $(".modal-backdrop").click(function () {
        $('.mymodal,.modal-backdrop').remove();
    });
}

//ajax加载动画
function ajax_loading() {
    var obj_loading = $('<div class="mask"></div><div class="spinner"></div>');
    $("body").append(obj_loading);
    $('.mask').css({'display': 'block'});
    center($('.spinner'));
    $('.spinner').css({'display': 'block'});
    // 居中
    function leftTop(obj) {
        var screenWidth = $(window).width();
        var screenHeight = $(window).height();
        var scrolltop = $(document).scrollTop();
        var scrollleft = $(document).scrollLeft();
        var objLeft = (screenWidth - obj.width()) / 2 + scrollleft;
        var objTop = (screenHeight - obj.height()) / 2 + scrolltop - 160;
        obj.css({left: objLeft + 'px', top: objTop + 'px'});
    }

    function center(obj) {
        leftTop(obj);
        //浏览器窗口大小改变时
        $(window).resize(function () {
            leftTop(obj);
        });
        //浏览器有滚动条时的操作、
        $(window).scroll(function () {
            leftTop(obj);
        });

    }
}

function ajax_complete() {
    $('.mask').fadeOut("slow", function () {
        $(this).remove();
    });
    $('.spinner').fadeOut("slow", function () {
        $(this).remove();
    });
}

$(function () {
    /*$(document).ajaxStart(onStart)
     .ajaxComplete(onComplete)
     .ajaxSuccess(onSuccess);*/

    $.ajaxSetup({
        dataType: 'json',
        type: 'post',
        beforeSend: function () {
            onStart();
        },
        complete: function () {
            onComplete();
        },
        success: function () {
            onSuccess();
        },
    });

    function onStart(event) {
        //动画加载
        ajax_loading();
        //.....
    }

    function onComplete(event, xhr, settings) {
        //动画加载结束
        ajax_complete();
        //.....
    }

    function onSuccess(event, xhr, settings) {
        //动画加载结束
        ajax_complete();
        //.....
    }
})

//为数组添加删除元素的方法
Array.prototype.removeByValue = function (val) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] == val) {
            this.splice(i, 1);
            break;
        }


    }
}

//获取url参数
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}