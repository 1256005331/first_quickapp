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
    <div id="set_add_banner">
        <form class="layui-form"method="post" id="doAdd">
          <input type="hidden" name="id" value="<?php echo ($list["id"]); ?>">
          <div class="layui-form-item">
            <label class="layui-form-label">跳转地址：</label>
            <div class="layui-input-inline">
              <input type="text" name="url" placeholder="请输入跳转地址" class="layui-input" value="<?php echo ($list["url"]); ?>" style="width:400px;">
            </div>
          </div>   
          <div class="layui-form-item">
            <label class="layui-form-label">BANNER：</label>
            <button type="button" class="layui-btn" id="test1" style="display:none"><i class="layui-icon">&#xe67c;</i>上传BANNER图片</button>
            <div id="show_img" style="display:block;">
              <img src="<?php echo ($list["imagePath"]); ?>" alt="" style="width:200px;"><a href="javascript:change_upload();">重新上传</a>
              <input type="hidden" id="imagePath" name="imagePath" value="<?php echo ($list["imagePath"]); ?>">
            </div>
          </div> 
          <div class="layui-form-item">
                  <label class="layui-form-label">是否显示：</label>
                  <div class="layui-input-inline">
                      <input type="checkbox" name="status" lay-skin="switch" lay-text="显示|影藏" <?php if($list["status"] == 0): ?>checked<?php endif; ?>>
                  </div>
              </div>
              <div class="layui-form-item">
                  <button class="layui-btn layui-btn ml80" lay-submit lay-filter="form" style="width:300px;margin-left:50px;">修改</button>
              </div>
        </form>
      </div>
            
            <script>
              function change_upload(){
                $('#show_img').hide();
                $('#test1').show();
              }
              $(function() {
                layui.use(['form','upload'], function() {
                  var form = layui.form;
                  var upload = layui.upload;
                  		//执行实例
		var uploadInst = upload.render({
			elem: '#test1' //绑定元素
			,url: '<?php echo U("Kyy/upbanner");?>' //上传接口
			,exts:'jpg|png|gif|bmp|jpeg'//允许上传的文件扩展名
			,field:'file'
			,size:1024
			,done: function(res){
				if(res.code == 1){
					$('#show_img img').attr('src',res.data);
					$('#show_img').show();
					$('#test1').hide();
					$('#imagePath').attr('value',res.data);
				}
			}
			,error: function(){
			//请求异常回调
			}
		});
    form.on('submit(form)', function(data){
    layer.msg('修改中，请稍后..', {
      icon: 16
      ,shade: 0.01
      ,time:360000
    });
		$.ajax({
	        url:"<?php echo U('Kyy/banner_edit');?>",
					type:'POST',
	        data:data.field,
	        dataType:"json",
	        error:function(data){
	            later.alert('发生错误。',{icon:5});
	        },
	        success:function(data){
            layer.close(layer.index);
						if(data.code == 1){
							layer.msg(data.msg, {icon: 6,time:2000});
						}else{
							layer.msg(data.msg, {icon: 5,time:2000});
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