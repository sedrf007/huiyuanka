<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cardinfo extends MY_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->model('Newmem_model');
        $content = $this->input->raw_input_stream;
        if(!empty($content)){
            $this->logmessage($content);
            $ret_data = json_decode($content,TRUE);
            if(!is_array($ret_data))  $this->logmessage("数据格式有误",0);

            $event = $ret_data['event'];
            if(isset($event)){
                $functions = get_class_methods($this);
                if(!in_array($event,$functions)){
                    $this->logmessage("事件未定义",0);
                }
                $this->$event($ret_data);
            }
        }else{
            $this->index();
        }
        //$this->logmessage($event,1);
    }

    public function apiEntry(){
        $this->load->view('form');
    }
    //判断是否可以注册
    protected function checkNewCard($member){
        if(!is_array($member)) $this->logmessage("checkNewCard数据格式有误",0);
        $mobile = $member['data']['mobile'];
        $return=$this->Newmem_model->checkMem($mobile);
        $this->logmessage(json_encode($return));
        echo json_encode($return);exit;
    }

    protected function newCard($info){
        if(!is_array($info)) $this->logmessage("newCard数据格式有误",0);
        $ret=$this->Newmem_model->regMem($info);
        $formdata=array(
            'errcode'=> 0,
            'errmsg'=> '开卡成功',
            'data'=>array(
                'cardId'=> $ret['cardId'],
                'name'=> $ret['name'],
                'mobile'=> $ret['phone'],
                'gender'=> $ret['gender'],
                'birthday'=> $ret['birthday'],
                'cash'=> $ret['coin'],
                'score'=> $ret['amount'],
                'gradeId'=> $ret['grade'],
                'storeId'=> $ret['store_id']
             )
        );
        $ret_json=json_encode($formdata);
        $this->logmessage($ret_json);
        echo  $ret_json;exit;
    }

    public function checkBindCard($info){
        if(!is_array($info)) $this->logmessage("bindCard数据格式有误",0);
        $cardkey=$info['data']['cardKey'];
        $phone=$info['data']['mobile'];
        $ret=$this->Newmem_model->checkCard($cardkey,$phone);
        echo json_encode($ret);exit;
    }

    public function sendScoreOrder($info){
        if(!is_array($info)) $this->logmessage("score数据格式有误",0);
        $data = $info['data'];
        $this->logmessage(json_encode($data));
        $ret=$this->Newmem_model->changeScore($data);
        echo json_encode($ret);exit;
    }

    public function sendCashOrder($info){
        if(!is_array($info)) $this->logmessage("cash数据格式有误",0);
        $data = $info['data'];
        $this->logmessage(json_encode($data));
        $ret=$this->Newmem_model->changeCash($data);
        echo json_encode($ret);exit;
    }

    public function getCash($info){
        $data = $info['data'];
        $ret=$this->Newmem_model->getCash($data);
        $return = array(
            'errcode'=>0,
            'errmsg'=>"请求成功",
            'data'=>$ret
        );
        $json_data=json_encode($return);
        $this->logmessage($json_data);
        return $json_data;
    }

    public function getScore($info){
        $data = $info['data'];
        $ret=$this->Newmem_model->getScore($data);
        $return = array(
            'errcode'=>0,
            'errmsg'=>"请求成功",
            'data'=>$ret
        );
        $json_data=json_encode($return);
        $this->logmessage($json_data);
        return $json_data;
    }

    public function index(){
        echo 3445;exit;
    }
}