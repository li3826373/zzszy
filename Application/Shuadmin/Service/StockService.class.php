<?php
/**
 * Created by PhpStorm.
 * User: 李进平
 * Date: 2018/7/16
 * Time: 9:39
 */
namespace Shuadmin\Service;
class StockService
{
    /*购买公司股权记录*/
    function slist($params){
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
        /*查询股票购买记录*/
        $recordnum = M("stock_log")
                     ->alias("a")
            //购买人
            ->join("left join __USER__ as u on u.id=a.uid")
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
        $result=M("stock_log")
            ->alias("a")
            ->field("a.*,p.pname,u.username as uname")
            //购买人
          ->join("left join __USER__ as u on u.id=a.uid")
            //发布人
               ->join("left join (select u1.username as pname,s1.id from user as u1,stock as s1 where u1.id=s1.uid) p on p.id=a.stock_id")
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
    /*股票发布*/
    function publish($params){
        if(!preg_match("/^\d+$/",$params['stocknumber'])){
            $json['status']=false;
            $json['info']="请填写出售股数";
        }else if(!preg_match("/^\d+(\.)?(\d+)?$/",$params['stockprice'])){
            $json['status']=false;
            $json['info']="请填写股票单价";
        }else{
             if($params['id']){
                  //修改
                   $res=M("stock")->save($params);
                   if($res){
                       $json['status']=true;
                       $json['info']="编辑成功";
                   }else{
                       $json['status']=false;
                       $json['info']="编辑失败";
                   }
             }else{
                 $params['attr']=1;
                 $res=M("stock")->add($params);
                 if($res){
                     $json['status']=true;
                     $json['info']="发布成功";
                 }else{
                     $json['status']=false;
                     $json['info']="发布失败";
                 }
             }

        }
        return $json;
    }
}