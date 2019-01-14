<?php
namespace Shuadmin\Controller;
use Think\Controller;
use Think\Verify;
class LoginController extends Controller{


	function index(){
			if(IS_POST){
        	$username=I("post.username","");
	        	$password=I("post.password","");
        	$verify=I("post.verify","");
        	if(empty($username) || empty($password) || empty($verify)){

        	  $this->error("用户名、密码或者验证码不能为空",U('Login/index'),3);
        	}
        	 $ver= new Verify();//实例化验证吗类
        	 $yan=$ver->check($verify);
              if($yan==false){
            
              	$this->error("验证码错误",U('Login/index'),3);
              }

        	$data=array(
                'username'=>$username,
                'password'=>md5($password),
        		);
        	$re=M("admin")->where($data)->find();
        	if($re){
                  /*更新登录时间*/
                  $time=array(
                       'id'=>$re['id'],
                      'login_time'=>date("Y:m:d H:i:s"),
                    );
                  M("admin")->save($time);
        		  $_SESSION['adminuser']=array("username"=>$re['username'],'id'=>$re['id'],'last_time'=>$re['login_time']);
                $_SESSION['state']=explode(",",$re['state']);//权限存储
        		  $this->redirect("Index/index");

        	}else{

        		  $this->error("用户名或密码错误",U("Login/index"),3);
        	}

        }else{
       
		$this->display();
	  }
	}

	/*验证码*/
	function verify(){
		$config =    array(    'fontSize'    =>    50,    // 验证码字体大小   
		 'length'      =>    3,     // 验证码位数   
		  'useNoise'    =>    true, // 关闭验证码杂点
		  );
       $verify=new Verify($config);
        $verify->entry();

	}
}