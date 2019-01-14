<?php
/*上传单个图片*/
function uploadimg($file){
                 $upload = new \Think\Upload();// 实例化上传类   
                $upload->maxSize =0;// 设置附件上传大小  
                $upload->exts  = array('jpg', 'gif', 'png', 'jpeg','xls','xlsx');
                $upload->autoSub=false;
                $upload->savePath  = './Uploads/'; 
                $info= $upload->uploadOne($file);

                    return $info;


}
/*上传多个图片*/
function uploadmore(){
    $upload = new \Think\Upload();// 实例化上传类
    $upload->maxSize =0;// 设置附件上传大小
    $upload->exts  = array('jpg', 'gif', 'png', 'jpeg');
    $upload->autoSub=false;
    $upload->savePath  = './Uploads/';
    // 上传文件
    $info   =   $upload->upload();
    return $info;
}
/*pc端新闻分类菜单下拉列表查询*/
function getcate($fid=0,&$arr=array(),$level=0){
	  $level+=2;
    $result=M("newstypes")->where("fid=".$fid)->order("sort desc")->select();
    foreach ($result as $k=> $v) {
    	    $v['typename']=str_repeat("&nbsp;&nbsp;",$level)."|--".$v['typename'];  
    	     $arr[]=$v;
    	     getcate($v['id'],$arr,$level);
    }
    return $arr;
}
/*产品分类*/
function productcate($fid=0,&$arr=array(),$level=0){
    $level+=2;
    $result=M("shoptype")->where("fid=".$fid)->order("sort desc")->select();
    foreach ($result as $k=> $v) {
        $v['typename']=str_repeat("&nbsp;&nbsp;",$level)."|--".$v['typename'];
        $arr[]=$v;
        getcate($v['id'],$arr,$level);
    }
    return $arr;
}
/*获取父类所有的子列id*/
function getcateson($id=1,&$arr=array()){
    
    $result=M("newstypes")->where("fid=".$id)->order("sort desc")->select();
    foreach ($result as $k=> $v) {
         
           $arr[]=$v['id'];
           getcateson($v['id'],$arr);
    }
    return $arr;
}
//获取父级以及父级的子分类
function getsonpath($dpid){
    $cnd['id']=$dpid;
    $cnd['_string']="find_in_set($dpid,fullpath)";
    $cnd['_logic']="or";
    $menu=M("newstypes")->field("id")->where($cnd)->select();
    foreach ($menu as $v){
        $arr[]=$v['id'];
    }
    return $arr;
}
/*手机端端新闻分类菜单下拉列表查询*/
function phonegetcate($fid=0,&$arr=array(),$level=0){
    $level+=2;
    $result=M("phonenewstypes")->where("fid=".$fid)->order("sort desc")->select();
    foreach ($result as $k=> $v) {
          $v['typename']=str_repeat("&nbsp;&nbsp;",$level)."|--".$v['typename'];  
           $arr[]=$v;
           getcate($v['id'],$arr,$level);
    }
    return $arr;
}
/*查询一个分类及其父级分类*/
function getcatepath($fid,&$arr=array()){
   $re=M("newstypes")->where("id=".$fid)->select();
   foreach($re as $k=>$v){
       $arr[]=$v;
       getcatepath($v['fid'],$arr);
   }
  
    krsort($arr); 
    return $arr;
}
/*分页查询*/
function query($count,$pagesize){

  $Page= new \Think\Page($count,$pagesize);// 实例化分页类 传入总记录数和每页显示的记录数(25)
  $Page->setConfig('header','共 %TOTAL_ROW% 条记录');
  $Page->setConfig('prev','上一页');
  $Page->setConfig('next','下一页');
  $Page->setConfig('first','首页');
  $Page->setConfig('last','%尾页%');
  $Page->setConfig('theme', '%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
  return $Page;
  
}
//使用 CURL 传送GET、POST数据,获取结果
function CURLSend($url, $method = 'get', $data = '') {
    $ch = curl_init(); //初始化
    $headers = array('Accept-Charset: utf-8');
    //设置URL和相应的选项
    curl_setopt($ch, CURLOPT_URL, $url); //指定请求的URL
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method)); //提交方式
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); //不验证SSL
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); //不验证SSL
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); //设置HTTP头字段的数组
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible;MSIE5.01;Windows NT 5.0)'); //头的字符串

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1); //自动设置header中的Referer:信息
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); //提交数值
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //是否输出到屏幕上,true不直接输出
    $temp = curl_exec($ch); //执行并获取结果
    curl_close($ch);
    return $temp; //return 返回值
}
function subtext($text, $length)
{
    if(mb_strlen(htmlspecialchars_decode($text), 'utf8') > $length)
        return mb_substr(htmlspecialchars_decode($text), 0, $length, 'utf8').'...';
    return $text;
}

