<!--Footer-part-->

<div class="row-fluid">
    <!--<div id="footer" class="span12"> 2013 &copy; Matrix Admin. Brought to you by <a href="http://themedesigner.in/">Themedesigner.in</a> </div>-->
</div>

<!--end-Footer-part-->

<!--<script src="/js/excanvas.min.js"></script>-->

<!--<script src="/js/jquery.flot.min.js"></script>
<script src="/js/jquery.flot.resize.min.js"></script>
<script src="/js/jquery.peity.min.js"></script>
<script src="/js/fullcalendar.min.js"></script>
<script src="/js/matrix.js"></script>
<script src="/js/matrix.dashboard.js"></script>
<script src="/js/jquery.gritter.min.js"></script>
<script src="/js/matrix.interface.js"></script>
<script src="/js/matrix.chat.js"></script>
<script src="/js/jquery.validate.js"></script>
<script src="/js/matrix.form_validation.js"></script>
<script src="/js/jquery.wizard.js"></script>
<script src="/js/jquery.uniform.js"></script>
<script src="/js/select2.min.js"></script>
<script src="/js/matrix.popover.js"></script>
<script src="/js/jquery.dataTables.min.js"></script>
<script src="/js/matrix.tables.js"></script>-->

<script>
    $(function(){
        var url = window.location.href;
        $('.submenu li').each(function(){
            var _this = $(this);
            var _href = _this.find("a").attr("href");
            if(url.indexOf(_href) !== -1){
                _this.addClass("active").parents(".submenu").addClass("open");
            }
        });
    });
</script>

<script type="text/javascript">
    // This function is called from the pop-up menus to transfer to
    // a different page. Ignore if the value returned is a null string:
    function goPage(newURL) {

        // if url is empty, skip the menu dividers and reset the menu selection to default
        if (newURL != "") {

            // if url is "-", it is this page -- reset the menu:
            if (newURL == "-") {
                resetMenu();
            }
            // else, send page to designated URL
            else {
                document.location.href = newURL;
            }
        }
    }


    // resets the menu selection upon entry to this page:
    function resetMenu() {
        document.gomenu.selector.selectedIndex = 2;
    }
</script>
</body>
</html>
<script src="/js/matrix.js"></script>
<script src="/js/jquery-helper/jquery-helper.js"></script>
<script src="/js/jquery-form/jquery.form.js"></script>
<!--<script src="/js/matrix.dashboard.js"></script>-->