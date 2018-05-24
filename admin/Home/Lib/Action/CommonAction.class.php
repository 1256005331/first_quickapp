<?php
class CommonAction extends Action
{
    public function _initialize()
    {
        //初始化的时候检查用户权限
        $username = cookie('username');
        $sign     = cookie('sign');
        if (!isset($username) || !isset($sign)) {
            if(substr(md5($username),10) !== $sign){
                $this->redirect('Login/login');
            }
        }
    }
}