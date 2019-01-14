<?php
namespace Shuadmin\Controller;
use Think\Controller;
class AdminUserController extends BaseController 
{


	  /*管理员*/
     function adminuser(){
     
          //所有管理员
          $result=M("admin")->select();
         
      $this->result=$result;
        $this->display();
     }
     /*管理员添加*/
     function add(){
     	$path=__APP__;
        if(IS_POST){
        	  $username=I("post.username","");
        	  $password=I("post.password");
        	  $state=I("post.state");
        	  $state=implode(",", $state);
        	
        	  if(empty($username) || empty($password)){
        	  	    echo  '<meta charset="UTF-8">';
			        echo "<script>
	                       alert('用户名或者密码不能为空');
	                       location.href='{$path}/Shuadmin/AdminUser/add';
			          	</script>";
        	  }
        	  $data=array("username"=>$username,"password"=>md5($password));
        	  if(!empty($state)|| $state!=null){
        	  	    $data['state']=$state;
        	  }
        	  $re=M("admin")->add($data);
        	  if($re){
        	  	  echo  '<meta charset="UTF-8">';
			        echo "<script>
	                       alert('添加成功');
	                       location.href='{$path}/Shuadmin/AdminUser/adminuser';
			          	</script>";
        	  }else{
        	  	   echo  '<meta charset="UTF-8">';
			        echo "<script>
	                       alert('添加失败');
	                       location.href='{$path}/Shuadmin/AdminUser/add';
			          	</script>";
        	  }
        }




        $this->display();
     }
     /*管理员编辑*/
     function edit(){
		     	if(IS_POST){
		     	 $path=__APP__;
		          $id=I("post.id");
              $oldinfo=M("admin")->field("password")->find($id);
              $oldpassword=$oldinfo['password'];
               $username=I("post.username","");
                $password=I("post.password");
                if($password==$oldpassword){
                      $password=$password;
                }else{
                     $password=md5($password);
                }
                /*查看密码是否有变化*/

              $state=I("post.state");
              $state=implode(",", $state);
              $data=array("username"=>$username,"password"=>$password,"state"=>$state);
		          $re=M("admin")->where("id=".$id)->save($data);
			          if($re){
			          	 $new=M("admin")->where("id=".$id)->find();
			          	 session("adinuser",null);
			          	 $_SESSION['adminuser']=array(
                                'username'=>I("post.username"),
                                'id'=>$id,
                                'last_time'=>$new['login_time']
			          	 	);
			          	echo  '<meta charset="UTF-8">';
			          	echo "<script>
	                       alert('修改成功');
	                       location.href='{$path}/Shuadmin/AdminUser/adminuser';
			          	</script>";
			          	
			          }else{
			          		echo  '<meta charset="UTF-8">';
			          	echo "<script>
	                       alert('修改失败');
	                       location.href='{$path}/Shuadmin/AdminUser/edit/id/{$id}';
			          	</script>";
			          }

		     	}else{

				        $id=I("get.id");
				        $info=M("admin")->where("id=".$id)->find();
                $state=explode(",",$info['state']);
               
                $this->state=$state;
				        $this->info=$info;
				        $this->display();
		        }
     }
     /*管理员删除*/
     function del(){
         $path=__APP__;
          $id=I("get.id",0,'intval');
          if(empty($id)){
             	echo  '<meta charset="UTF-8">';
			          	echo "<script>
	                       alert('参数丢失');
	                       location.href='{$path}/Shuadmin/AdminUser/adminuser';
			          	</script>";
          }
          $re=M("admin")->where("id=".$id)->delete();
          if($re){

          	  $this->redirect('AdminUser/adminuser');
          }else{

          	echo  '<meta charset="UTF-8">';
			          	echo "<script>
	                       alert('删除失败');
	                       location.href='{$path}/Shuadmin/AdminUser/adminuser';
			          	</script>";
          }

     }
}