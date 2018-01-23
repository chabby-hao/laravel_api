<?php

namespace App\Libs;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WxApi
{

    private $appId = \WxPayConfig::APPID;
    private $appSecret = \WxPayConfig::APPSECRET;

    public function sendMessage($json)
    {
        is_array($json) && $json = json_encode($json);
        $accessToken = $this->getAccessToken();
        $uri = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=$accessToken";
        $client = new Client();
        $res = $client->post($uri, ['body' => $json]);
        $body = $res->getBody()->getContents();
        $data = json_decode($body, true);
        Log::info('send message token: ' . $accessToken . ' json : ' . $json . ' res: ' . $body);
        if ($data['errcode'] !== 0) {
            return false;
        }
        return true;
    }

    public function getQrImg($deviceId)
    {
        $accessToken = $this->getAccessToken();
        $uri = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=$accessToken";
        $client = new Client();
        $json = '{"path": "pages/index/index?deviceId=' . $deviceId . '", "width": 500}';
        $res = $client->post($uri, ['headers' => ['Content-Type:application/json'], 'body' => $json]);
        $body = $res->getBody()->getContents();
        $filename = "image/qr/device-$deviceId.jpg";
        $file = public_path($filename);
        $imgUrl = env('APP_URL') . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($file, $body);
        return ['img_url' => $imgUrl, 'img_path' => $file];
    }

    public function getQrImgForCards($cardId)
    {
        $accessToken = $this->getAccessToken();
        $uri = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=$accessToken";
        $client = new Client();
        $json = '{"path": "pages/index/index?cardId=' . $cardId . '", "width": 500}';
        $res = $client->post($uri, ['headers' => ['Content-Type:application/json'], 'body' => $json]);
        $body = $res->getBody()->getContents();
        $filename = "image/qr/card-$cardId.jpg";
        $file = public_path($filename);
        $imgUrl = env('APP_URL') . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($file, $body);
        return ['img_url' => $imgUrl, 'img_path' => $file];
    }

    public function getAccessToken()
    {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode($this->get_php_file(base_path('access_token.php')));
        if ($data->expire_time < time()) {
            // 如果是企业号用以下URL获取access_token
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $res = json_decode($this->httpGet($url));
            $access_token = $res->access_token;
            if ($access_token) {
                $data->expire_time = time() + 7000;
                $data->access_token = $access_token;
                $this->set_php_file(base_path('access_token.php'), json_encode($data));
            }
        } else {
            $access_token = $data->access_token;
        }
        return $access_token;
    }

    private function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

    private function get_php_file($filename)
    {
        if (!file_exists($filename)) {
            $this->set_php_file($filename, json_encode(['expire_time' => 0]));
        }
        return trim(substr(file_get_contents($filename), 15));
    }

    private function set_php_file($filename, $content)
    {
        $fp = fopen($filename, "w");
        fwrite($fp, "<?php exit();?>" . $content);
        fclose($fp);
    }
}