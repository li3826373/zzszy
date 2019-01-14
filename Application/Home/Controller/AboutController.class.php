<?php
namespace Home\Controller;
class AboutController extends BaseController {
    public function index()
    {
          $typeid=I("get.typeid");
          if(empty($typeid)){
              $typeid=60;
          }
        /*关于我们*/
        $about=M("news")->where("typeid=".$typeid)->order("sort desc")->limit(1)->find();
        $this->about=$about;
        /*关于我们图文*/
        $tu=M("news")->where("typeid=".$typeid)->order("sort desc")->limit(1,2)->select();
        $this->tu=$tu;
        /*banner*/
        $this->banner=M("banner")->where(array("postion"=>2))->order("sort desc")->limit(1)->find();
//       查询公司简介下的类型
        $aboutall=M("newstypes")->where("fid=30")->select();
        $this->aboutall=$aboutall;
            $this->display();
    }
    //空方法, 访问不存在的方法时执行
    public function _empty() {
        $this->redirect("Home/Index/index");
    }
}