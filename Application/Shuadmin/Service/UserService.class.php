<?php
/**
 * Created by PhpStorm.
 * User: 李进平
 * Date: 2018/7/11
 * Time: 10:26
 */
namespace Shuadmin\Service;
class UserService
{
    /*会员列表*/
    function ulist($params){
        if(preg_match("/^\d+$/",$params['p'])){
            $pagenum=$params['p'];
        }else{
            $pagenum=1;
        }
        //判断每页显示条数
        if(preg_match("/^\d+$/",$params['pagesize'])){
            $pagesize=$params['pagesize'];
        }else{
            $pagesizelist= C('PAGESIZE_LIST');
            $pagesize=$pagesizelist[0];
        }
        if($params['username']){
            $condition['a.username']=$params['username'];
        }
        if($params['name']){
            $condition['a.name']=$params['name'];
        }
        if($params['servicecenter']){
            $condition['a.servicecenter']=$params['servicecenter'];
        }
        /*查询会员*/
        $recordnum = M("user")
            ->alias("a")
            ->where($condition)
            ->count();//总数量
        if(false!==$recordnum){
            $pageAllnum = intval(($recordnum-1)/$pagesize)+1;
            if($pagenum>$pageAllnum){
                $pagenum=$pageAllnum;
            }
        }else{
            $json['status']=false;
            $json['info']='获取数据记录条数失败!';
        }
        $result=M("user")
            ->alias("a")
            ->where($condition)
            ->order("a.addtime desc")
            ->page($pagenum.",".$pagesize)
            ->select();
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
        if($result){
            $arr=array(
                "status"=>true,
                "data"=>array($result,$show,$recordnum,$pageAllnum,$pagenum,$pagesize)
            );
        }else{
            $arr=array(
                "status"=>false,
                "info"=>"没有更多的数据了",
                "data"=>array($result,$show,$recordnum,$pageAllnum,$pagenum, $pagesize)
            );
        }
        return $arr;

    }
    /*服务中心申请列表*/
    function servicelist($params){
        $condition['a.service_status']=1;
        if(preg_match("/^\d+$/",$params['p'])){
            $pagenum=$params['p'];
        }else{
            $pagenum=1;
        }
        //判断每页显示条数
        if(preg_match("/^\d+$/",$params['pagesize'])){
            $pagesize=$params['pagesize'];
        }else{
            $pagesizelist= C('PAGESIZE_LIST');
            $pagesize=$pagesizelist[0];
        }
        if($params['username']){
            $condition['a.username']=$params['username'];
        }
        /*查询我发布的货源信息*/
        $recordnum = M("user")
            ->alias("a")
            ->where($condition)
            ->count();//总数量
        if(false!==$recordnum){
            $pageAllnum = intval(($recordnum-1)/$pagesize)+1;
            if($pagenum>$pageAllnum){
                $pagenum=$pageAllnum;
            }
        }else{
            $json['status']=false;
            $json['info']='获取数据记录条数失败!';
        }
        $result=M("user")
            ->alias("a")
            ->where($condition)
            ->order("a.addtime desc")
            ->page($pagenum.",".$pagesize)
            ->select();
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
        if($result){
            $arr=array(
                "status"=>true,
                "data"=>array($result,$show,$recordnum,$pageAllnum,$pagenum,$pagesize)
            );
        }else{
            $arr=array(
                "status"=>false,
                "info"=>"没有更多的数据了",
                "data"=>array($result,$show,$recordnum,$pageAllnum,$pagenum, $pagesize)
            );
        }
        return $arr;
    }
    /*充值记录*/
    function chong($params){
        $condition['a.is_delete']=0;
        if(preg_match("/^\d+$/",$params['p'])){
            $pagenum=$params['p'];
        }else{
            $pagenum=1;
        }
        //判断每页显示条数
        if(preg_match("/^\d+$/",$params['pagesize'])){
            $pagesize=$params['pagesize'];
        }else{
            $pagesizelist= C('PAGESIZE_LIST');
            $pagesize=$pagesizelist[0];
        }
        if($params['username']){
            $condition['u.username']=$params['username'];
        }
        /*查询我发布的货源信息*/
        $recordnum = M("givemoney")
                   ->alias("a")
            ->join("__USER__ as u on u.id=a.uid")
                   ->where($condition)
                      ->count();//总数量
        if(false!==$recordnum){
            $pageAllnum = intval(($recordnum-1)/$pagesize)+1;
            if($pagenum>$pageAllnum){
                $pagenum=$pageAllnum;
            }
        }else{
            $json['status']=false;
            $json['info']='获取数据记录条数失败!';
        }
        $result=M("givemoney")
            ->alias("a")
            ->field("a.*,u.username")
            ->join("__USER__ as u on u.id=a.uid")
            ->where($condition)
            ->order("a.dateandtime desc")
            ->page($pagenum.",".$pagesize)
            ->select();
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
        if($result){
            $arr=array(
                "status"=>true,
                "data"=>array($result,$show,$recordnum,$pageAllnum,$pagenum,$pagesize)
            );
        }else{
            $arr=array(
                "status"=>false,
                "info"=>"没有更多的数据了",
                "data"=>array($result,$show,$recordnum,$pageAllnum,$pagenum, $pagesize)
            );
        }
        return $arr;
    }
    /*提现记录*/
    function ti($params){
        $condition['a.is_delete']=0;
        if(preg_match("/^\d+$/",$params['p'])){
            $pagenum=$params['p'];
        }else{
            $pagenum=1;
        }
        //判断每页显示条数
        if(preg_match("/^\d+$/",$params['pagesize'])){
            $pagesize=$params['pagesize'];
        }else{
            $pagesizelist= C('PAGESIZE_LIST');
            $pagesize=$pagesizelist[0];
        }
        if($params['username']){
            $condition['u.username']=$params['username'];
        }
        /*查询我发布的货源信息*/
        $recordnum = M("getmoney")
                    ->alias("a")
            ->join("__USER__ as u on u.id=a.uid")
                     ->where($condition)
                       ->count();//总数量
        if(false!==$recordnum){
            $pageAllnum = intval(($recordnum-1)/$pagesize)+1;
            if($pagenum>$pageAllnum){
                $pagenum=$pageAllnum;
            }
        }else{
            $json['status']=false;
            $json['info']='获取数据记录条数失败!';
        }
        $result=M("getmoney")
            ->alias("a")
            ->field("a.*,u.username")
            ->join("__USER__ as u on u.id=a.uid")
            ->where($condition)
            ->order("a.dateandtime desc")
            ->page($pagenum.",".$pagesize)
            ->select();
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
        if($result){
            $arr=array(
                "status"=>true,
                "data"=>array($result,$show,$recordnum,$pageAllnum,$pagenum,$pagesize)
            );
        }else{
            $arr=array(
                "status"=>false,
                "info"=>"没有更多的数据了",
                "data"=>array($result,$show,$recordnum,$pageAllnum,$pagenum, $pagesize)
            );
        }
        return $arr;


    }
}