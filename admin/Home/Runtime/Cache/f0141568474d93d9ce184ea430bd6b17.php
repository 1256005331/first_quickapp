<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>登录</title>
    <link rel="stylesheet" href="__FRAME__/layui/css/layui.css">
    <link rel="stylesheet" href="__FRAME__/static/css/style.css">
    <link rel="icon" href="__FRAME__/static/image/code.png">
</head>
<body>

<div class="login-main">
    <header class="layui-elip">后台登录</header>
    <form class="layui-form">
        <div class="layui-input-inline">
            <input type="text" name="account" required lay-verify="required" placeholder="账号" autocomplete="off"
                   class="layui-input">
        </div>
        <div class="layui-input-inline">
            <input type="password" name="password" required lay-verify="required" placeholder="密码" autocomplete="off"
                   class="layui-input">
        </div>
        <div class="layui-input-inline">
            <input type="text" name="code" required lay-verify="required" placeholder="验证码" autocomplete="off"
                   class="layui-input" style="width:60%!important">
            <a href="javascript:;" onClick="getcode()" ><img id='img' src="<?php echo U('Public/code');?>" style="position:absolute;left:230px;top:0px;border:1px solid #e6e6e6;" height="38px" width="120px"></a>
        </div>
        <div class="layui-input-inline login-btn">
            <button type="button" class="layui-btn">登录</button>
        </div>
        <hr/>
    </form>
</div>


<script src="__FRAME__/layui/layui.js"></script>
<script type="text/javascript">
        function getcode(){
            var timestamp =Date.parse(new Date());
            var url = "<?php echo U('Public/code');?>?_"+Math.random();
            var image = document.getElementById('img');
            image.src = url;
        };
    layui.use(['form'], function () {

        // 操作对象
        var form = layui.form
                , $ = layui.jquery;

        // you code ...
        
        function dologin(){
            var username = $('input[name="account"]').val();
            var password = $('input[name="password"]').val();
            var code     = $('input[name="code"]').val();
            var url      = "<?php echo U('Login/doLogin');?>";
            var data     = {username:username,password:password,code:code}
            $.post(url,data,function(data){
                if(data.code == 0){
                    location.href=data.data;
                }else{
                    layer.msg(data.msg);
                }
            },"json");
        }
        
        $('button[type="button"]').click(function(){
            dologin();
        });
        
        $('input').keypress(function(e){
            if(e.which == 13) {
                dologin();
            }
        });

    });
</script>
</body>
</html>