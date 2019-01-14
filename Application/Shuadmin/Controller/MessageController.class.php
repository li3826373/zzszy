<?php
namespace Shuadmin\Controller;
class MessageController extends BaseController{

    function index(){
        /*分页查询报名信息*/
         $count=M("message")->count();
         $page=query($count,12);
         $pagesize=$page->show();
         $list=M("message")->limit($page->firstRow.','.$page->listRows)->select();
        
        $this->pagesize=$pagesize;
        $this->list=$list;
    	$this->display();
    }
/*查看*/
function look(){
   $id=I("get.id");
   $result=M("message")->where("id=".$id)->find();

   $this->info=$result;
   $this->display();
   

}
/*删除报名信息*/
function delete(){

	 $id=I("post.id");
	 $re=M("message")->where("id=".$id)->delete();
	 if($re){
	 	 $data=array("state"=>1,"msg"=>"删除成功");
	 	 $this->ajaxReturn($data);

	 }else{
	 	  $data=array("state"=>2,"msg"=>"删除失败");
	 	 $this->ajaxReturn($data);
	 }
}
}
