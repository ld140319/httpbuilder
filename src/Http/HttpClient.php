<?php

namespace Ethansmart\HttpBuilder\Http;

use Ethansmart\HttpBuilder\Log\Log;

/**
 * Class HttpClient
 * @package Ethansmart\HttpBuilder\Http
 * Support Http Method : GET, POST, PUT , DELETE , HEADER , TRACE
 */

class HttpClient
{
    private $ch ;
    private $url ;
    private $method ;
    private $params ;
    private $timeout;
    protected $multipart ;
    protected $dns_cache_timeout;
    private $log;

    public function __construct($logInstance = null)
    {
        $this->timeout = 120 ;
        $this->dns_cache_timeout = 1800 ;
        $this->log = new Log();
        if(!empty($logInstance)){
            $this->log = $logInstance;
        }
    }

    public function Get($data)
    {
        $data['method'] = "GET";
        return $this->performRequest($data);
    }

    public function Post($data)
    {
        $data['method'] = "POST";
        return $this->performRequest($data);
    }

    public function Put($data)
    {
        $data['method'] = "PUT";
        return $this->performRequest($data);
    }

    public function Delete($data)
    {
        $data['method'] = "DELETE";
        return $this->performRequest($data);
    }

    public function Upload($data)
    {
        $data['method'] = "POST";
        $this->multipart = true;
        return $this->performRequest($data);
    }

    public function Head($data)
    {
        $data['method'] = "HEAD";
        return $this->performRequest($data);
    }


    public function Trace($data)
    {
        $data['method'] = "TRACE";
        return $this->performRequest($data);
    }

    /**
     * Http 请求
     * @param $data
     * @return array
     */
    public function performRequest($data)
    {
        // 初始cURL
        $this->ch = curl_init();
        $url = $data['url'];

        // 数据验证
        try {
            $this->dataValication($data);
        } catch (\Exception $e) {
            return ['code'=>-1, 'msg'=>$e->getMessage()];
        }

        // cURL设置
        $timeout = isset($data['timeout'])?$data['timeout']:$this->timeout;
        $headers = $this->setHeaders($data);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout); // 设置超时时间
        curl_setopt($this->ch, CURLOPT_HEADER, true); // 将头文件的信息作为数据流输出
        curl_setopt($this->ch, CURLINFO_HEADER_OUT, true); // 追踪句柄的请求字符串
        if (!empty($headers)) {curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);} // 设置HTTP头信息
        curl_setopt($this->ch, CURLOPT_NOBODY, false); // 输出body部分
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true); // 获取的信息以字符串返回
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 0); // 在尝试连接时等待的秒数。设置为0，则无限等待
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $this->method); //设置请求方式
        curl_setopt($this->ch, CURLOPT_DNS_CACHE_TIMEOUT, $this->dns_cache_timeout); //设置DNS缓存时间,减少时延
        $this->setExecuteTime(0);   // 设置执行时间为没有限制

        // 设置主体(body)参数
        if ($this->method=="GET") {
            if(strpos($this->url,'?')){
                $this->url .= http_build_query($this->params);
            }else{
                $this->url .= '?' . http_build_query($this->params);
            }
        }else{
            $params = (is_array($this->params)||is_object($this->params))?http_build_query($this->params):$this->params;
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->multipart?$this->params:$params);
        }

        // 设置URI:URL
        curl_setopt($this->ch, CURLOPT_URL, $this->url);

        if (1 == strpos('$'.$this->url, "https://")) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        // 执行cURL
        $result = curl_exec($this->ch);

        // 结果判断
        if(!curl_errno($this->ch)){ //if (curl_getinfo($this->ch, CURLINFO_HTTP_CODE) == '200'){}
            list($response_header, $response_body) = explode("\r\n\r\n", $result, 2);
            $this->log::info("Request Headers: ". json_encode($response_header));
            $this->log::info("Request Body :".json_encode($response_body));
            $contentType = curl_getinfo($this->ch, CURLINFO_CONTENT_TYPE);

            $info = curl_getinfo($this->ch);
            $this->log::info('耗时 ' . $info['total_time'] . ' Seconds 发送请求到 ' . $info['url']);
            $response = ['code'=>0, 'msg'=>'OK', 'data'=>$response_body, 'contentType'=>$contentType];
        }else{
            $this->log::info('Curl error: ' . curl_error($this->ch)) ;
            $response = ['data'=>(object)['code'=>-1, 'msg'=>"请求 $url 出错: Curl error: ". curl_error($this->ch)]];
        }

        // 关闭连接
        curl_close($this->ch);

        // 返回数据
        return $response;
    }

    /**
     * 设置Header信息
     * @param $data
     * @return array
     */
    public function setHeaders($data)
    {
        $headers = array();
        if (isset($data['headers'])) {
            foreach ($data['headers'] as $key=>$item) {
                $headers[] = "$key:$item";
            }
        }

        $headers[] = "Expect:"; // libcurl 会将大于1k的数据加上 Expect:100-continue, Client默认去掉
        return $headers;
    }

    /**
     * 超时设置
     * @param $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        if (!empty($timeout) || $timeout != 30) {
            $this->timeout = $timeout ;
        }

        return $this;
    }

    /**
     * 执行时间设置
     * @param $second
     */
    protected function setExecuteTime($second)
    {
        ini_set('max_execution_time',$second);// 秒,0 设置为执行时间没有限制
    }

    /**
     * 数据验证
     * @param $data
     * @throws \Exception
     */
    public function dataValication($data)
    {
        // url验证
        if(!isset($data['url']) || empty($data['url'])){
            throw new \Exception("HttpClient Error: Uri不能为空", 4422);
        }else{
            $this->url = $data['url'];
        }

        // 参数验证
        if(!isset($data['params']) || empty($data['params'])){
            $this->params = [];
        }else{
            $this->params = $data['params'];
        }

        // 方法设置
        if(!isset($data['method']) || empty($data['method'])){
            $this->method = "POST";
        }else{
            $this->method = $data['method'];
        }
    }
}