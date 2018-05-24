<?php 
class GamModel extends Model{
			
	public function integral($userData){
		header('Cache-Control: max-age=0');
		$activetime = array("2018-01-20","2018-01-21","2018-01-22","2018-01-23","2018-01-24","2018-01-25","2018-01-26","2018-01-27","2018-01-28","2018-01-29","2018-01-30","2018-01-31");
		$btime      = strtotime("2018-01-20 00:00:00"); //开始时间
		$etime      = strtotime("2018-01-30 23:59:59"); //结束时间
		
		//试玩积分
		$Dbgname = M('weixin_180324_gname_log');
		$gnamelog['unionid'] = array("in",$userData);
		$gnamelogRs = $Dbgname->where($gnamelog)->field('unionid,gname,time')->select();

		
		foreach($userData as $k => $v){
			$gnameNum = 0;
			foreach($gnamelogRs as $a => $b){
				if($v === $b['unionid']){
					$gnameNum++;
				}
			}
			$gnameRs[$v] = $gnameNum*2;
		}
		

		//邀请积分
		$Dbrecommend = M('weixin_180324_recommendlog');
		$InviteLog['recommend'] = array("in",$userData);
		$InviteLog['istrue']    = 1;
		$InviteRs = $Dbrecommend->where($InviteLog)->field('recommend')->select();

		//邀请获取的积分
		foreach($userData as $k => $v){
			$InviteNum = 0;
			foreach($InviteRs as $a => $b){
				if($b['recommend'] == $v){
					$InviteNum++;
				}
			}
			$Invite[$v] = $InviteNum*2;
		}

		//互动积分
		$Dbgam = M('weixin_180324_gam_log');
		$gamLog['unionid'] = array("in",$userData);
		$gamRs = $Dbgam->where($gamLog)->field('unionid')->select();
		
		//获取互动积分
		foreach($userData as $k => $v){
			$gamNum = 0;
			foreach($gamRs as $a => $b){
				if($b['unionid'] == $v){
					$gamNum++;
				}
			}
			$gam[$v] = $gamNum*2;
		}
		//整合数据

		foreach($userData as $k => $v){
			$integral[$v] = array(
				"gam"    => ($gam[$v] == null) ? 0 : $gam[$v],
				"togame" => $gnameRs[$v],
				"Invite" => ($Invite[$v] == null) ? 0 : $Invite[$v],
			);
		}
		
		return $integral;
	}
	
}