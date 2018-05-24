<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<title>后台管理中心</title>
<link rel="stylesheet" type="text/css" href="__FRAME__/layui/css/layui.css">
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="__FRAME__/layui/layui.js"></script>
</head>
<body>
<blockquote class="layui-elem-quote">
			抽奖情况
</blockquote>
<div class="explain-col">
	<span class="fr">
	<span class="layui-form-label">游戏简称：</span>
	<div class="layui-input-inline">
		<input id="selcontent" type="text" autocomplete="off" placeholder="请输入游戏简称" class="layui-input">
	</div>
	<button id="sel" class="layui-btn mgl-20">查询</button>
	<button id="export" class="layui-btn mgl-20" style="display:none;">导出</button>
	</span>
	<a class="layui-btn btn-add btn-default" id="btn-refresh"><i class="layui-icon">&#x1002;</i></a>
	<a class="layui-btn btn-add btn-default" id="btn-refresh" href="javascript:open_add();">添加开服游戏</a>
	<button type="button" class="layui-btn" id="test1">
		<i class="layui-icon">&#xe67c;</i>上传表格数据
	</button>
	<table id="demo" lay-filter="test">
	</table>
</div>
<div id="add_form" style="display:none">
	<div id="set-add-platform" style="display:none; width:400px; padding:20px;">
		<form class="layui-form" action="#" id="doAdd">
		  <div class="layui-form-item" style="text-align: center">
			<label class="layui-form-label">游戏名称：</label>
			<div class="layui-input-inline">
			  <input type="text" name="gamename" lay-verify="required" autocomplete="off" placeholder="请输入游戏名称" class="layui-input">
			</div>
		  </div>  
		  <div class="layui-form-item" style="text-align: center">
			<label class="layui-form-label">区服信息：</label>
			<div class="layui-input-inline">
			  <input type="text" name="gserver" lay-verify="company_name" autocomplete="off" placeholder="请输入区服信息" class="layui-input">
			</div>
		  </div>
		  <div class="layui-form-item" style="text-align: center">
			  <label class="layui-form-label">开服时间：</label>
			  <div class="layui-input-inline">
				<input type="text" name="opentime" class="layui-input" id="test2" placeholder="yyyy-MM-dd" lay-verify="required">
			  </div>
		  </div>
		  <div class="layui-form-item">
			<label class="layui-form-label">所属类别：</label>
			<div class="layui-input-inline">
			  <select name="category" lay-verify="required" lay-search="">
				  <option value="">请选择所属类别</option>
					<option value="1">热门</option>
					<option value="2">传奇</option>
					<option value="3">魔幻</option>
					<option value="4">仙侠</option>
					<option value="5">其他</option>
				  </select>
				  <img src="" alt="">
			</div>
		  </div>
		  <div style="text-align: center"><button class="layui-btn layui-btn ml80" lay-submit lay-filter="form" style="width:250px">提交</button></div>
		</form>
	  </div>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-xs" lay-event="del">删除</a>
</script>
<script>
function outexcel() {
	if (!confirm('确认导出吗？')) {
		return false
	};
	$('#act').val("outexcel");
	document.searchform.submit();
	$('#act').val("");
}
//添加h5游戏开服表
function open_add(){
	$('#set-add-platform').show();
	layer.open({
		type: 1,
		skin: 'layui-layer-rim', //加上边框
		area: ['440px', '490px'], //宽高
		shadeClose: true,
		content: $('#add_form')
	});
}

//添加h5游戏开服表
$(function() {
	layui.use(['table','laydate','form','upload'], function() {
		var table = layui.table;
		var laydate = layui.laydate;
		var form = layui.form;
		var upload = layui.upload;
		form.on('submit(edit)', function(data){
                    $.ajax({
                          url:"<?php echo U('Kyy/edit');?>",
                          type:'POST',
                          data:$('#doAdd').serialize(),
                          dataType:"json",
                          error:function(data){
                              
                          },
                          success:function(data){
                              if(data.code==1){
                                  layer.msg(data.msg, {icon:6,time:1000}, function(index){
                                      window.location.href = "<?php echo U('Index/data');?>";
                                  });
                              }else{
                                  layer.msg(data.msg, {icon:5,time:2000});
                                  return false;
                              }
                          }
                      });
                      return false;
                  });
		//执行实例
		var uploadInst = upload.render({
			elem: '#test1' //绑定元素
			,exts:'xlsx'
			,url: "<?php echo U('Kyy/up_excel');?>" //上传接口
			,done: function(res){
				if(res.code == 1){
					layer.alert(res.msg, {icon: 6}, function(index){
	                    layer.close(index);
	                    window.location.href = "<?php echo U('Index/data');?>";
	                });
				}else{
					layer.alert(res.msg,{icon:5});
				}
			}
			,error: function(){
				
			}
		});

		form.on('submit(form)', function(data){
		$.ajax({
	        url:"<?php echo U('Index/h5gameadd');?>",
			type:'POST',
	        data:$('#doAdd').serialize(),
	        dataType:"json",
	        error:function(data){
	            common.layerAlertE('链接错误！', '提示');
	        },
	        success:function(data){
	            if(data.code==1){
	                layer.msg(data.msg, {icon:6,time:1000}, function(index){
	                    window.location.href = "<?php echo U('Index/data');?>";
	                });
	            }else{
	                layer.msg(data.msg, {icon:5,time:2000});
	                return false;
	            }
	        }
	    });
	    return false;
	});
		laydate.render({
			elem: '#test2'
			,type: 'datetime'
		});
		//加载时输出所有数据
		layer.msg('加载中', {
			icon: 16,
			shade: 0.1,
			time: 30000
		});
		datatable('<?php echo U("Kyy/dokyy");?>');
		//用户名搜索
		$('#sel').click(function() {
			layer.msg('加载中', {
				icon: 16,
				shade: 0.1,
				time: 30000
			});
			var like = $('#selcontent').val();
			datatable('<?php echo U("Kyy/dokyy");?>?gname=' + like);
		});
		//导出
		$('#export').click(function() {
			layer.msg('加载中', {
				icon: 16,
				shade: 0.1,
				time: 30000
			});
			var like = $('#selcontent').val();
			window.open('<?php echo U("Draw/dodraw");?>?export=1&like=' + like);
			layer.close(layer.index);
		});
		// 刷新
		$('#btn-refresh').on('click', function() {
			location.reload();
		});
		//第一个实例
		function datatable(gourl) {
			table.render({
				elem: '#demo',
				url: gourl //数据接口
				,
				page: true //开启分页
				,
				size:'lg',
				cols: [
					[ //表头
					{
						field: 'id',
						title: 'ID',
						sort: false
					}, {
						field: 'gname',
						title: '游戏简称',
						sort: false
					}, {
						field: 'gamename',
						title: '游戏全称',
						sort: true
					}, {
						field: 'ico',
						title: '图标',
						sort: true
					}, {
						field: 'gserver',
						title: '区服',
						sort: true
					}, {
						field: 'category',
						title: '类别',
						sort: true
					}, {
						field: 'open_time',
						title: '开服时间',
						sort: true
					}, {
						field: 'creation_time',
						title: '创建时间',
						sort: true
					}
					, {
						fixed: 'right',
						align: 'center',
						toolbar: '#barDemo'
					} //这里的toolbar值是模板元素的选择器
					]
				],
				limits: [20,30,50],
				limit: 20 //默认采用30
				,
				loading: true,
				done: function(res, curr, count) {
					layer.close(layer.index);
				}
			});
		}
		//监听工具条
		table.on('tool(test)', function(obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
			var userdata = obj.data; //获得当前行数据
			var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
			var tr = obj.tr; //获得当前行 tr 的DOM对象
			if (layEvent === 'del') { //查看
				//询问框
				layer.confirm('确定要删除这条数据吗？', {
				btn: ['确定','取消'] //按钮
				}, function(){
					$.get("<?php echo U('Kyy/del');?>",{id:userdata.id},function(data){
						if(data.code == 1){
							layer.alert(data.msg,{icon:6}, function(index){
								window.location.href = "<?php echo U('Index/data');?>";
							});
						}else{
							layer.alert(data.msg,{icon:5});
						}
					},"json");
				}, function(){
					layer.msg('已取消');
				});
			}

			if(layEvent === 'edit'){
                //iframe层
                layer.open({
                    type: 2,
                    title: '修改',
                    shadeClose: true,
                    shade: 0.8,
                    area: ['500px', '90%'],
                    content: "<?php echo U('Index/list_edit');?>?id="+userdata.id
                }); 
				console.log(userdata.id);
			}
		});

	});
});
</script>
  </body>
</html>