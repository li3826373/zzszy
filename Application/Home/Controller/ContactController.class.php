<?php
/**
 * Created by PhpStorm.
 * User: 李进平
 * Date: 2018/9/14
 * Time: 10:25
 */
namespace Home\Controller;
class ContactController extends BaseController
{
    function index(){
        /*企业简介*/
        $about=M("news")->where("typeid=11")->limit(1)->find();
        $this->about=$about;
        /*banner*/
        $this->banner=M("banner")->where(array("postion"=>10))->order("sort desc")->limit(1)->find();
        $this->display();
    }
}