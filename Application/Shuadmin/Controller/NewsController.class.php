<?php
namespace Shuadmin\Controller;
use Think\Controller;
class NewsController extends BaseController {


   /*新闻分类*/
   function type(){
         $re=getcate();

      $this->list=$re;
   	 $this->display();
   }
   /*添加新闻分类*/
   function addtype(){

   	$path=__APP__;
     if(IS_POST){
         $typename=I("post.typename");
         $fid=I("post.fid");//父级的id
         $state=I("post.state");
         $sort=I("post.sort");
         $link=I("post.link");
         
         /*查询此分类名是否已经在当前父级分类下已经存在*/
            $childcate=getcate($fid);//获得当前父级分类的所有子分类
            $arr=array();
            foreach ($childcate as $v) {
            	    $arr[]=$v['id'];
            }
              if(!empty($arr)){
	            $res=M("newstypes")->where("typename='{$typename}' and id in(".implode(",", $arr).")")->select();
	           if($res){
	           	   echo  '<meta charset="UTF-8">';
				        echo "<script>
		                       alert('新闻分类已存在');
		                       location.href='{$path}/Shuadmin/News/type';
				          	</script>";
				          	exit();
	           }
	       }
          
         /*查找父级的fullpath*/
         $fullpath=M("newstypes")->field("fullpath")->where("id=".$fid)->find();
         $fullpath=$fullpath['fullpath'];
         if($fid!=0){
         $data=array("typename"=>$typename,"fid"=>$fid,"fullpath"=>$fullpath.",".$fid);
         }else{
         	$data=array("typename"=>$typename,"fid"=>$fid);
         }
         if(!empty($state)){
		         	  $data['state']=$state;
		         }
        if(!empty($sort)){
                $data['sort']=$sort;
             }
         if(!empty($link)){
             $data['link']=$link;
         }
              $img=uploadimg($_FILES['imagecate']);
              /*图文*/
            if($img){
                  $size=$img['size'];
              
              if($size>'804800'){
                   echo  '<meta charset="UTF-8">';
               echo "<script>
                           alert('请上传小于800kb的图片');
                           location.href='".U('News/type')."';
                    </script>";
                    
                    exit;
              }
              $data['imagecate']=ltrim($img['savepath'].$img['savename'],"./");
            
            }
         $re=M("newstypes")->add($data);
       
         if($re){

         	   echo  '<meta charset="UTF-8">';
			        echo "<script>
	                       alert('添加成功');
	                       location.href='{$path}/Shuadmin/News/type';
			          	</script>";
         }else{
         	 echo  '<meta charset="UTF-8">';
			        echo "<script>
	                       alert('添加失败');
	                       location.href='{$path}/Shuadmin/News/type';
			          	</script>";
         }

     }else{
           
           $re=getcate();

          $this->re=$re;
     	$this->display();
     }
   }
   /*修改新闻分类*/
   function edit(){
   	  $path=__APP__;
   	      if(IS_POST){
               $typename=I("post.typename");
               $id=I("post.id");
               $oldtype=M("newstypes")->field("typename,fid")->find($id);
               $oldtypename=$oldtype['typename'];
               $oldfid=$oldtype['fid'];
               $fid=I("post.fid");
               $state=I("post.state");
               $sort=I("post.sort");
               $link=I("post.link");
               if($typename!=$oldtypename || $fid!=$oldfid){
                /*查询此分类名是否已经在当前父级分类下已经存在*/
		            $childcate=getcate($fid);//获得当前父级分类的所有子分类
		            $arr=array();
		            foreach ($childcate as $v) {
		            	    $arr[]=$v['id'];
		            }
		              if(!empty($arr)){
			            $res=M("newstypes")->where("typename='{$typename}' and id in(".implode(",", $arr).")")->select();
			           if($res){
			           	   echo  '<meta charset="UTF-8">';
						        echo "<script>
				                       alert('新闻分类已存在');
				                       location.href='{$path}/Shuadmin/News/type';
						          	</script>";
						          	exit();
			           }
			       }
			   }
			       /*查找父级的fullpath*/
		         $fullpath=M("newstypes")->field("fullpath")->where("id=".$fid)->find();
		         $fullpath=$fullpath['fullpath'];
		         if($fid!=0){
		         $data=array("typename"=>$typename,"fid"=>$fid,"fullpath"=>$fullpath.",".$fid);
		         }else{
		         	$data=array("typename"=>$typename,"fid"=>$fid);
		         }
		         if(!empty($state)){
		         	  $data['state']=$state;
		         }
                if(!empty($sort)){
                $data['sort']=$sort;
             }
              if(!empty($link)){
                  $data['link']=$link;
              }
              $img=uploadimg($_FILES['imagecate']);
            if($img){
                  $size=$img['size'];
              
              if($size>'804800'){
                   echo  '<meta charset="UTF-8">';
               echo "<script>
                           alert('请上传小于800kb的图片');
                           location.href='".U('News/type')."';
                    </script>";
                    
                    exit;
              }
              $data['imagecate']=ltrim($img['savepath'].$img['savename'],"./");
            
            }
		         $rr=M("newstypes")->where("id=".$id)->save($data);
                   if($rr){

	         	   	    echo  '<meta charset="UTF-8">';
				        echo "<script>
		                       alert('修改成功');
		                       location.href='{$path}/Shuadmin/News/type';
							          	</script>";
			         }else{
			         	 echo  '<meta charset="UTF-8">';
						        echo "<script>
				                       alert('修改失败');
				                       location.href='{$path}/Shuadmin/News/type';
						          	</script>";
			         }

   	      }else{
        	$id=I("get.id");
           	/*查询当前新闻类型*/
           	$this->info=M("newstypes")->find($id);
              
              $re=getcate();

             $this->re=$re;
             $this->display();
         }
   } 
/*删除新闻分类*/
function deltype(){
	$path=__APP__;
   $id=I("get.id",0,"intval");
   /*判断当前分类新有新闻不能删除*/
   $re=M("news")->where("typeid=".$id)->select();
	   if($re){
	   	  echo  '<meta charset="UTF-8">';
				        echo "<script>
		                       alert('分类下有新闻不能删除');
		                       location.href='{$path}/Shuadmin/News/type';
				          	</script>";
	        }else{
                $result=M("newstypes")->where("id=".$id)->delete();
                 if($result){
                 	 echo  '<meta charset="UTF-8">';
				        echo "<script>
		                       alert('删除成功');
		                       location.href='{$path}/Shuadmin/News/type';
				          	</script>";
                 }else{
                 	  echo  '<meta charset="UTF-8">';
				        echo "<script>
		                       alert('删除失败');
		                       location.href='{$path}/Shuadmin/News/type';
				          	</script>";
                 }
	         }

}


	function index(){
         $cate=getcate();
       $this->cate=$cate;

        $typeid=I("get.typeid");
       
        if($typeid){
            $count=M("news")->where("typeid=".$typeid)->count();
            $page=query($count,10);
            $pagesize=$page->show();
            $this->pagesize=$pagesize;
      $info=M("news")->where("typeid=".$typeid)->join("newstypes on news.typeid=newstypes.id")->limit($page->firstRow.','.$page->listRows)->select();
      $this->info=$info;
      $this->typeid=$typeid;
      $this->display();
        }else{

          /*分页查询所有的新闻和新闻所属分类*/
           $count      = M("news")->count();// 查询满足要求的总记录数
           $Page       = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        
      $Page->setConfig('header','共 %TOTAL_ROW% 条记录');
       $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('first','首页');
        
         $Page->setConfig('last','尾页');
          $Page->setConfig('theme', '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
         
    $show= $Page->show();// 分页显示输出
          $info=M("news")->join("newstypes on news.typeid=newstypes.id")->limit($Page->firstRow.','.$Page->listRows)->select();
         
        $this->info=$info;
        $this->pagesize=$show;
        

		     $this->display();
       }
	}
	/*新增*/
	function add(){
		$path=__APP__;
       if(IS_POST){
       	    $title=I("post.title");
       	    $typeid=I("post.typeid");
       	    $content=I("post.content");
       	    $pcontent=I("post.pcontent");
       	    $sort=I("post.sort",0,"intval");
       	    $hit=I("post.hit",0,"intval");
              $img=uploadimg($_FILES['imagename']);

           $pimg=uploadimg($_FILES['img1']);
             	    $data=array(
                        "title"=>$title,
                        "typeid"=>$typeid,
                        "content"=>$content,
                         "sort"=>$sort,
                          "hit"=>$hit,
                        "pcontent"=>$pcontent
             	    	);
              
             $hot=I("post.hot");
       	    if(!empty($hot)){
       	    	 $data['hot']=$hot;
       	    }
       	    $area=I("post.area");
            if(!empty($area)){
                $data['area']=$area;
            }
       	    $describle=I("post.describle");
       	    if(!empty($describle)){
       	    	 $data['describle']=$describle;
       	    }

       	    if($img){
              $size=$img['size'];

              if($size>'804800'){
                   echo  '<meta charset="UTF-8">';
                echo "<script>
                           alert('请上传小于800kb的图片');
                           location.href='{$path}/Shuadmin/News/index';
                    </script>";
                    
                    exit;
              }
	          $data['imagename']=ltrim($img['savepath'].$img['savename'],"./");

	            }
           if($pimg){
               $size=$pimg['size'];

               if($size>'804800'){
                   echo  '<meta charset="UTF-8">';
                   echo "<script>
                           alert('请上传小于800kb的图片');
                           location.href='{$path}/Shuadmin/News/index';
                    </script>";

                   exit;
               }
               $data['img1']=ltrim($pimg['savepath'].$pimg['savename'],"./");

           }
       	     $info=M("news")->add($data);
          
       	     if($info){
       	     	   echo  '<meta charset="UTF-8">';
				        echo "<script>
		                       alert('添加成功');
		                       location.href='{$path}/Shuadmin/News/index';
			 	          	</script>";
       	            }else{
       	            	 echo  '<meta charset="UTF-8">';
				        echo "<script>
		                       alert('添加失败');
		                       location.href='{$path}/Shuadmin/News/add';
			 	          	</script>";
       	            }

       }else{
           /*查询新闻分类*/
         $cate=getcate();
       $this->cate=$cate;

       $this->display();
        }
	}
	/*修改新闻*/
	function modnews(){
		$path=__APP__;
		if(IS_POST){
            $articleid=I("post.articleid");
             $title=I("post.title");
       	    $typeid=I("post.typeid");
       	    $content=I("post.content");
       	    $pcontent=I("post.pcontent");
       	    $sort=I("post.sort",0,"intval");
       	    $hit=I("post.hit",0,"intval");
       	    $data=array(
                  "title"=>$title,
                  "typeid"=>$typeid,
                  "content"=>$content,
                   "sort"=>$sort,
                    "pcontent"=>$pcontent,
                    "hit"=>$hit,
                    "dateandtime"=>date("Y:m:d H:i:s")
       	    	);
             $hot=I("post.hot");
       	    if(!empty($hot)){
       	    	 $data['hot']=$hot;
       	    }
       	    $area=I("post.area");
            if(!empty($area)){
                $data['area']=$area;
            }
       	    $describle=I("post.describle");
       	    if(!empty($describle)){
       	    	 $data['describle']=$describle;
       	    }
          
       	    $img=uploadimg($_FILES['imagename']);
            if($img){

            	$data['imagename']=ltrim($img['savepath'].$img['savename'],"./");

            }
            $pimg=uploadimg($_FILES['img1']);
            if($pimg){

                $data['img1']=ltrim($pimg['savepath'].$pimg['savename'],"./");

            }
             $re=M("news")->where("articleid=".$articleid)->save($data);
              if($re){
       	     	   echo  '<meta charset="UTF-8">';
				        echo "<script>
		                       alert('修改成功');
		                       location.href='{$path}/Shuadmin/News/modnews/articleid/{$articleid}';
			 	          	</script>";
       	            }else{
       	            	 echo  '<meta charset="UTF-8">';
				        echo "<script>
		                       alert('修改失败');
		                       location.href='{$path}/Shuadmin/News/modnews/articleid/{$articleid}';
			 	          	</script>";
       	            }
		}else{
         $articleid=I("get.articleid");
         $info=M("news")->field("news.*")->join("newstypes as a on news.typeid=a.id")->where("articleid=".$articleid)->find();
        
         $this->info=$info;
          /*查询新闻分类*/
         $cate=getcate();
       $this->cate=$cate;
		$this->display();
	  }
	}

	/*删除新闻*/
	function delnews(){
     $path=__APP__;
     $articleid=I("get.articleid");
     $imagename=M("news")->field("imagename")->find($articleid);
     $re=M("news")->where("articleid=".$articleid)->delete();
     if($re){
          /*删除图片*/
          unlink("./Uploads/{$imagename['imagename']}");
         echo  '<meta charset="UTF-8">';
				        echo "<script>
		                       alert('删除成功');
		                       location.href='{$path}/Shuadmin/News/index';
			 	          	</script>";
     }else{
     	    echo  '<meta charset="UTF-8">';
				        echo "<script>
		                       alert('删除失败');
		                       location.href='{$path}/Shuadmin/News/index';
			 	          	</script>";
     }

	}
	/*ajax删除*/
	function selectDel(){

		$ids=I("post.ids");
		$info=M("news")->field("imagename")->where("articleid in(".$ids.")")->select();
        
		$re=M("news")->where("articleid in(".$ids.")")->delete();
		if($re){
              /*清理图片*/
             foreach ($info as $key => $v) {
             	 unlink("./Uploads/{$v['imagename']}");
             }

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