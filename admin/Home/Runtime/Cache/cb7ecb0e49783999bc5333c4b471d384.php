<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>修改</title>
    <link rel="stylesheet" type="text/css" href="__FRAME__/layui/css/layui.css">
    <script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="__FRAME__/layui/layui.js"></script>
</head>
<body>
        <div id="edit_form">
                <div id="set-edit-platform" style="width:400px; padding:20px;">
                    <form class="layui-form" id="doAdd">
                        <input type="hidden" name="id" value="<?php echo ($list["id"]); ?>">
                      <!-- <div class="layui-form-item" style="text-align: center">
                        <label class="layui-form-label">游戏名称：</label>
                        <div class="layui-input-inline">
                          <input type="text" name="gamename" lay-verify="required" autocomplete="off" placeholder="请输入游戏名称" class="layui-input" value="<?php echo ($list["gamename"]); ?>">
                        </div>
                      </div>   -->
                      <div class="layui-form-item" style="text-align: center">
                        <label class="layui-form-label">区服信息：</label>
                        <div class="layui-input-inline">
                          <input type="text" name="gserver" lay-verify="company_name" autocomplete="off" placeholder="请输入区服信息" class="layui-input" value="<?php echo ($list["gserver"]); ?>">
                        </div>
                      </div>
                      <div class="layui-form-item" style="text-align: center">
                          <label class="layui-form-label">开服时间：</label>
                          <div class="layui-input-inline">
                            <input type="text" name="open_time" class="layui-input" id="test2" placeholder="yyyy-MM-dd" lay-verify="required" value="<?php echo ($list["open_time"]); ?>">
                          </div>
                      </div>
                      <div class="layui-form-item">
                        <label class="layui-form-label">所属类别：</label>
                        <div class="layui-input-inline">
                          <select name="category" lay-verify="required" lay-search="" value="<?php echo ($list["category"]); ?>">
                              <option value="">请选择所属类别</option>
                                <option value="1" <?php if($list["category"] == 1): ?>selected<?php endif; ?> >热门</option>
                                <option value="2" <?php if($list["category"] == 2): ?>selected<?php endif; ?> >传奇</option>
                                <option value="3" <?php if($list["category"] == 3): ?>selected<?php endif; ?> >魔幻</option>
                                <option value="4" <?php if($list["category"] == 4): ?>selected<?php endif; ?> >仙侠</option>
                                <option value="5" <?php if($list["category"] == 5): ?>selected<?php endif; ?> >其他</option>
                              </select>
                              <img src="" alt="">
                        </div>
                      </div>
                      <div style="text-align: center"><button class="layui-btn layui-btn ml80" lay-submit lay-filter="form" style="width:250px">修改</button></div>
                    </form>
                  </div>
            </div>
            
            <script>
              $(function() {
                layui.use(['form','laydate'], function() {
                  var form = layui.form;
                  var laydate = layui.laydate;
  
                  //执行一个laydate实例
                  laydate.render({
                    elem: '#test2' //指定元素
                    ,type:'datetime'
                  });
                  form.on('submit(form)', function(data){
                    $.ajax({
                          url:"<?php echo U('Kyy/edit');?>",
                          type:'POST',
                          data:$('#doAdd').serialize(),
                          dataType:"json",
                          error:function(data){
                              
                          },
                          success:function(data){
                              if(data.code==1){
                                  layer.msg(data.msg, {icon:6,time:1000});
                              }else{
                                  layer.msg(data.msg, {icon:5,time:2000});
                                  return false;
                              }
                          }
                      });
                      return false;
                  });
                });
              });
            </script>
</body>
</html>