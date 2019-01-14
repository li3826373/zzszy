<?php
/**
 * Created by PhpStorm.
 * User: 李进平
 * Date: 2018/7/15
 * Time: 18:42
 */
namespace Home\Service;
class StockService
{
    function slist($params){
        $condition['a.status']=0;//出搜中的
        $condition['a.attr']=0;//普通股票
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
        /*查询我发布的股票*/
        $recordnum = M("stock")->alias("a")->where($condition)->count();//总数量
        if(false!==$recordnum){
            $pageAllnum = intval(($recordnum-1)/$pagesize)+1;
            if($pagenum>$pageAllnum){
                $pagenum=$pageAllnum;
            }
        }else{
            $json['status']=false;
            $json['info']='获取数据记录条数失败!';
        }
        $result=M("stock")
            ->alias("a")
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