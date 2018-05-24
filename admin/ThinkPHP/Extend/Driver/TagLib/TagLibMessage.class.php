<?php
	Class TagLibMessage extends TagLib{
		protected $tags=array(
		'code'=>array('attr'=>'width,height','close'=>0),
		);
		public function _code($attr){
			$tag = $this->parseXmlAttr($attr,'code');
			$width = $tag['width'];
			$height =$tag['height'];
			$str="<img src='__APP__/Public/code?w={$width}&h={$height}' onclick='this.src=this.src+\"?\"+Math.random()'/>";
			return $str;
		}
}
?>