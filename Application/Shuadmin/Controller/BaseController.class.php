<?php
namespace Shuadmin\Controller;
use Think\Controller;
class BaseController extends Controller{

	public function _initialize() {
         if(empty($_SESSION['adminuser'])){

         	$this->error("非法进入,请先登录",U("Login/index"),3);
         }

	}
}