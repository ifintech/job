//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
$(function() {
    $(window).bind("load resize", function() {
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.sidebar-collapse').addClass('collapse')
        } else {
            $('div.sidebar-collapse').removeClass('collapse')
        }
    })
    $('#side-menu').metisMenu();
})

// 生成弹窗 如果只传第一个参数必须是url地址
function openDialog(id, url) {
    if(arguments.length === 1) { // 兼容优化处理，仅需要传入url地址即可
        url = id;
        id = 'Modal';
    }
    if($('#'+id).size() < 1) {
        var dialogHtml = '<div class="modal fade" id="'+id+'" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"></div></div></div>';
        $('body').append(dialogHtml);
    }
    $('#'+id).modal({remote:url}).on('hidden.bs.modal', function(e) {
        $(this).removeData("bs.modal");
    });
}

// 刷新当前页
function refresh() {
    window.location.reload();
}

// 跳转指定页面
function jumpUrl(url) {
    window.location.href=url;
}

//js获取指定的cookie的值
function getCookie(name) {
    var cookieArray = document.cookie.split("; "); //得到分割的cookie名值对
    var cookie = new Object();

    for (var i = 0; i < cookieArray.length; i++) {
        var arr = cookieArray[i].split("=");       //将名和值分开
        if (arr[0] == name)return unescape(arr[1]); //如果是指定的cookie，则返回它的值
    }
    return "";
}

//设置cookie
function setCookie(name, value, hours, path, domain, secure) {
    var cdata = name + "=" + value;
    if (hours) {
        var d = new Date();
        d.setHours(d.getHours() + hours);
        cdata += "; expires=" + d.toGMTString();
    }
    cdata += path ? ("; path=" + path) : "";
    cdata += domain ? ("; domain=" + domain) : "";
    cdata += secure ? ("; secure=" + secure) : "";
    document.cookie = cdata;
}

// 清除cookie
function clearCookie() {
    var keys = document.cookie.match(/[^ =;]+(?=\=)/g);
    if (keys) {
        for (var i = keys.length; i--;)
            document.cookie = keys[i] + '=0;expires=' + new Date(0).toUTCString()
    }
}

//删除指定名称的cookie
function delCookie(name) {
    document.cookie = name + '=0;expires=' + new Date(0).toUTCString()
}

//查找数组
function inArray(arr, val) {
    var ret = false, i = 0, len = arr.length;
    for (; i < len; i++) {
        if (arr[i] === val) { // 强类型校验
            ret = true;
            break;
        }
    }
    return ret;
}

//空验证
function isNull2(col, id) {
    var value = document.getElementById(id).value
    if (value == "" || value == null) {

        //console.log(tip);
        var tip = id.substring(0, 2) + "tip";
        $('#' + tip).html("请填写" + col);
        $('#' + id).focus();
        return 1;
    } else {
        return 0;
    }
}

function selectAll(name) { // 全选框
    var i = 1;
    $(name).change(function () {
        if (i % 2 == 1) $("input[name='check']").prop("checked", true);
        if (i % 2 == 0) $("input[name='check']").prop("checked", false);
        i++;
    });
}

function formatMoney(name) {
    $(name).keyup(function () {
        //如果输入非数字，则替换为''，如果输入数字，则在每4位,分隔
        this.value = this.value.replace(/[,]/g, '').replace(/(\d)(?=(\d{4})+(?!\d))/g, "$1,");
    });
}

function decimal(name) {
    $(name).blur(function () {
        //保留两位小数
        var f = parseFloat(this.value);
        if (isNaN(f)) {     //检查空
            return false;
        }
        var f = Math.round(this.value * 100) / 100;  //如有2位以上小数，则截取
        var s = f.toString();
        var rs = s.indexOf('.');    //判断有无小数点
        if (rs < 0) {
            rs = s.length;
            s += '.';           //补充小数点
        }
        while (s.length <= rs + 2) {
            s += '0';          //补充0
        }
        this.value = s;
    });
}
