<?php
defined('BASEPATH') OR exit('No direct script access allowed');
Class UserApi extends CI_Controller{
    protected $appid ='ab1dd6dba0a241aeff4dbc377643d0c6';
    protected $appsecret = '02f9c1e244a9600db401eb7effd6fb25';
    protected $token="";
    public function __construct(){
        parent::__construct();
        $this->token=$this->getToken();
        if($this->token == 2) {
            echo "get weimob token failed";

            exit;
        }
        $this->load->model('Newmem_model');
    }

    public function apiEntry(){
        $size=10;
        $index=$this->input->get('page')?$this->input->get('page'):0;
        $start = $index * $size;
        $data = $this->Newmem_model->getAllMem($start,$size);
        $data['pagenum']=ceil($data['num']/$size);

        $this->load->view('form',$data);
    }

    public function toedituser(){
        $cardId=$this->input->post('cardid');
        $name=$this->input->post('name')?$this->input->post('name'):"";
        $gender=$this->input->post('gender');
        $mobile=$this->input->post('phone');
        $birhday=$this->input->post('birthday');
        $gradeId=$this->input->post('gradeid');
        $status=$this->input->post('status');
        $storeId=$this->input->post('storeid');
        $url="https://open.weimob.com/api/mname/WE_MEMBERS_CARD/cname/setCard?accesstoken=".$this->token;
        $formdata=array(
            'cardId'=>$cardId,
            'name'=>$name,
            'mobile'=>$mobile,
            'gender'=>$gender,
            'birthday'=>$birhday,
            'gradeId'=>$gradeId,
            'status'=>$status,
            'storeId'=>$storeId
        );
        $data=json_encode($formdata);

        $ret=$this->Newmem_model->updateuser($formdata);
        $this->logmessage($data);
        $return=$this->httpPost($url,$data);
        var_export($return);exit;
    }

    public function editscore(){
        $cardId=$this->input->get('cardid');
        $data=$this->Newmem_model->searchById($cardId);
        $this->load->view("userscore",$data);
    }

    public function editcash(){
        $cardId=$this->input->get('cardid');
        $data=$this->Newmem_model->searchById($cardId);
        $this->load->view("usercash",$data);
    }

    public function toeditscore(){
        $cardId=$this->input->post('cardid');
        $scoreValue=$this->input->post('score');
        $scoreBefore=$this->input->post('scoreBefore');
        $storeId=$this->input->post('storeId');
        $scoreSource=$this->input->post('scoreSource');
        if($scoreValue>0){
            $scoreType=1;
            $type='inc';
        }elseif($scoreValue==0){
            $scoreType=3;
            $type='reset';
        }else{
            $scoreType=2;
            $type='dec';
        }
        //$scoreTime=date('Y-m-d H:i:s',time());
        $formdata=array(
            'scoreBefore'=>$scoreBefore,
            'cardId'=>$cardId,
            'scoreType'=>$scoreType,
            'scoreSource'=>$scoreSource,
            'from'=>2,
            'store_id'=>$storeId,
            'scoreValue'=>abs($scoreValue)
        );
        //var_dump($formdata);
        $scoreOrderId=$this->Newmem_model->insertScore($formdata);
        $formdata['scoreType']=$type;
        $formdata['scoreTime']=date('Y-m-d H:i:s',time());
        $url="https://open.weimob.com//api/mname/WE_MEMBERS_CARD/cname/sendScoreOrder?accesstoken=".$this->token;
        unset($formdata['store_id']);
        $formdata['storeId']=$storeId;
        $formdata['scoreOrderId']=$scoreOrderId;
        $data=json_encode($formdata);
        $result = json_decode($this->httpPost($url,$data),true);
        var_dump($result);

    }

    public function toeditcash(){
        $cardId=$this->input->post('cardid');
        $cashValue=$this->input->post('cash');
        $cashBefore=$this->input->post('cashBefore');
        $storeId=$this->input->post('storeId');
        $cashSource=$this->input->post('cashSource');
        if($cashValue>0){
            $cashType=1;
            $type='inc';
        }elseif($cashValue==0){
            $cashType=3;
            $type='reset';
        }else{
            $cashType=2;
            $type='dec';
        }
        $cashTime=date('Y-m-d H:i:s',time());
        $formdata=array(
            'cashBefore'=>$cashBefore,
            'cardId'=>$cardId,
            'cashType'=>$cashType,
            'cashSource'=>$cashSource,
            'from'=>2,
            'store_id'=>$storeId,
            'cashValue'=>abs($cashValue)
        );
        $cashOrderId=$this->Newmem_model->insertcash($formdata);
        $formdata['cashTime']=$cashTime;
        $formdata['cashType']=$type;
        $url="https://open.weimob.com//api/mname/WE_MEMBERS_CARD/cname/sendCashOrder?accesstoken=".$this->token;
        unset($formdata['store_id']);
        $formdata['storeId']=$storeId;
        $formdata['cashOrderId']=$cashOrderId;
        $data=json_encode($formdata);
        $result = json_decode($this->httpPost($url,$data),true);
        var_dump($result);

    }

    public function edituser(){
        $cardId=$this->input->get('cardid');
        $data=$this->Newmem_model->searchById($cardId);
        $this->load->view("userdata",$data);
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
        $nowtime =time();
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
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;

    }

    public function logmessage($message,$out = 0){
        $file = "/tmp/logmessage".date("Y-m-d").'.log';
        file_put_contents($file,$message.date("Y-m-d H:i:s")."\n",FILE_APPEND);
        if($out == 1) exit;
    }
}