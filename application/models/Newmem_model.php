<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Newmem_model extends CI_Model {
    private $table="memberinfo";
    private $stable='scoreOrder';
    private $ctable='cashOrder';
    public function __construct(){
        parent::__construct();
        $this->load->database('New_mem');
        //$this->db=$this->load->database('New_mem');
    }


    public function checkMem($phone){
        if(!isset($phone))$this->logmessage("未传入手机号");
        $sql ='SELECT cardId FROM '.$this->table.' WHERE phone=? AND isbind=1';
        $query=$this->db->query($sql,$phone);
        if(!$query->num_rows()){
            $ret['errcode']=0;
            $ret['errmsg']="可以开卡";
            $ret['data']="";
        }else{
            $ret['errcode']=3015130;
            $ret['errmsg']="线下门店提示会员已经存在	";
            $ret['data']="";
        }
        //$this->logmessage($query->num_rows());
        return $ret;
    }
    public function regMem($info){
       // $data=json_decode($info,TRUE);
        if(!is_array($info))$this->logmessage("注册信息为空");
        $formdata=array(
            'wechat_id'=>$info['data']['openid'],
            'name' =>$info['data']['name'],
            'phone' =>$info['data']['mobile'],
            'gender'=>$info['data']['gender'],
            'pid'=>$info['data']['pid'],
            'isbind'=>1
        );
        foreach($info['data']['field'] as $k=>$v){
            $formdata['personId']=$v['value'];
        }
        $str=$this->db->insert_string($this->table,$formdata);
        $this->db->query($str);
        $this->logmessage($str);
        $cardInfo = $this->getCardKey($info['data']['mobile']);
        return $cardInfo;
    }

    public function checkCard($cardkey,$phone){
        $sql='SELECT cardId FROM '.$this->table.' where cardId=? AND phone=? AND isbind=0';
        $query=$this->db->query($sql,array($cardkey,$phone));
        $result = $query->num_rows();
        if($result==0){
            $data=array(
                "errcode" => 3015141,
                "errmsg"=> "线下门店提示无法绑卡",
                "data"=>""
            );
        }else{
            $meminfo=$this->getCardKey($phone);
            $formdata=array(
                'cardId'=>$meminfo['cardId'],
                'name' =>$meminfo['name'],
                'mobile' =>$meminfo['phone'],
                'gender'=>$meminfo['gender'],
                'birthday'=>$meminfo['birthday'],
                'score'=>$meminfo['amount'],
                'cash'=>$meminfo['coin'],
                'gradeId'=>$meminfo['grade'],
                'storeId'=>$meminfo['store_id']
            );
            $data=array(
                "errcode" => 0,
                "errmsg"=> "可以绑卡	",
                "data"=> $formdata
            );
        }
        return $data;
    }

    public function changeScore($info){
       $scoreinfo=array(
           'cardId'=>intval($info['cardId']),
           'from'=>intval($info['from']),
           'scoreType'=>$info['scoreType'],
           'pid'=>$info['pid'],
           'scoreValue'=>$info['score'],
           'scoreSn'=>$info['scoreSn'],
           'store_id'=>$info['storeId']
       );
        switch($info['scoreType']){
            case 'inc':$scoreinfo['scoreType']=1;break;
            case 'dec':$scoreinfo['scoreType']=2;break;
            case 'reset':$scoreinfo['scoreType']=3;break;
        }
        if($scoreinfo['scoreType']==1)$action = "amount=amount+".$scoreinfo['scoreValue'];
        if($scoreinfo['scoreType']==2)$action = "amount=amount-".$scoreinfo['scoreValue'];
        if($scoreinfo['scoreType']==3)$action = "amount=0";
        $sql = 'UPDATE '.$this->table.' SET '.$action.' WHERE cardId='.$scoreinfo['cardId'];
        $this->db->query($sql);
        $str = $this->db->insert_string($this->stable, $scoreinfo);
        $this->db->query($str);
        $formdata=array(
            'errcode'=>0,
            'errmsg'=>"",
            'data'=>array(
                'scoreOrderId'=>$this->db->insert_id()
            )
        );
        return $formdata;
    }

    public function changeCash($info){
        $cashinfo=array(
            'cardId'=>intval($info['cardId']),
            'from'=>intval($info['from']),
            'cashType'=>$info['cashType'],
            'store_id'=>$info['storeId'],
            'cashValue'=>$info['cash'],
            'cashSn'=>$info['cashSn'],
            'pid'=>$info['pid']
        );
        switch($info['cashType']){
            case 'inc':$cashinfo['cashType']=1;break;
            case 'dec':$cashinfo['cashType']=2;break;
            case 'reset':$cashinfo['cashType']=3;break;
        }
        if($cashinfo['cashType']==1)$action = "coin=coin+".$cashinfo['cashValue'];
        if($cashinfo['cashType']==2)$action = "coin=coin-".$cashinfo['cashValue'];
        if($cashinfo['cashType']==3)$action = "coin=0";
        $sql = 'UPDATE '.$this->table.' SET '.$action.' WHERE cardId='.$cashinfo['cardId'];
        $this->db->query($sql);
        $str = $this->db->insert_string($this->ctable, $cashinfo);
        $this->db->query($str);
        $formdata=array(
            'errcode'=>0,
            'errmsg'=>"",
            'data'=>array(
                'cashOrderId'=>$this->db->insert_id()
            )
        );
        return $formdata;
    }

    public function getCash($info){
        $sql="SELECT * FROM ".$this->ctable." WHERE cashOrderId>=".$info['offset']." LIMIT ".$info['size'];
        $query=$this->db->query($sql);
        $result=$query->row_array();
        $formdata=array(
            'pid'=>68680,
            'cardId'=>$result['cardId'],
            'cashOrderId'=>$result['cashOrderId'],
            'cashSn'=>"",
            'isExec'=>0,
            'source'=>$result['cashSource'],
            'storeId'=>$result['store_id'],
            'from'=>2,
            'cash'=>$result['cashValue'],
            'cashTime'=>$result['cashTime'],
            'orderType'=>"exec"
        );
        if($result['cashType']==1)$formdata['cashType']='inc';
        if($result['cashType']==2)$formdata['cashType']='dec';
        if($result['cashType']==3)$formdata['cashType']='reset';
        return $formdata;
    }

    public function getScore($info){
        $sql="SELECT * FROM ".$this->stable." WHERE scoreOrderId>=".$info['offset']." LIMIT ".$info['size'];
        $query=$this->db->query($sql);
        $result=$query->row_array();
        $formdata=array(
            'pid'=>68680,
            'cardId'=>$result['cardId'],
            'scoreOrderId'=>$result['scoreOrderId'],
            'scoreSn'=>"",
            'isExec'=>0,
            //'source'=>$result['scoreSource'],
            'source'=>"测试测试",
            'storeId'=>$result['store_id'],
            'from'=>2,
            'score'=>intval($result['scoreValue']),
            'scoreTime'=>$result['scoreTime'],
            'scoreType'=>$result['scoreType'],
            'orderType'=>"exec"
        );
//        if($result['scoreType']==1)$formdata['scoreType']='inc';
//        if($result['scoreType']==2)$formdata['scoreType']='dec';
//        if($result['scoreType']==3)$formdata['scoreType']='reset';
        return $formdata;
    }

    public function getCardKey($mobile){
        $sql ='SELECT cardId,name,phone,gender,birthday,coin,amount,grade,store_id FROM '.$this->table.' WHERE phone=?';
        $query=$this->db->query($sql,$mobile);
        $result = $query->row_array();
        $this->logmessage(json_encode($result));
        return $result;
    }

    public function searchById($cardid){
        $sql='SELECT * FROM '.$this->table.' WHERE cardId=?';
        $query=$this->db->query($sql,$cardid);
        $result = $query->row_array();
        return $result;
    }

    public function getAllMem($from,$limit){
        $sql='SELECT * from '.$this->table.' WHERE 1=1 LIMIT '.$from.','.$limit;
        $query=$this->db->query($sql);
        $data=$query->result_array();
        $num=$query->num_rows();
        $ret=array(
            'data'=>$data,
            'num'=>$num
        );
        return $ret;
    }

    public function updateuser($info){
        $where = "cardId=".$info['cardId'];
        $formdata=array(
            'cardId'=>$info['cardId'],
            'name'=>$info['name'],
            'phone'=>$info['mobile'],
            'gender'=>$info['gender'],
            'birthday'=>$info['birthday'],
            'grade'=>$info['gradeId'],
            'status'=>$info['status'],
            'store_id'=>$info['storeId']
        );
        $str = $this->db->update_string($this->table, $formdata, $where);
        $result=$this->db->query($str);
        return $result;
    }

    public function insertScore($info){
        if($info['scoreType']==1)$action = "amount=amount+".$info['scoreValue'];
        if($info['scoreType']==2)$action = "amount=amount-".$info['scoreValue'];
        if($info['scoreType']==3)$action = "amount=0";
        $sql = 'UPDATE '.$this->table.' SET '.$action.' WHERE cardId='.$info['cardId'];
        $this->db->query($sql);
        $str = $this->db->insert_string($this->stable, $info);
        $this->db->query($str);

        return $this->db->insert_id();
    }

    public function insertCash($info){
        if($info['cashType']==1)$action = "coin=coin+".$info['cashValue'];
        if($info['cashType']==2)$action = "coin=coin-".$info['cashValue'];
        if($info['cashType']==3)$action = "coin=0";
        $sql = 'UPDATE '.$this->table.' SET '.$action.' WHERE cardId='.$info['cardId'];
        $this->db->query($sql);
        $str = $this->db->insert_string($this->ctable, $info);
        $this->db->query($str);

        return $this->db->insert_id();
    }

    protected function logmessage($message,$out = 0){
        $file = "/tmp/logmessage".date("Y-m-d").'.log';
        file_put_contents($file,$message.date("Y-m-d H:i:s")."\n",FILE_APPEND);
        if($out == 1) exit;
    }

}