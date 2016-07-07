<?php
class MY_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->valid();
    }
    public function valid()
    {
        $echoStr = $this->input->get("echostr");
        if($this->checkSignature()){
            //$this->logmessage($echoStr);
            echo $echoStr;
        }else{
            echo "非法访问!!!!";exit;
        }
    }

    private function checkSignature()
    {
        $token = $this->config->item("TOKEN");
        if (!isset($token)) {
            throw new Exception('TOKEN is not defined!');
        }
        $signature =$this->input->get("signature");
        $timestamp = $this->input->get("timestamp");
        $nonce = $this->input->get("nonce");
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        //$this->logmessage($tmpStr);
        //$this->logmessage($signature);
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function logmessage($message,$out = 0){
        $file = "/tmp/logmessage".date("Y-m-d").'.log';
        file_put_contents($file,$message.date("Y-m-d H:i:s")."\n",FILE_APPEND);
        if($out == 1) exit;
    }

}