<?php
/**
 *@depict：           公共类库
 *@author：           GAOYANG 
 **/
/**
*@depict：       返回json
*@name：         returnjson
*@param：        $status,$message,$data[状态,返回的信息,数据]
*@author:        GAOYANG
**/
function returnjson($status, $message, $data)
{
    if (!isset($data)) {
        $data = null;
    }
    $jsonArr = array("code" => $status, "msg" => $message, "data" => $data);
    echo json_encode($jsonArr);
    exit;
}
/**
*@depict：       按几率得出结果
*@name：         get_rand
*@param：        $proArr 概率一维数组   value加起来等于1000
*@author:        gaoyang
**/
function get_rand($proArr)
{
    $result = '';
    //概率数组的总概率精度
    $proSum = array_sum($proArr);
    //概率数组循环
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);
        if ($randNum <= $proCur) {
            $result = $key;
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset($proArr);
    return $result;
}


/**
 *@depict：       curl请求
 *@name：         doGet
 *@param：        $url  [type=string]
 *@author:        gy
 **/
function doGet($url)
{
    header('content-type:application/json;charset=utf8');
    //初始化
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    // 执行后不直接打印出来
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    // 跳过证书检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // 不从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    //执行并获取HTML文档内容
    $output = curl_exec($ch);
    //释放curl句柄
    curl_close($ch);
    return $output;
}


/*-----------------------------------------------------------
*获取用户IP
------------------------------------------------------------*/
function getIP()
{
    static $realip;
    if (isset($_SERVER)) {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")) {
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }
    return $realip;
}