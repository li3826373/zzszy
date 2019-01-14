<?php
namespace Shuadmin\Controller;
use Think\Controller;
use Think\Upload;
class IndexController extends BaseController {


    public function index(){

     
      $this->display();

        }
     function main(){


         /*新闻数*/
         $newscount=M("news")->count();
         $this->newscount=$newscount;
//         最新留言
         $message=M("message")->order("dateandtime desc")->limit(6)->select();
         $this->message=$message;
         /*最新新闻*/
         $news=M("news")->order("dateandtime desc")->limit(6)->select();
         $this->news=$news;
     	  $this->display();
     }   
     /*推出登录*/
     function logout(){
         session("adminuser",null);
         session("state",null);
         $this->redirect('Login/index');

     }
     /*会员管理*/
     function user(){

        $this->display();
     }
     /*系统设置*/
     function config(){
            if(IS_POST){
                $old=M("config")->find(1);
                $title=I("post.title");
                $phone=I("post.phone");
                $describle=I("post.describle");
                $keyword=I("post.keyword");
                $icp=I("post.icp");
                $email=I("post.email");
                $tel=I("post.tel");
                $address=I("post.address");
                $qq=I("post.qq");


                     $logoimg=uploadimg($_FILES['logo']);
                      if($logoimg){
                          $logo=ltrim($logoimg['savepath'].$logoimg['savename'],'./');
                           /*把原先的图片删除掉*/
                     unlink("./Uploads/{$old['logo']}");
                    }else{
                          $logo=$old['logo'];
                    }
                    $weixin_code=uploadimg($_FILES['weixin_code']);
                if($weixin_code){
                    $weixin=ltrim($weixin_code['savepath'].$weixin_code['savename'],'./');
                }else{
                    $weixin=$old['weixin_code'];
                }
                $data=array(
                     "title"=>$title,
                     "phone"=>$phone,
                     "describle"=>$describle,
                     "keyword"=>$keyword,
                     "logo"=>$logo,
                      "weixin_code"=>$weixin,
                      "icp"=>$icp,
                      "email"=>$email,
                      "tel"=>$tel,
                      "address"=>$address,
                    "qq"=>$qq
                    );
                   $result=M("config")->where("id=1")->save($data);
                   
                   $this->redirect('Index/config');

            }else{
               /*查询配置表*/
               $info=M("config")->find(1);
               $this->info=$info;
               $this->display();
          }
                
     }
     
   
}