<?php
class IndexAction extends CommonAction
{
	//加载首页
    public function index()
    {
        $this->display();
    }
    
    //左侧主菜单
    public function leftlist(){
        $data = array(
            'data' => array(
                "text"=>"快应用",
                "icon"=>"&#xe756;",
                "subset"=>array(
                    array("text"=>"快应用开服表",
                    "icon"=>"&#xe62a;",
                    "href"=>U('Index/data')),
                    array("text"=>"快应用BANNER图片",
                    "icon"=>"&#xe62a;",
                    "href"=>U('Index/banner'))
                )
            )
        );
        echo json_encode($data);
    }
    
    //左上菜单
    public function lefttoplist(){
        $data = array(
            'data' => array(
                "text" => "清除缓存",
                "icon" => "&#xe621;",
                "href" => "Javascript:clear_cache;"
            )
        );
        echo json_encode($data);
    }
    
    //清除缓存
    public function delCache()
    {
        
        function delDirAndFile( $dirName )
        {
            if ( $handle = opendir( "$dirName" ) ) {
                while ( false !== ( $item = readdir( $handle ) ) ) {
                    if ( $item != "." && $item != ".." ) {
                        if ( is_dir( "$dirName/$item" ) ) {
                            delDirAndFile( "$dirName/$item" );
                        } else {
                            if( unlink( "$dirName/$item" ) );
                        }
                    }
                }
                closedir( $handle );
                if (rmdir($dirName)) {
                    returnjson(0,"缓存清除成功","");
                } else {
                    returnjson(500,"缓存清除失败","");
                }
            }
        }
        $dirName = RUNTIME_PATH;
        delDirAndFile($dirName);
    }
    //加载开服游戏列表页面
    public function data()
    {
        $this->display('data-table');
    }

    public function banner(){
        $this->display('data-banner');
    }

    //加载功能页面
    public function log()
    {
        $this->display('log');
    }
	//清除缓存
    public function clear_cache()
    {
        //更新缓存
        $this->assign('jumpUrl', U('Index/index'));
        $this->success('更新缓存成功!');
    }
	
    //密码修改
    public function pass()
    {
        //修改密码
        if ($this->isPost()) {
            $password = $_POST['newpass'];
            $rpassword = $_POST['renewpass'];
            if ($password != $rpassword) {
                $this->error("前后两次密码不一致");
            } else {
                $adminAccountInfo = M('170428_admin')->where(array('username' => 'admin'))->find();
                if (!$adminAccountInfo) {
                    $this->error("账户不存在");
                } else {
                    $newPwd = $password;
                    $adminAccountInfo['password'] = $newPwd;
                    M('170428_admin')->save($adminAccountInfo);
                    //		echo M('admin')->getLastsql();
                    //exit;
                    $this->success("修改成功");
                }
            }
        } else {
            $this->display();
        }
    }

    //添加H5开服数据
    public function h5gameadd(){
        $data['gamename']       = $_POST['gamename'];
        $data['gserver']        = $_POST['gserver'];
        $data['category']       = $_POST['category'];
        
        if(in_array('',$data)){
            echo json_encode(array('code'=>1001,'msg'=>'请完善添加信息'));exit;
        }
        //通过gamename  请求接口 获取gname ico 
        $url = 'http://yutang.8090.com/app.php/Game/get_h5game_lists_by_name/version/1/name/'.$data['gamename'];
        $gamename_res = doGet($url);
        preg_match_all('/{(.*?)]}/',$gamename_res, $matches);
        $str = "{".$matches[1][0]."]}";
        $gamename_arr = json_decode($str,true);
        if(isset($gamename_arr['data'][1]) || $gamename_arr['status'] == -1){
            echo json_encode(array('code'=>1001,'msg'=>'游戏名称输入有误'));exit;
        }else{
            preg_match_all('/(.*?)[H5(]/',$gamename_arr['data'][0]['game_name'],$zzres);
            $data['gamename'] = $zzres[1][0];
            $data['ico'] = $gamename_arr['data'][0]['icon'];
            $data['gname'] = $gamename_arr['data'][0]['tag'];
        }
        //入库
        $kyy = M('list');
        //先进行查询是否已存在该数据
        $chk_res = $kyy->where($data)->find();
        if(!$chk_res){
            $data['open_time']       = strtotime($_POST['opentime']);
            $data['creation_time']  = time();
            $insert = $kyy->add($data);
            if($insert){
                echo json_encode(array('code'=>1,'msg'=>'添加成功'));exit;
            }else{
                echo json_encode(array('code'=>1001,'msg'=>'添加失败'));exit;
            }
        }else{
            echo json_encode(array('code'=>1002,'msg'=>'已存在，请核对后重新添加'));exit;
        }

    }

    //list_edit 
    public function list_edit(){
        $id = $_GET['id'];
        if(empty($id)){
            $this->error('请选择需要修改的数据');
        }
        //查询数据并输出
        $sel_res = M('list')->where("id=$id")->find();
        $sel_res['open_time'] = date('Y-m-d H:i:s',$sel_res['open_time']);
        $this->assign('list',$sel_res);
        $this->display('list_edit');
    }

    public function banner_edit(){
        $id = $_GET['id'];
        if(empty($id)){
            $this->error('请选择需要修改的数据');
        }
        //查询数据并输出
        $sel_res = M('banner')->where("id=$id")->find();
        $this->assign('list',$sel_res);
        $this->display('banner_edit');
    }
}