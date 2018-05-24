<?php
class LogAction extends CommonAction
{
    public function draw()
    {
        $this->display('draw');
    }
    public function dodraw()
    {
        $like = $_GET['like'] ? $_GET['like'] : "";
        $like = isset($like) ? $like : "" ;
        $userinfo = M('weixin_180324_recommendlog');
        $where['recommend'] = array("like", "%" . $like . "%");
        $userArr = $userinfo->where($where)->select();
        $user_ty = M('concern_user','yt_');
        foreach($userArr as $k => $v){
            //查询昵称
            $selNickname['unionid'] = $v['unionid'];
            $selUnionid = $user_ty->where($selNickname)->field('nickname')->select();
            $userArr[$k]['unionid_nickname'] = $selUnionid[0]['nickname'];
            $selNickname['unionid'] = $v['recommend'];
            $selUnionid = $user_ty->where($selNickname)->field('nickname')->select();
            $userArr[$k]['recommend_nickname'] = $selUnionid[0]['nickname'];
        }
        $userCount = $userinfo->count();
        echo json_encode(array("code" => 0, "msg" => "", "count" => $userCount, "data" => $userArr));
    }
}