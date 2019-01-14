<?php
namespace Home\Controller;
class IndexController extends BaseController {
    //空方法, 访问不存在的方法时执行
    public function _empty() {
        $this->redirect("Home/Index/index");
    }
    public function index()
    {
        /*我们的作品*/
          $this->acing="Index_index";
        $typeid=48;
        $typeall=getsonpath($typeid);
        $con['typeid']=array("in",$typeall);
        $list=M("news")->where($con)->order("sort desc") ->limit(2)->select();
        $this->list=$list;
        //新闻中心
        $typeid=49;
        $typeall=getsonpath($typeid);
        $con1['typeid']=array("in",$typeall);
        $newslist=M("news")->where($con1)->order("sort desc")->limit(6)->select();
        $this->newslist=$newslist;

        /*关于我们*/
        $about=M("news")->where("typeid=60")->limit(1)->find();
        $this->about=$about;
        /*banner*/
        $this->banner=M("banner")->where(array("postion"=>1))->order("sort desc")->select();


            $this->display();
    }
    /*模型api*/
    function modeling(){
           $res=M("product_model")->select();
           echo json_encode($res);
    }
//    模板库
    function moban(){
        $type=I("get.type");
        if($type==1){
            $condition['type']="营销展示类";
        }elseif($type==2){
            $condition['type']="管理系统";
        }elseif($type==3){
            $condition['type']="商城类";
        }elseif($type==4){
            $condition['type']="其他";
        }
           $count=M("moban")->where($condition)->count();
        $Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show= $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = M("moban")->where($condition)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
}