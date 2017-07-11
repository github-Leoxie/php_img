<?php 
    
	class OPIMG{
		
		private $_old_img_path = null;
		private $_new_width	 = 0;//新图片的宽度
		private $_new_height = 0;//新图片的高度
		private $_old_width = 0;//原图片的宽度
		private $_old_height = 0;//原图片的高度
		private $_start_x = 0;
		private $_start_y = 0;
		
	  /**
		*description 图片操作类
		*/	
		public function __construct(){
		}
		
		/**
		  * description 创建等比例的图片
		  * @param sting $imgsrc 图片路径 
		  * @param string $imgdst 压缩后保存路径
		  * @param int $max_width 最大宽度
		  * @param int $max_height 最大高度
		  */
		public function createScalingImg($imgsrc,$imgdst,$max_width,$max_height){
			$this->_isExists($imgsrc);
			list($this->_old_width,$this->_old_height,$type) = getimagesize($imgsrc);
			$width_rate = $max_width/$this->_old_width;
			$height_rate = $max_height/$this->_old_height;
			$min_rate = $width_rate>$height_rate?$height_rate:$width_rate;
			
			$this->_new_width = $this->_old_width * $min_rate; 
			$this->_new_height =$this->_old_height * $min_rate;
			
			$this->createThumb($imgsrc,$imgdst,$type);
		}
		
		/**
		  * description 创建固定的图片
		  * @param sting $imgsrc 图片路径 
		  * @param string $imgdst 压缩后保存路径
		  * @param int $width 宽度
		  * @param int $height 高度
		  */
		public function createFixedImg($imgsrc,$imgdst,$width,$height){
			$this->_isExists($imgsrc);
			list($this->_old_width,$this->_old_height,$type) = getimagesize($imgsrc); 
			
			$this->_new_width = $width; 
			$this->_new_height =$height;
			
			$this->createThumb($imgsrc,$imgdst,$type);
		}
		
		/**
		  *网站背景裁剪
		  * @param int $part 多少部分
		  */
		public function createMutilImg($imgsrc,$imgdst,$part){
			$this->_isExists($imgsrc);
			list($this->_old_width,$this->_old_height,$type) = getimagesize($imgsrc); 
			
			$this->_new_width  = $this->_old_width; 
			$this->_new_height = $this->_old_height/$part;
			
			
			for($i=1;$i<$part;$i++){
				$this->_start_y = $this->_new_height*$i;
				$index = strripos($imgdst,'.');
				$part_imgdst = substr($imgdst,0,$index)."_$i".substr($imgdst,$index);
                $this->createCrop($imgsrc,$part_imgdst,$type);
			}
		}
		
		/** 
		* desription 压缩图片 
		* @param sting $imgsrc 图片路径 
		* @param string $imgdst 压缩后保存路径
		* @param int 图片类型
		*/
		private function createThumb($imgsrc,$imgdst,$type){
		  $this->_old_img_path = $imgsrc;
		  switch($type){ 
			case 1: 
				//header('Content-Type:image/gif');
				$image_wp=imagecreatetruecolor($this->_new_width, $this->_new_height); 
				$image = imagecreatefromgif($imgsrc); 
				imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $this->_new_width, $this->_new_height, $this->_old_width, $this->_old_height); 
				imagejpeg($image_wp, $imgdst,100); 
				imagedestroy($image_wp);
                break; 				
			case 2: 
			  //header('Content-Type:image/jpeg'); 
			  $image_wp=imagecreatetruecolor($this->_new_width, $this->_new_height); 
			  $image = imagecreatefromjpeg($imgsrc); 
			  imagecopyresampled($image_wp, $image, 0, 0,0, 0, $this->_new_width, $this->_new_height, $this->_old_width, $this->_old_height); 
			  imagejpeg($image_wp, $imgdst,100); 
			  imagedestroy($image_wp); 
			  break; 
			case 3: 
			  //header('Content-Type:image/png'); 
			  $image_wp=imagecreatetruecolor($this->_new_width, $this->_new_height); 
			  $image = imagecreatefrompng($imgsrc); 
			  imagecopyresampled($image_wp, $image, 0, 0,0, 0, $this->_new_width, $this->_new_height, $this->_old_width, $this->_old_height); 
			  imagejpeg($image_wp, $imgdst,100); 
			  imagedestroy($image_wp); 
			  break; 
		  } 
		} 
		
		
		/** 
		* desription 裁剪图片 
		* @param sting $imgsrc 图片路径 
		* @param string $imgdst 压缩后保存路径
		* @param int 图片类型
		*/
		private function createCrop($imgsrc,$imgdst,$type){
		  $this->_old_img_path = $imgsrc;
		  switch($type){ 
			case 1: 
				//header('Content-Type:image/gif');
				$image_wp=imagecreatetruecolor($this->_new_width, $this->_new_height); 
				$image = imagecreatefromgif($imgsrc); 
				imagecopy($image_wp, $image, 0, 0, $this->_start_x, $this->_start_y, $this->_new_width, $this->_new_height); 
				imagejpeg($image_wp, $imgdst,100); 
				imagedestroy($image_wp);
                break; 				
			case 2: 
			  //header('Content-Type:image/jpeg'); 
			  $image_wp=imagecreatetruecolor($this->_new_width, $this->_new_height); 
			  $image = imagecreatefromjpeg($imgsrc); 
			  imagecopy($image_wp, $image, 0, 0, $this->_start_x, $this->_start_y, $this->_new_width, $this->_new_height); 
			  imagejpeg($image_wp, $imgdst,100); 
			  imagedestroy($image_wp); 
			  break; 
			case 3: 
			  //header('Content-Type:image/png'); 
			  $image_wp=imagecreatetruecolor($this->_new_width, $this->_new_height); 
			  $image = imagecreatefrompng($imgsrc); 
			  imagecopy($image_wp, $image, 0, 0, $this->_start_x, $this->_start_y, $this->_new_width, $this->_new_height); 
			  imagejpeg($image_wp, $imgdst,100); 
			  imagedestroy($image_wp); 
			  break; 
		  } 
		} 
		
		
		
		public function removeOldImg(){
			//删除原图片
			unlink($this->_old_img_path);
		}
		
		private function _isExists($imgsrc){
			if(!file_exists($imgsrc)){
				echo "imgsrc is not found\n";
				exit;
			}
		}
	}
	
	//实例化对象
    $img_op = new OPIMG();
	//创建固定大小的图片
	$img_op->createFixedImg('./share.gif',"./share_fixed.gif",100,100);
	//按照等比缩放图片
	$img_op->createScalingImg('./share.gif',"./share_scaling.gif",100,100);
	//用于纵向裁剪图片
	$img_op->createMutilImg('./share.gif',"./share_mutli.gif",4);
	echo "create ok!\n";
	//删除原图片
	$img_op->removeOldImg();
	echo "remove ok!\n";
?>