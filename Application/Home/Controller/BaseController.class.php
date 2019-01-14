<?php
/**
 * Created by PhpStorm.
 * User: 李进平
 * Date: 2018/9/13
 * Time: 16:27
 */
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller
{
    function _initialize(){
           //查询导航菜单
            $menu=M("newstypes")->where(array("state"=>1))->order("sort desc")->select();
            $this->menu=$menu;
        /*配置表*/
        $config=M("config")->find(1);
        $this->assign("conf",$config);
        $checkall=M("newstypes")->where("fid=28")->select();
        $this->checkall=$checkall;
        /*获取控制器和方法*/
        $controller_actions = CONTROLLER_NAME.'_'.ACTION_NAME;
        $this->ac=$controller_actions;
       }
}