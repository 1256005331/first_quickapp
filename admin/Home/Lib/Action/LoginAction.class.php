<?php
class LoginAction extends Action
{
	//渲染登录页
    public function login()
    {
        $this->display();
    }
	
	//登录操作
    public function doLogin()
    {
        //	dump($_POST);
        $username = $_POST['username'];
        $password = $_POST['password'];
        $code     = $_POST['code'];
        import('ORG.Util.Verify');
        $Verify = new Verify();
        $test_code = $Verify->check($code);
        if (!$test_code) {
            returnjson(500,'验证码错误','');
        }
        if ($username === "admin" && $password === "admin8090") {
            cookie('username',$username,3600);
            cookie('sign',substr(md5($username),10),3600);
            returnjson(0,'登录成功',U('Index/index'));
        } else {
            returnjson(500,'登录失败','');
        }
    }
	
	//登出操作
    public function doLogout()
    {
        $username = cookie('username');
        $sign     = cookie('sign');
        cookie(null,'username');
        cookie(null,'sign');
        if(!isset($username) || !isset($sign)){
            returnjson(500,"退出登录失败","");
        }else{
            returnjson(0,"退出成功","");
        }
        
    }
}