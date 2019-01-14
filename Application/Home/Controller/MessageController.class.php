<?php
/**
 * Created by PhpStorm.
 * User: 李进平
 * Date: 2018/9/14
 * Time: 15:18
 */
namespace Home\Controller;
use Home\Service\MessageService;

class MessageController extends BaseController
{
    function index(){
        /*banner*/
        $this->banner=M("banner")->where(array("postion"=>9))->order("sort desc")->limit(1)->find();

        $this->display();
    }
    /*留言*/
    function message(){
          $params=I("post.");
          $obj=new MessageService();
          $res=$obj->index($params);
          $this->ajaxReturn($res);
    }
}