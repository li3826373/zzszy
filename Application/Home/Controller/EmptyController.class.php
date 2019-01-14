<?php 
//不存在控制器时执行
namespace Home\Controller;
use Think\Controller;
class EmptyController extends Controller {

    public function index() {
       $this->redirect("Index/index");
    }
     //空方法, 访问不存在的方法时执行
    public function _empty() {
        $this->redirect("Index/index");
    }
}