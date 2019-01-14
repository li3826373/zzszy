<?php
/**
 * Created by PhpStorm.
 * User: 李进平
 * Date: 2018/12/27
 * Time: 10:38
 */
namespace Home\Controller;
use Think\Controller;
class CaseController extends BaseController
{
    function index(){
        //       获取所有的新闻类型
        $newsall=M("newstypes")->where("fid=48")->select();
        $this->newsall=$newsall;
        /*banner*/
        $this->banner=M("banner")->where(array("postion"=>3))->order("sort desc")->limit(1)->find();
        $typeid=I("get.typeid");
        if(empty($typeid)){
            $typeid=48;
        }
        $typeall=getsonpath($typeid);
        $con['typeid']=array("in",$typeall);
        $recordnum=M("news")->where($con)->count();
        $pagesize=10;
        $p=isset($_GET['p'])?$_GET['p']:0;
        $list=M("news")->where($con)->order("sort desc") ->page($p.',10')->select();
        $Page  = new \Think\Page($recordnum,$pagesize);// 实例化分页类 传入总记录数和每页显示的记录数
        $Page->rollPage=8;
        $Page->lastSuffix=false;
        $Page->setConfig('header','共 %TOTAL_ROW% 条记录');
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('first','首页');
        $Page->setConfig('last','尾页');
        $Page->setConfig('theme', '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $show  = $Page->show();// 分页显示输出
        $this->list=$list;
        $this->page=$show;
        $this->display();
    }
    /*作品详情页*/
    function info(){
        $articleid=I("get.articleid");
        $type=I("get.type");
        if(empty($type)){
            $type=1;
        }
        $info=M("news")->find($articleid);
        $this->type=$type;
        $this->info=$info;
        //下一篇
        $con['articleid']=array("gt",$articleid);
        $con['typeid']=$info['typeid'];
        $con["_logic"]="and";
        $next=M("news")->where($con)->find();
        $this->next=$next;
        $this->display();
    }
}