<?php
/**
 * Created by PhpStorm.
 * User: 李进平
 * Date: 2018/9/14
 * Time: 15:21
 */
namespace Home\Service;
class MessageService{
    function index($params){
           if(!preg_match("/\S+/",trim($params['name']))){
                 $json['status']=false;
                 $json['info']="请填写姓名";
           }else if(!preg_match("/^1(3|4|5|7|8)\d{9}$/",$params['phone']) && !preg_match("/^(\d{3}\-)(\d{8})$|^(\d{4}\-)(\d{7})$|^(\d{7})$|^(\d{8})$/",$params['phone'])){
               $json['status']=false;
               $json['info']="请填写联系方式";
           }else if(!preg_match("/\S+/",trim($params['address']))){
               $json['status']=false;
               $json['info']="请填写地址";
           }else if(!preg_match("/\S+/",trim($params['content']))){
               $json['status']=false;
               $json['info']="请填写内容";
           }else{
                 $res=M("message")->add($params);
                 if($res){
                     $json['status']=true;
                     $json['info']="留言成功,稍后给你联系";
                 }else{
                     $json['status']=false;
                     $json['info']="留言失败，请稍后再试";
                 }
           }
           return $json;

    }
}