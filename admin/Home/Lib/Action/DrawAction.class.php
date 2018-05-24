<?php
class DrawAction extends CommonAction
{
    public function draw()
    {
        $this->display('draw');
    }
    public function dodraw()
    {
        $page = $_GET['page'];
        $limit = $_GET['limit'];
        $like = $_GET['like'] ? $_GET['like'] : "";
        $like = isset($like) ? $like : "" ;
        $userinfo = M('weixin_180324_user');
        $where['unionid'] = array("like", "%" . $like . "%");
        if ($_GET['export'] == 1) {
            $userArr = $userinfo->where($where)->field('unionid')->select();
        } else {
            $userArr = $userinfo->where($where)->page($page, $limit)->field('unionid')->select();
        }
        foreach ($userArr as $k => $v) {
            $userData[] = $v['unionid'];
        }
        $drawLoginfo = M('weixin_180324_drawlog');
        $drawLog['unionid'] = array("in", $userData);
        $drawNum = $drawLoginfo->where($drawLog)->field('unionid,prize')->select();
        foreach ($drawNum as $k => $v) {
            $drawRs[] = $v['unionid'];
        }
        $userDrawNum = array_count_values($drawRs);
        //总共红包奖励
        foreach ($userData as $k => $v) {
            $money = 0;
            foreach ($drawNum as $a => $b) {
                switch($b['prize']){
                    case "1":
                      $prize_log = 0;
                      break;
                    case "2":
                    $prize_log = 6.6;
                      break;
                    case "3":
                      $prize_log = 8.8;
                      break;
                    case "4":
                      $prize_log = 16.8;
                      break;
                    case "5":
                      $prize_log = 28.8;
                      break;
                    case "6":
                      $prize_log = 51.8;
                      break;
                  }
                if ($b['unionid'] == $v) {
                    $money = $prize_log + $money;
                }
            }
            $prizeNum[$v] = $money;
        }
        //查询昵称
        $nicknameData['unionid'] = array("in", $userData);
        $nickname = M("concern_user", "yt_")->where($nicknameData)->field('unionid,nickname')->select();
        //积分
        $gamModel = D('Gam');
        $integral = $gamModel->integral($userData);
        foreach ($nickname as $k => $v) {
            if (empty($v['unionid'])) {
                unset($nickname[$k]);
            } elseif (array_key_exists($v['unionid'], $integral)) {
                array_push($nickname[$k], $integral[$v['unionid']], $prizeNum[$v['unionid']]);
            } else {
                echo '111';
            }
        }
        foreach ($nickname as $k => $v) {
            $allintegral = $v[0]['gam'] + $v[0]['togame'] + $v[0]['Invite'];
            if (array_key_exists($v['unionid'], $userDrawNum)) {
                $nowAll = $allintegral - $userDrawNum[$v['unionid']] * 10;
            } else {
                $nowAll = $allintegral;
            }
            $prize = isset($v[1]) ? $v[1] : 0;
            //合并每个用户的数据
            $draw[] = array(
                "unionid" => $v['unionid'],
                //ID
                "nickname" => $v['nickname'],
                //昵称
                "gam" => $v[0]['gam'] == null ? 0 : $v[0]['gam'],
                //互动积分
                "togame" => $v[0]['togame'] == null ? 0 : $v[0]['togame'],
                //试玩积分
                "Invite" => $v[0]['Invite'] == null ? 0 : $v[0]['Invite'],
                //邀请积分
                "Integral" => $nowAll,
                //剩余积分
                "all" => $v[0]['gam'] + $v[0]['togame'] + $v[0]['Invite'],
                "prize" => $prize,
            );
        }
        if ($_GET['export'] == 1) {
            $header_data = array('id', '微信名', '互动积分', '试玩积分', '邀请积分', '总积分', '剩余积分', '总共话费奖励');
            $this->export_csv_2($draw, $header_data, 'choujiang.csv');
            exit;
        }
        //查询有多少用户 count数据总条数
        $count = $userinfo->count('unionid');
        echo json_encode(array("code" => 0, "msg" => "", "count" => $count, "data" => $draw));
    }
    /**
     * 导出CSV文件
     * @param array $data        数据
     * @param array $header_data 首行数据
     * @param string $file_name  文件名称
     * @return string
     */
    public function export_csv_2($data = array(), $header_data = array(), $file_name = '')
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $file_name);
        header('Cache-Control: max-age=0');
        $fp = fopen('php://output', 'a');
        if (!empty($header_data)) {
            foreach ($header_data as $key => $value) {
                $header_data[$key] = iconv('utf-8', 'gbk', $value);
            }
            fputcsv($fp, $header_data);
        }
        $num = 0;
        //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 100000;
        //逐行取出数据，不浪费内存
        $count = count($data);
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $num++;
                //刷新一下输出buffer，防止由于数据过多造成问题
                if ($limit == $num) {
                    ob_flush();
                    flush();
                    $num = 0;
                }
                $row = $data[$i];
                foreach ($row as $key => $value) {
                    $row[$key] = iconv('utf-8', 'gbk', $value);
                }
                fputcsv($fp, $row);
            }
        }
        fclose($fp);
    }
    public function addgamlog()
    {
        $num = $_POST['pass'];
        $unionid = $_POST['unionid'];
        if (empty($num) || empty($unionid)) {
            echo json_encode(array("status" => 1, "msg" => "网络超时"));
            exit;
        }
        for ($i = 0; $i < $num; $i++) {
            $array[] = array("unionid" => $unionid, "time" => date('Y-m-d h:m:s'));
        }
        $gamlog = M('weixin_180324_gam_log');
        $gamlogsql = $gamlog->addAll($array);
        if ($gamlogsql !== false) {
            echo json_encode(array("status" => 0, "msg" => "添加成功"));
            exit;
        } else {
            echo json_encode(array("status" => 1, "msg" => "添加失败"));
            exit;
        }
    }
}