<?php
/**
 * Created by PhpStorm.
 * User: 李进平
 * Date: 2018/9/13
 * Time: 17:02
 */
namespace Home\Controller;
class ServiceController extends BaseController
{
    function index(){
        /*banner*/
        $this->banner=M("banner")->where(array("postion"=>7))->order("sort desc")->limit(1)->find();

        $typeid=I("get.typeid");
        if(!$typeid){
            $typeid=28;
        }

        $list=M("news")->where(array("typeid"=>$typeid))->order("sort desc") ->select();
      $this->list=$list;
        $this->display();
    }
//    详情
    function detail(){
        /*banner*/
        $this->banner=M("banner")->where(array("postion"=>7))->order("sort desc")->limit(1)->find();
           $articleid=I("get.articleid");
           $info=M("news")->find($articleid);
           $this->info=$info;
            M("news")->where("articleid=".$articleid)->setInc("hit",1);
            /*上一篇*/
        $con['articleid']=array("lt",$articleid);
        $con['typeid']=$info['typeid'];
        $con["_logic"]="and";
           $last=M("news")->where($con)->find();
           $this->last=$last;
           //下一篇
        $con['articleid']=array("gt",$articleid);
        $con['typeid']=$info['typeid'];
        $con["_logic"]="and";
          $next=M("news")->where($con)->find();
          $this->next=$next;
           $this->display();
    }
}
