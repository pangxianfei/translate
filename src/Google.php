<?php
namespace Tmaic\Translate;

use GuzzleHttp\Client;
use Tmaic\Translate\Exceptions\TranslateException;
use Stichoza\GoogleTranslate\TranslateClient;

class Google implements TranslateInterface
{

    protected static $language = [
        '' =>'auto',   //中文
        'auto' =>'auto',   //中文
        'zh' =>'zh-CN',   //中文
        'hk' =>'zh-TW',   //繁体
        'en' =>'EN',   //英文
        'jp' =>'jp',   //日文
        'ko' =>'kor',  //韩文
        'fr' =>'fra',  //法语
        'ru' =>'ru',   //俄语
        'es' =>'spa',  //西班牙语
        'pt' =>'pt',  //葡萄牙语
    ];



    private $app_id;
    private $app_key;
    private $base_url;
    private $options;
    private $httpClient;
    private $from;
    private $to;
    public $source = false;


    public function __construct($app_id,$app_key,$from,$to,$base_url,$options=[])
    {
        $this->app_id = $app_id;
        $this->app_key = $app_key;
        $this->from = $this->checkLanguage($from);
        $this->to = $this->checkLanguage($to);
        $this->base_url = $base_url;
        $this->options = $options;
    }


    /**
     * @author Tmaic
     * @email 421339244@qq.com
     * @param $string
     * @param bool $source  返回原数据结构
     * @return mixed
     */
    public function translate($string,$source=false)
    {
        $driver = new TranslateClient( $this->from,  $this->to,$this->options);
        $driver->setUrlBase($this->base_url);
        if($source){
           $result= $driver->getResponse($string);
        }else{
            $result=  $driver->translate($string);
        }
        return $result;

    }




    /**
     * @author Tmaic
     * @email 421339244@qq.com
     * @param $language
     * @return mixed
     * @throws TranslateException
     */
    private static function checkLanguage($language)
    {

        if (!isset(self::$language[$language])) {
            throw new TranslateException('10000');
        }

        return self::$language[$language];
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