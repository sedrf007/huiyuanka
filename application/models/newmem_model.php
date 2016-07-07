<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Newmem_model extends CI_Model {
    public function __construct(){
        parent::__construct();
        //$this->db=$this->load->database('New_mem');
    }

    public function checkMem($info=array()){
        if(!empty($info)){
            $formdata=array(
                'phone'=>$info['mobile'],
                'name'=>$info['name'],
                'gender'=>$info['gender']
            );
            $str = $this->db->insert_string('t_wm_new_member', $formdata);
            return $str;
        }else{
            return -1;
        }
    }
    public function regMem($info){
       // $data=json_decode($info,TRUE);
        $this->db=$this->load->database('New_mem');
        if(!is_array($info))$this->logmessage("注册信息为空");
        $formdata=array(
            'wechatid'=>$info['data']['openid'],
            'name' =>$info['data']['name'],
            'phone' =>$info['data']['mobile'],
            'gender'=>$info['data']['gender']
        );
        $this->db->insert_string('new_member',$formdata);
    }

}