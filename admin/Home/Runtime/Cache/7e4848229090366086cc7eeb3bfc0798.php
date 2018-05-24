<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>活动后台</title>
    <link rel="stylesheet" href="__FRAME__/layui/css/layui.css">
    <link rel="stylesheet" href="__FRAME__/static/css/style.css">
    <link rel="icon" href="__FRAME__/static/image/code.png">
</head>
<body>

<!-- layout admin -->
<div class="layui-layout layui-layout-admin"> <!-- 添加skin-1类可手动修改主题为纯白，添加skin-2类可手动修改主题为蓝白 -->
    <!-- header -->
    <div class="layui-header my-header">
        <a href="index.html">
            <!--<img class="my-header-logo" src="" alt="logo">-->
            <div class="my-header-logo">活动后台</div>
        </a>
        <div class="my-header-btn">
            <button class="layui-btn layui-btn-small btn-nav"><i class="layui-icon">&#xe65f;</i></button>
        </div>

        <!-- 顶部左侧添加选项卡监听 -->
        <ul class="layui-nav" > <!--lay-filter="side-top-left"-->
            <li class="layui-nav-item layui-this">
                <a href="javascript:;" id="delCache"><i class="layui-icon" style="font-size:16px;">&#xe640;</i>清除缓存</a>
            </li>
        </ul>

        <!-- 顶部右侧添加选项卡监听 -->
        <ul class="layui-nav my-header-user-nav" lay-filter="side-top-right">
            <li class="layui-nav-item">
                <a class="name" href="javascript:;"><i class="layui-icon">&#xe629;</i>主题</a>
                <dl class="layui-nav-child">
                    <dd data-skin="0"><a href="javascript:;">默认</a></dd>
                    <dd data-skin="1"><a href="javascript:;">纯白</a></dd>
                    <dd data-skin="2"><a href="javascript:;">蓝白</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a class="name" href="javascript:;"><i class="layui-icon" style="font-size:16px;">&#xe612;</i><?=$_COOKIE['username']?></a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;" id="logout"><i class="layui-icon">&#x1006;</i>退出</a></dd>
                </dl>
            </li>
        </ul>

    </div>
    <!-- side -->
    <div class="layui-side my-side">
        <div class="layui-side-scroll">
            <!-- 左侧主菜单添加选项卡监听 -->
            <ul class="layui-nav layui-nav-tree" lay-filter="side-main">
            </ul>

        </div>
    </div>
    <!-- body -->
    <div class="layui-body my-body">
        <div class="layui-tab layui-tab-card my-tab" lay-filter="card" lay-allowClose="true">
            <ul class="layui-tab-title">
                <li class="layui-this" lay-id="1"><span><i class="layui-icon">&#xe638;</i>欢迎页</span></li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <iframe id="iframe" src="<?php echo U('Index/data');?>" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- footer -->
    <div class="layui-footer my-footer">

    </div>
</div>

<!-- pay -->
<div class="my-pay-box none">
    <div><img src="__FRAME__/static/image/zfb.png" alt="支付宝"><p>支付宝</p></div>
    <div><img src="__FRAME__/static/image/wx.png" alt="微信"><p>微信</p></div>
</div>

<!-- 右键菜单 -->
<div class="my-dblclick-box none">
    <table class="layui-tab dblclick-tab">
        <tr class="card-refresh">
            <td><i class="layui-icon">&#x1002;</i>刷新当前标签</td>
        </tr>
        <tr class="card-close">
            <td><i class="layui-icon">&#x1006;</i>关闭当前标签</td>
        </tr>
        <tr class="card-close-all">
            <td><i class="layui-icon">&#x1006;</i>关闭所有标签</td>
        </tr>
    </table>
</div>

<script type="text/javascript" src="__FRAME__/layui/layui.js"></script>
<script type="text/javascript" src="__FRAME__/static/js/vip_comm.js"></script>
<script type="text/javascript">
layui.use(['layer','vip_nav'], function () {

    // 操作对象
    var layer       = layui.layer
        ,vipNav     = layui.vip_nav
        ,$          = layui.jquery;

    // 顶部左侧菜单生成 [请求地址,过滤ID,是否展开,携带参数]
    //vipNav.top_left('<?php echo U("Index/lefttoplist");?>','side-top-left',false);
    // 主体菜单生成 [请求地址,过滤ID,是否展开,携带参数]
    vipNav.main('<?php echo U("Index/leftlist");?>','side-main',true);

    // you code ...
    $('#logout').click(function(){
        //询问框
        layer.confirm('您确定要登出？', {
          btn: ['确定','取消'] //按钮
        }, function(){
            var url = "<?php echo U('Login/doLogout');?>";
            $.get(url,function(data){
                if(data.code == 0){
                    location.href = "<?php echo U('Login/login');?>";
                }else{
                    layer.alert(data.msg);
                }
            },"json");
        }, function(){
            layer.msg('已取消');
        });
    });
    $('#delCache').click(function(){
        var url = "<?php echo U('Index/delCache');?>";
        $.get(url,function(data){
            if(data.code == 0){
                layer.alert(data.msg);
            }
        },"json");
    });
});
</script>
</body>
</html>