<?php
defined('BASEPATH') OR exit('No direct script access allowed');
Class UserApi extends CI_Controller{
    protected $appid ='ab1dd6dba0a241aeff4dbc377643d0c6';
    protected $appsecret = '02f9c1e244a9600db401eb7effd6fb25';
    protected $token="";
    public function __construct(){
        parent::__construct();
        $this->token=$this->getToken();
        $this->load->model('Newmem_model');
    }

    public function apiEntry(){
        $size=10;
        $index=$this->input->get('page')?$this->input->get('page'):0;
        $from=$index*$size;
        $limit=($index+1)*$size-1;
        $data = $this->Newmem_model->getAllMem($from,$limit);
        //var_dump($data);
        $data['pagenum']=ceil($data['num']/$size);
        $this->load->view('form',$data);
    }

    public function getToken(){
        if(file_exists('/tmp/token.log')&&$this->isUpdate()){
            return file_get_contents('/tmp/token.log');
        }
        $url = 'https://open.weimob.com/common/token?grant_type=client_credential';
        $data = array(
            'appid'=>$this->appid,
            'secret'=>$this->appsecret
        );
        $url = $url."&".http_build_query($data);
        //echo file_get_contents($url);exit;
        $ret_data = $this->httpGet($url);
        $ret_array= json_decode($ret_data,TRUE);

        if($ret_array['code']['errcode']==0){
            $this->token=$ret_array['data']['access_token'];
            file_put_contents('/tmp/token.log',$this->token);
            return $this->token;
        }else{
            return 2;
        }
    }

    public function isUpdate(){
        $mtime=filemtime('/tmp/token.log');
        $mtime=intval($mtime+7200);
        //var_dump($mtime);
        $nowtime =time();
        //var_dump($nowtime);
        if($mtime>$nowtime){
            return true;
        }else{
            return false;
        }
    }

    protected function httpPost($url="",$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    protected function httpGet($url){
        $ch = curl_init();
        //curl_setopt($ch, CURLOPT_POST, 1);
        //echo $url;exit;
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $result = curl_exec($ch);
        curl_close($ch);
        //curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,true); ;
        // curl_setopt($ch,CURLOPT_CAINFO,dirname(__FILE__).'/cacert.pem');

        return $result;

    }
}