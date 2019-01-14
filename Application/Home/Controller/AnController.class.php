<?php
/**
 * Created by PhpStorm.
 * User: 李进平
 * Date: 2018/12/27
 * Time: 10:59
 */
namespace Home\Controller;
use Think\Controller;
class AnController extends BaseController
{
    function index(){

        $typeid=I("get.typeid");
        if(empty($typeid)){
            $typeid=47;
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
    /*想i青叶*/
    function info(){
        $articleid=I("get.articleid");
        $info=M("news")->find($articleid);
        $this->info=$info;
          /*其他*/
        $other=M("news")->where("typeid=".$info['typeid']." and articleid !=".$articleid)->order("sort desc")->limit(3)->select();
        $this->other=$other;
        $this->display();
    }
}