<?php
/**
 * Created by PhpStorm.
 * User: 李进平
 * Date: 2019/1/4
 * Time: 17:13
 */
namespace Shuadmin\Controller;
use Think\Controller;
class MoController extends BaseController
{
    function index(){

        $count=M("moban")->count();
        $Page = new \Think\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show= $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = M("moban")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出

        $this->display();
    }
    /*添加*/
    function add(){
        $path=__APP__;
        if(IS_POST){
            $img=uploadimg($_FILES['imagename']);
            $params=I("post.");
            if($img){
                $params['imagename']=ltrim($img['savepath'].$img['savename'],"./");
            }
            $re=M("moban")->add($params);
            if($re){
                echo  '<meta charset="UTF-8">';
                echo "<script>
		                       alert('添加成功');
		                       location.href='".U('Mo/index')."';
			 	          	</script>";
            }else{
                echo  '<meta charset="UTF-8">';
                echo "<script>
		                       alert('添加失败');
		                       location.href='".U('Mo/add')."';
			 	          	</script>";
            }
        }else{
            $this->display();
        }
    }
    /*修改新闻*/
    function modnews(){
        $path=__APP__;
        if(IS_POST){
            $params=I("post.");
            $img=uploadimg($_FILES['imagename']);
            if($img){

                $params['imagename']=ltrim($img['savepath'].$img['savename'],"./");

            }
            $re=M("moban")->where("id=".$params['id'])->save($params);
            if($re){
                echo  '<meta charset="UTF-8">';
                echo "<script>
		                       alert('修改成功');
		                       location.href='".U('Mo/index')."';
			 	          	</script>";
            }else{
                echo  '<meta charset="UTF-8">';
                echo "<script>
		                       alert('修改失败');
		                       location.href='".U('Mo/index')."';
			 	          	</script>";
            }
        }else{
            $id=I("get.id");
            $info=M("moban")->find($id);

            $this->info=$info;
            $this->display();
        }
    }

    /*删除新闻*/
    function delnews(){
        $path=__APP__;
        $id=I("get.id");
        $re=M("moban")->delete($id);
        if($re){
            echo  '<meta charset="UTF-8">';
            echo "<script>
		                       alert('删除成功');
		                       location.href='".U('Mo/index')."';
			 	          	</script>";
        }else{
            echo  '<meta charset="UTF-8">';
            echo "<script>
		                       alert('删除失败');
		                       location.href='".U('Mo/index')."';
			 	          	</script>";
        }

    }
    /*ajax删除*/
    function selectDel(){

        $ids=I("post.ids");
        $re=M("moban")->where("id in(".$ids.")")->delete();
        if($re){

            $data=array(
                "state"=>1,
                "msg"=>"删除成功"
            );
            $this->ajaxReturn($data);
        }else{
            $data=array(
                "state"=>2,
                "msg"=>"删除失败"
            );
            $this->ajaxReturn($data);
        }
    }


}