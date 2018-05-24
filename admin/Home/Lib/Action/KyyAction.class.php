<?php
class KyyAction extends Action
{
    //后台开服游戏列表输出
    public function dokyy(){
        $page = $_GET['page'];
        $limit= $_GET['limit'];
        $gname= $_GET['gname'];
        $kyy = M('list');
        $where['gname'] = array('like',"%$gname%");
        $sel_res = $kyy->where($where)->page($page,$limit)->select();
        foreach($sel_res as $k => $v){
            $sel_res[$k]['ico'] = "<img src='".$v['ico']."' width=\"40px\" alt=\"\">";
            switch($v['category']){
                case 1:
                    $sel_res[$k]['category'] = "热门";
                    break;
                case 2:
                    $sel_res[$k]['category'] = "传奇";
                    break;
                case 3:
                    $sel_res[$k]['category'] = "魔幻";
                    break;
                case 4:
                    $sel_res[$k]['category'] = "仙侠";
                    break;
                default:
                    $sel_res[$k]['category'] = "其他";
            }
            $sel_res[$k]['open_time'] = date('Y-m-d H:i:s',$v['open_time']);
            $sel_res[$k]['creation_time'] = date('Y-m-d H:i:s',$v['creation_time']);
        }
        $count = $kyy->where($where)->count();
        echo json_encode(array('code' => 0 , 'count' => $count , 'data' => $sel_res));
    }
    public function dobanner(){
        $page = $_GET['page'];
        $limit= $_GET['limit'];
        $kyy = M('banner');
        $sel_res = $kyy->page($page,$limit)->select();
        foreach($sel_res as $k => $v){
            $sel_res[$k]['status']          =   $v['status'] == 0 ? '显示' : '影藏';
            $sel_res[$k]['creation_time']   =   date('Y-m-d H:i:s',$v['creation_time']);
            $sel_res[$k]['imagePath']       =   '<img src="'.$v['imagePath'].'" width="100px" alt="">';
        }
        $count = $kyy->count();
        echo json_encode(array('code' => 0 , 'count' => $count , 'data' => $sel_res));
    }
    //banner图片上传
    public function upbanner(){
        import('ORG.Net.UploadFile');
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 3145728 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->savePath =  'Public/Uploads/';// 设置附件上传目录
        if(!$upload->upload()) {// 上传错误提示错误信息
            $this->error($upload->getErrorMsg());
        }else{// 上传成功 获取上传文件信息
            $info =  $upload->getUploadFileInfo();
        }
        echo json_encode(array('code' => 1,'msg'=>'上传成功','data' => __ROOT__.'/'.$info[0]['savepath'].$info[0]['savename']));
    }
    //banner数据添加
    public function addbanner(){
        $data['url']            =   $_POST['url'];
        $data['imagePath']      =   $_POST['imagePath'];
        $data['status']         =   $_POST['status'];
        $data['creation_time']  =   time();
        $data['status']         =   $data['status'] == 'on' ? 0 : 1 ;
        //echo('<pre/>');print_r($data);exit;
        if(empty($data['url']) || empty($data['imagePath'])){
            echo json_encode(array('code'=>1001,'msg' => '请填写完整'));exit;
        }
        $banner     =   M('banner');
        $add_res    =   $banner->add($data);
        //var_dump($add_res);exit;
        if($add_res){
            echo json_encode(array('code'=>1,'msg' => '添加成功'));exit;
        }else{
            echo json_encode(array('code'=>1002,'msg' => '添加失败'));exit;
        }

    }
    //表格上传
    public function up_excel(){
        import('ORG.Net.UploadFile');
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 3145728 ;// 设置附件上传大小
        $upload->savePath =  'Runtime/';// 设置附件上传目录
        if(!$upload->upload()) {// 上传错误提示错误信息
            $this->error($upload->getErrorMsg());
        }else{// 上传成功 获取上传文件信息
            $info =  $upload->getUploadFileInfo();
        }
        import('ORG.phpexcel.PHPExcel');
        $objPHPExcel = new \PHPExcel();
        if ($info) {
            //获取文件名
            $file_name = $info[0]['savepath'].$info[0]['savename'];
            //上传文件的地址
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
            $obj_PHPExcel = $objReader->load($file_name, $encode = 'utf-8');
            //加载文件内容,编码utf-8
            if (!$obj_PHPExcel) {
                json_return(1001, '只支持Excel2007表格数据上传', '');
            }
            //echo "<pre>";
            $excel_array = $obj_PHPExcel->getsheet(0)->toArray();
            
            //转换为数组格式
            if (!$excel_array) {
                json_return(1001, '只支持Excel2007表格数据上传', '');
            }
            array_shift($excel_array);
            $data = array();
            //echo('<pre/>');print_r($excel_array);exit;
            foreach($excel_array as $k => $v){
                if(!empty($v[0]) && !empty($v[1]) && !empty($v[2]) && !empty($v[3]) && !empty($v[4])){
                    //通过gamename  请求接口 获取gname ico gamename
                    $url = 'http://yutang.8090.com/app.php/Game/get_h5game_lists_by_name/version/1/name/'.$v[0];
                    $gamename_res = doGet($url);
                    preg_match_all('/{(.*?)]}/',$gamename_res, $matches);
                    $str = "{".$matches[1][0]."]}";
                    $gamename_arr = json_decode($str,true);
                    if(isset($gamename_arr['data'][1]) || $gamename_arr['status'] == -1){
                        echo json_encode(array('code'=>1001,'msg'=>'游戏名称输入有误'));exit;
                    }else{
                        preg_match_all('/(.*?)[H5(]/',$gamename_arr['data'][0]['game_name'],$zzres);
                        $data[$k]['gamename'] = $zzres[1][0];
                        $data[$k]['ico'] = $gamename_arr['data'][0]['icon'];
                        $data[$k]['gname'] = $gamename_arr['data'][0]['tag'];
                    }
                    $data[$k]['gserver'] = $v[1];
                    $time_y     = strtotime($v[2]);
                    $time_h     = strtotime('1970/1/1 '.$v[3])+8*60*60;
                    $time_all   = strtotime($v[3]) == -28800 ? $time_y : $time_y + $time_h;
                    $data[$k]['open_time']      = $time_all;
                    
                    switch($v[4]){
                        case '热门':
                            $data[$k]['category'] = 1;
                            break;
                        case '传奇':
                            $data[$k]['category'] = 2;
                            break;
                        case '魔幻':
                            $data[$k]['category'] = 3;
                            break;
                        case '仙侠':
                            $data[$k]['category'] = 4;
                            break;
                        case '其他':
                            $data[$k]['category'] = 5;
                            break;
                    }
                }
            }
            $chk_error = array();
            foreach($data as $k => $v){
                //查询是否存在该条数据
                $list = M('list');
                $chk_res = $list->where($data)->find();
                if(empty($chk_res)){
                    $data[$k]['creation_time']  = time();
                }else{
                    $chk_error[] = implode(',',$data[$k]);
                    unset($data[$k]);
                }
            }
            if(!empty($chk_error)){
                echo json_encode(array('code'=>1001,'msg' => "数据中存在已添加数据,分别是".implode("<br>",$chk_error).",检查后重新导入"));exit;
            }
            unset($excel_array);
            $list = M('list');
            $res  = $list->addAll($data);
            if(!empty($res)){
                echo json_encode(array('code'=>1,'msg'=>"添加数据成功共添加".count($data)." 条数据",));exit;
            }else{
                echo json_encode(array('code'=>1002,'msg'=>"添加失败",));exit;
            }
        }
    }
    //快应用数据接口 首页
    public function datalist(){
        $type       = $_GET['type'];
        $category   = $_GET['category'];
        if($type == 'day'){
            $open_time_begin  = strtotime(date("Y-m-d")." 00:00:00");
            $open_time_end    = strtotime(date("Y-m-d")." 23:59:59");
        }elseif($type == 'soon'){
            $open_time_begin  = strtotime(date('Y-m-d',strtotime("+ 1 day"))." 00:00:00");
            $open_time_end    = strtotime(date('Y-m-d',strtotime("+ 1 day"))." 23:59:59");
        }
        $where['category'] = !empty($category) ? $category : array('like',"%%");
        $where['open_time'] = array('between',"$open_time_begin,$open_time_end");
        $kyy = M('list');
        $sel_res = $kyy->where($where)->page($page,$limit)->select();
        foreach($sel_res as $k => $v){
            $sel_res[$k]['enter'] = "http://member.8090.com/game/game_h5.php?game=".$v['gname']."&username=";
            $sel_res[$k]['open_time'] = date('d日H时',$v['open_time']);
        }
        cookie('username','gy224542484');
        echo json_encode($sel_res);
    }
    //最近登录
    public function lately(){
        $username = $_GET['username'];
        $url = "http://data.8090.com/api/get_h5_info.php?username=$username&_=".time();
        $res = file_get_contents($url);
        $res_arr = json_decode($res,true);
        if(!empty($res_arr['gname'])){
            $data['ico'] = "http://h.8090.com".$res_arr['gico'];
            $data['gamename'] = $res_arr['gname'];
            $data['enter'] = "http://h.8090.com/enter/?quick_app=kyy&purl=kuaiyingyong&gname=".$res_arr['gnamejc'];
            echo json_encode($data);
        }else{
            echo 'null';
        }
        
    }
    //bannerlist
    public function bannerlist(){
        //查询banner数据 输出给快应用
        $where['status'] = 0;
        $sel_res = M('banner')->where($where)->field('imagePath,url')->select();
        foreach($sel_res as $k => $v){
            $sel_res[$k]['imagePath'] = "http://h.8090.com".$v['imagePath'];
            $sel_res[$k]['url'] = "http://member.8090.com/game/game_h5.php?game=".$v['url']."&username=";
        }
        echo json_encode($sel_res);
    }
    //del  删除
    public function del(){
        $id = $_GET['id'];
        if(empty($id)){
            $this->error('请选择需要删除的数据');
        }
        if($_GET['type'] == 'banner'){
            $list       = M('banner');
        }else{
            $list       = M('list');
        }
        $sel_res    = $list->where("id=$id")->delete();
        if($sel_res != false){
            echo json_encode(array("code" => 1,"msg" => "删除成功"));exit;
        }else{
            echo json_encode(array("code" => 1002,"msg" => "删除失败"));exit;
        }
    }
    //edit 编辑
    public function edit(){
        $data['id']         = $_POST['id'];
        $data['gserver']    = $_POST['gserver'];
        $data['open_time']  = strtotime($_POST['open_time']);
        $data['category']   = $_POST['category'];
        if(in_array('',$data)){
            echo json_encode(array('code' => 1001 , 'msg' => '请输入完整.' ));exit;
        }
        //查询是否没有进行修改
        $chk_data = M('list')->where($data)->select();
        if(!empty($chk_data)){
            echo json_encode(array('code' => 1001 , 'msg' => '您未进行任何修改.' ));exit;
        }else{
            //更新数据
            $up_res = M('list')->where("id=".$data['id'])->save($data);
            if(!empty($up_res)){
                echo json_encode(array('code' => 1 , 'msg' => '修改成功.' ));exit;
            }else{
                echo json_encode(array('code' => 1002 , 'msg' => '修改失败.' ));exit;
            }
        }
    }

    //edit 编辑
    public function banner_edit(){
        $data['id']         = $_POST['id'];
        $data['url']        = $_POST['url'];
        $data['imagePath']  = $_POST['imagePath'];
        if(in_array('',$data)){
            echo json_encode(array('code' => 1001 , 'msg' => '请输入完整.' ));exit;
        }
        $data['status']     = $_POST['status'] == 'on' ? 0 : 1 ;
        //查询是否没有进行修改
        $chk_data = M('banner')->where($data)->select();
        if(!empty($chk_data)){
            echo json_encode(array('code' => 1001 , 'msg' => '您未进行任何修改.' ));exit;
        }else{
            //更新数据
            $up_res = M('banner')->where("id=".$data['id'])->save($data);
            if(!empty($up_res)){
                echo json_encode(array('code' => 1 , 'msg' => '修改成功.' ));exit;
            }else{
                echo json_encode(array('code' => 1002 , 'msg' => '修改失败.' ));exit;
            }
        }
    }
}