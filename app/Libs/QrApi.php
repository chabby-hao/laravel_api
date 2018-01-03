<?php

namespace App\Libs;


class QrApi
{

    private $_appId = 'qr283846';
    private $_appKey = 'aKGEOvVTJnAKss';

    /**
     * @param null|string $appId
     * @return QrApi
     */
    public function setAppId($appId)
    {
        $this->_appId = $appId;
        return $this;
    }

    /**
     * @param null|string $appKey
     * @return QrApi
     */
    public function setAppKey($appKey)
    {
        $this->_appKey = $appKey;
        return $this;
    }

    public function __construct($appId = null, $appKey = null)
    {
        if ($appId && $appKey) {
            $this->setAppId($appId)->setAppKey($appKey);
        }
    }

    /*
   图片转base64
   */
    public static function base64EncodeImage($image_file)
    {
        $image_info = getimagesize($image_file);
        $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
        return $base64_image;
    }

    public function qrdecode($imgPath)
    {
        $timestamp = time();
        $imgurl = '';//'http://www.wwei.cn/static/images/qrcode.jpg';//远程图片
        $imgdata = self::base64EncodeImage($imgPath);//本地图片
        //echo '<img src="' . $imgdata . '" />';exit;
        $signature = md5($this->_appKey . $timestamp . $imgurl . $imgdata);//简易数据签名
        $client = \Hprose\Client::create('http://hprose.wwei.cn/qrcode.html', false);// true 异步   false同步
        $result = $client->qrdecode($this->_appId, $signature, $timestamp, $imgurl, $imgdata);
        if(is_array($result) && $result['status'] == 1){
            return $result['data']['raw_text'];
        }
        return false;
        /*
         *
array(3) {
  ["status"]=>
  int(1)
  ["data"]=>
  array(2) {
    ["raw_text"]=>
    string(65) "https://mp.weixin.qq.com/a/~~oX1m0_cQdCY~RgLqIxiElAPmb7OWuU_dPA~~"
    ["raw_type"]=>
    string(7) "QR-Code"
  }
  ["msg"]=>
  string(7) "success"
}
         *
         */
    }


}