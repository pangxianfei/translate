<?php

namespace Tmaic\Translate;


use Tmaic\Translate\Exceptions\TranslateException;

class TranslateService implements TranslateInterface
{



    private $driver;
    private $spare_driver;
    private $config =[];
    private $options=[];
    private $from;
    private $to;
    public $source = false;




    public function __construct($config=[])
    {
        $this->config=$config;
        if(!count($config)){
            $this->config = include(__DIR__.'/../config/translate.php');
        }

        $this->driver = $this->config['defaults']['driver'];
        $this->spare_driver = $this->config['defaults']['spare_driver'];
        $this->from = $this->config['defaults']['from'];
        $this->to = $this->config['defaults']['to'];
    }



    public function setDriver($driver){

        $this->driver = $driver;
        return $this;
    }



    public function setFromAndTo($from,$to){

        $this->from = $from;
        $this->to = $to;
        return $this;
    }


    public function setHttpOption($options = []){
        $this->options = $options;
        return $this;
    }


    /**
     * 执行翻译
     * @author Tmaic
     * @email 421339244@qq.com
     * @param $string
     * @param bool $source 原数据，针对google
     * @return mixed
     * @throws TranslateException
     */
    public function translate($string,$source=false)
    {
        $this->source=$source;
        try {
            return $this->sendTranslate($string, $this->driver);
        } catch (\Exception $e) {
            //自动切换为备用渠道
            return $this->sendTranslate($string, $this->spare_driver);
        }
    }


    /**
     * 执行请求
     * @author Tmaic
     * @email 421339244@qq.com
     * @param $string
     * @param $driver
     * @return mixed
     * @throws TranslateException
     */
    protected function sendTranslate($string,$driver){

        $appKey = $this->config['drivers'][$driver]['app_key'];
        $appId = $this->config['drivers'][$driver]['app_id'];
        $baseUrl = $this->config['drivers'][$driver]['base_url'];
        switch ($driver){
            case 'baidu':
                $obj = new Baidu($appId,$appKey,$this->from,$this->to,$baseUrl,$this->options);
                break;
            case 'youdao':
                $obj = new YouDao($appId,$appKey,$this->from,$this->to,$baseUrl,$this->options);
                break;
            case 'google':
                $obj = new Google($appId,$appKey,$this->from,$this->to,$baseUrl,$this->options);
                break;
            default:
                throw new TranslateException(10003);
        }

        return $obj->translate($string, $this->source);
    }



    /**
     * @author Tmaic
     * @email 421339244@qq.com
     * @param $attr
     * @param $value
     * @return $this
     */
    public function __set($attr,$value)
    {
        $this->$attr = $value;
        return $this;
    }


    /**
     * @author Tmaic
     * @email 421339244@qq.com
     * @param $attr
     * @return mixed
     */
    public function __get($attr)
    {
        return $this->$attr;
    }



}
