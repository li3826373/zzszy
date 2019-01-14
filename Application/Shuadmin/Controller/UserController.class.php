<?php
/**
 * Created by PhpStorm.
 * User: 李进平
 * Date: 2018/7/13
 * Time: 10:39
 */
namespace Shuadmin\Controller;
use Shuadmin\Service\UserService;

class UserController extends BaseController
{
   function index(){
      $params=I("get.");
      $obj=new UserService();
      $res=$obj->ulist($params);
      $this->list=$res['data'][0];
      $this->pagesize=$res['data'][1];
       session("userlist",$res['data'][0]);
       $this->display();
   }
   /*禁用*/
    function dis(){
          $id=I("post.id");
          $res=M("user")->where("id=".$id)->save(array("is_delete"=>1));
          if($res){
              $json['status']=true;
              $json['info']="已禁用";
          }else{
              $json['status']=false;
              $json['info']="禁用失败";
          }
          $this->ajaxReturn($json);

    }
    /*解除禁用*/
    function useing(){
        $id=I("post.id");
        $res=M("user")->where("id=".$id)->save(array("is_delete"=>0));
        if($res){
            $json['status']=true;
            $json['info']="已解除";
        }else{
            $json['status']=false;
            $json['info']="解除失败";
        }
        $this->ajaxReturn($json);
    }
//   充值列表
function chong(){
    $params=I("get.");
    $obj=new UserService();
    $res=$obj->chong($params);
    $this->list=$res['data'][0];
    $this->page=$res['data'][1];
       $this->display();
}
/*提现列表*/
    function ti(){
      $params=I("get.");
      $obj=new UserService();
      $res=$obj->ti($params);
      $this->list=$res['data'][0];
      $this->page=$res['data'][1];

        $this->display();
    }
    /*服务中心申请列表*/
    function service(){
         /*查看个人服务中心*/
        $params=I("get.");
        $obj=new UserService();
        $res=$obj->servicelist($params);
        $this->list=$res['data'][0];
        $this->page=$res['data'][1];
        $this->display();
    }
    /**
     * @User 一秋
     * @param $userid  用户id
     * @param $out_biz_no 编号
     * @param $payee_account 提现的支付宝账号
     * @param $amount 转账金额
     * @param $payee_real_name 账号的真实姓名
     * @return bool|Exception
     */
    /*提现方法*/
    public function money(){
        /*加载第三方类库*/
        /*  Vendor('Org.Util.pay');*/
        Vendor('pay.aop.AopClient');
        Vendor('pay.aop.request.AlipayFundTransToaccountTransferRequest');
        Vendor('pay.aop.SignData');
        $id=I("post.id");//提现申请id
        $username=I("post.username");//提现用户
        $amount=I("post.money");//转账金额
        $info=M("user")->field("overmoney,integral")->where(array("username"=>$username))->find();
        if($info['overmoney']<$amount && $info['integral']<$amount){
            echo  '<meta charset="UTF-8">';
            echo "<script>
		                           alert('体现用户余额不足,无法提现');
		                           location.href='".U('User/ti')."';
		                    </script>";

            exit;
        }
        $out_biz_no=I("post.out_biz_no");//订单号
        $payee_account=I("post.payee_account");//支付宝账号
        $pay_real_name=I("post.pay_real_name");
        $payer_show_name="河南树正电子";
        $remark="河南树正电子提现";
        $aop = new  \AopClient ();
        $aop->gatewayUrl =C('gatewayUrl');
        $aop->appId =C("appid");
        $aop->rsaPrivateKey = 'MIIEowIBAAKCAQEArrcZ20dXdWhqYlw0XhnKsH2gCMuWl6iGkIiQy8jg1pJ6E24AzWTyD509NE72ckNV28qIiSvtH7wTmL9eK6rfW2O6uXYJdkFPgpnDRufNRB5JjRhc47hhKoq8f8/CBIhnEXDYwK87mWX3kXVSIWnIPeWdYXKJrgfg7PFY2e7FS+4l60ESlgZCWGd00+K/ym03LJ+DIFMzq4yMbbgMOWlJfqn0Q7wUB/ouEZWxCFgeQOh2cYF24LOd9vdrr1X1G5R7deqsQwSmc/AscudIEWionAK54zDtZsa1qlNmSFg94HgjdgpI0px8jgQu7SEshjQXObQBjcOYykA8COLIi5OupwIDAQABAoIBAEdbBLZn5rJO2NQfMEwWYI/AXvH4pCKAc6ToAasY+aro2+6/iJhaV/pEj9CjR0fXdGN1zlmnlQrW93H1BnLzMJHUo1hHewnPFrgSMIzu8wiVDhkQEC/5B8YmL2JL6cOMKfwXiI75gm/eE0RXFBoNZ/jPpH0+GDj5gsWCtHUdbXratdrLQ5w0DbzRyzuw9Pj4i0HbEff37NP9KAbIBGVQHFxBZzcg+5enPBFAMkaFZuPsNugPOpyPy/r0ffAGOECThmAFF8w7BggY7kWe/7wXdT/yRgwNRvkJtJmzVEkAbmNnHeyWNprMJYMpgQadfTr3YGb8usETHWqZB5RT25RhWiECgYEA2EK80KoVBegzb6Mpwrzm0pA3uL+3mycyeHYLaheQqL6YPRzs8gBta+mGRlqIuIxKT3qEzWjij1eIIlWqGfjnYblwJgHwXckzhfQRL2l09pMXZAhn9ysLtq+chOSpduFa7xY0sEjc1Q1Nkv4nV7BtoPOTPlU1FFuPCN2zw5aAPYkCgYEAztH/BZ0ilrybnv2IQt+Z+onKP3RZhHiMsUhqlvLD+NWq9fwEJqIo2gZGvdgHxnv8cvEJYE12ML3EtPuYOi23MQBLi8UPSesxC5XpnSGeVIUlWljF/LIhgqrVWkem+oftdzaIuaPo2ssnFZdXmhr/EevKjUK3H3k/Oqd38GRDLq8CgYBoLO3rzqLF768nbf/l8T133UUaWDBIKz+iy6p/9s4Wm4mWluKFSTNssleeeGo4DEyXsLtcf9PznQTVFVMVo9NCPiQKCxEQ/KU3N/U3U6OmTAEOjQYYCMJhxIKveb8wpyo41geXi+HlN71Qu+GT2kdVK0CY3E7veZOTf8jixRMNkQKBgQDLRFLrlot3MLmWYkG40ACK3z/aa6TLcJt+Zdj0sHZxKrbS+jSuL7QMzBxc22SQ7CyqX0HC137w2qHo6lmr3GBulYaqQSwMj03twBIRcAgns5CJojQ8bmpG/VWhXEi9dDiscPmh0nm6B+5K5yqe+Cd22pKBkW9fTJAbOprDthbdWwKBgAEj0OMXzc15ikZ4XPlAcoLW5QBqEA6sWqnUiwJdnZ3FkkNb7HxE1Q4pC4Vg1gOeU9/OF21qPVVybLV2G8tChtnTi+5HNQgu4fumYMNqApEE67hAn3nU47oKOjuxA2dqcLViwBDKaV2ayKyn0n8QmmuH2yXgFO9JFORzhX1YBCcg';
        $aop->alipayrsaPublicKey='MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArHjOxiS+i/KvwhiWrjj4ka1Yp/tbue8KLLiLIAlCKU7mqp5g9UcRZoC5/+4Fk4Qhig/1lzXXXgujjneFyST+RHsaUlC8ePyJdZlDW5/Z4+/fxbydqylSWZMv8UquXZMa4DamliL1lSQ8cXnQJWzJ+iZA68+ggtbLSoUAh6El+Dumoc9D1zDRWsUyPILGuxDYMqMb+qTaQX1afjHM517H+NWP1kZmcCrT4zgjW+eZGtISavfb+eUXGeAuTubQQHjeG4FJOd3DkBq/OflQHz9b2pymynja17gdOjqNddf1LQFE3hqWTf5jMRliZACGSKluo6BMzFM3DX7H3wiIk6PWKwIDAQAB';
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset='utf-8';
        $aop->format='json';
        $request = new \AlipayFundTransToaccountTransferRequest ();
        $request->setBizContent("{" .
            "\"out_biz_no\":\"$out_biz_no\"," .
            "\"payee_type\":\"ALIPAY_LOGONID\"," .
            "\"payee_account\":\"$payee_account\"," .
            "\"amount\":\"$amount\"," .
            "\"payer_show_name\":\"$payer_show_name\"," .
            "\"payee_real_name\":\"$pay_real_name\"," .
            "\"remark\":\"$remark\"" .
            "}");
        $result = $aop->execute ( $request);
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        /*var_dump($result->$responseNode);获取所有的状态*/
        if(!empty($resultCode)&&$resultCode == 10000){
            /*成功后更新数据库*/
            M("getmoney")->where("id=".$id)->save(array("status"=>1));
          /*提现者扣余额或者积分*/
          if($info['integral']>$amount){//扣积分
              M("user")->where(array("username"=>$username))->setDec("integral",$amount);
          }else{
              //扣余额
              M("user")->where(array("username"=>$username))->setDec("overmoney",$amount);
          }
        } else {
           /* echo "失败".$result->$responseNode->sub_msg;*/
            echo  '<meta charset="UTF-8">';
            echo "<script>
		                           alert('提现失败".$result->$responseNode->sub_msg."');
		                           location.href='".U('User/ti')."';
		                    </script>";

            exit;
        }

    }
//    拒绝提现
function refus(){
   $id=I("post.id");
   $res=M("getmoney")->where(array("id"=>$id))->save(array("status"=>2));
   if($res){
       $json['status']=true;
       $json['info']="已拒绝";
   }else{
       $json['status']=false;
       $json['info']="拒绝失败";
   }
   $this->ajaxReturn($json);
}
/*同意或者拒绝服务中心申请*/
    function tong(){
           $num=I("post.num");
           $id=I("post.id");
           if($num==1){
               //同意
               $res=M("user")->where("id=".$id)->save(array("service_status"=>2));
               if($res){
                   $json['status']=true;
                   $json['info']="已同意";
               }else{
                   $json['status']=false;
                   $json['info']="同意失败";
               }
           }else if($num==2){
               //拒绝
               $res=M("user")->where("id=".$id)->save(array("service_status"=>3));
               if($res){
                   $json['status']=true;
                   $json['info']="已拒绝";
               }else{
                   $json['status']=false;
                   $json['info']="拒绝失败";
               }
           }
           $this->ajaxReturn($json);
    }
    /*会员导出*/
    public function import(){
        $news_list=session('userlist');
        //重新组装需要的数据
        foreach($news_list as $key=>$val){
            $data[$key][id]=$val['id'];
            $data[$key][username]=$val['username'];
            $data[$key][name]=$val['name'];
            $data[$key][phone]=$val['phone'];
            $data[$key][recommend]=$val['recommend'];
            $data[$key][directnumber]=$val['directnumber'];
            $data[$key][num]=$val['num'];
            $data[$key][integral]=$val['integral'];
            $data[$key][overmoney]=$val['overmoney'];
            $data[$key][repeatbuy]=$val['repeatbuy'];
            $data[$key][shares]=$val['shares'];
            $data[$key][addtime]=substr($val['addtime'],0,10);
            $data[$key][num]=$val['num'];
            if($val['servicecenter']==1){
                $data[$key][servicecenter]='社区服务中心';
                $data[$key][servicename]=$val['servicename'];
            }elseif($val['servicecenter']==2){
                $data[$key][servicecenter]="旗舰服务中心";
                $data[$key][servicename]=$val['servicename'];
            }else{
                $data[$key][servicecenter]="暂无";
                $data[$key][servicename]="暂无";
            }
            $data[$key][servicepeople]=$val['servicepeople'];
            $data[$key][servicephone]=$val['servicephone'];
            if($val['level']==0){
                $data[$key][level]="普通会员";
            }else{
                $data[$key][level]="VIP会员";
            }
        }


        //给excel做标题
        $headArr=array('用户ID','用户名','姓名','注册手机号','推荐人','直推人数','第三层人数','积分余额','账户余额','重消余额','股权余额','注册时间','服务中心类型','服务中心名字','联系人','联系电话','会员类型');
        //给导出的excel命名(前缀)
        $filename="user";
        $this->getExcel($filename,$headArr,$data);
    }
    private  function getExcel($fileName,$headArr,$data){
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");

        $date = date("Y_m_d_H:i:s",time());
        $fileName .= "_{$date}.xls";

        //创建PHPExcel对象，注意，不能少了\
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();

        //设置表头
        $key = ord("A");
        //print_r($headArr);exit;
        foreach($headArr as $v){
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }

        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();

        //print_r($data);exit;
        foreach($data as $key => $rows){ //行写入
            $span = ord("A");
            foreach($rows as $keyName=>$value){// 列写入
                $j = chr($span);
                $objActSheet->setCellValue($j.$column, $value);
                $span++;
            }
            $column++;
        }

        $fileName = iconv("utf-8", "gb2312", $fileName);

        //重命名表
        //$objPHPExcel->getActiveSheet()->setTitle('test');
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean();//清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
    }
    /*会员导入*/
    public function export(){
        $file=$_FILES['exceldata'];
        $name=$file['name'];
        $name=explode('.',$name);
        $hz=array_pop($name);
        if($hz=='xls'|| $hz=='xlsx'){
            $aa=uploadimg($file);
        }else{
            echo  '<meta charset="UTF-8">';
            echo "<script>
	                       alert('上传文件格式不对');
	                       location.href='".U('User/index')."';
			          	</script>";
            exit();
        }
        $excel=ltrim($aa['savepath'].$aa['savename'],"./");
        $filename='./Uploads/'.$excel;
        $this->excel_save($filename,$hz);
    }
    //调用获取的excel数据保存到数据库表中

    public function excel_save($filename, $exts='xls'){
//导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        import("Org.Util.PHPExcel");
        //创建PHPExcel对象，注意，不能少了\
        $PHPExcel=new \PHPExcel();
        //如果excel文件后缀名为.xls，导入这个类

        if($exts == 'xls'){
            import("Org.Util.PHPExcel.Reader.Excel5");
            $PHPReader=new \PHPExcel_Reader_Excel5();
        }else if($exts == 'xlsx'){
            import("Org.Util.PHPExcel.Reader.Excel2007");
            $PHPReader=new \PHPExcel_Reader_Excel2007();
        }
        //载入文件
        $PHPExcel=$PHPReader->load($filename);
        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet=$PHPExcel->getSheet(1);
        //获取总列数
        $allColumn=$currentSheet->getHighestColumn();
        //获取总行数
        $allRow=$currentSheet->getHighestRow();
        //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for($currentRow=2;$currentRow<=$allRow;$currentRow++){
            //从哪列开始，A表示第一列
            for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
                //数据坐标
                $address=$currentColumn.$currentRow; //A1 B1
                //读取到的数据，保存到数组$arr中
                $cell =$currentSheet->getCell($address)->getValue();
                //封装二维数组
                $data[$currentRow][$currentColumn]=$cell;

            }//列循环


        } //行循环

        //插入到数据库
        $all=array();
        $lastusername="";
        foreach($data as $val){
            $arr['username']=$val['A'];
            $arr['name']=htmlspecialchars($val['B']);
            if($val['C']=='男'){
                $arr['sex']=0;
            }else if($val['C']=='女'){
                $arr['sex']=1;
            }else{
                $arr['sex']=3;
            }

            $d = 25569;
            $t = 24 * 60 * 60;
            $time= gmdate('Y-m-d H:i:s', ($val['D'] - $d) * $t);
            $arr['addtime']=$time;
            $tong=gmdate('Y-m-d H:i:s', ($val['E'] - $d) * $t);
            $arr['tongtime']=$tong;
            if($val['F']==""){
                $arr['recommend']="";
            }else{
                $arr['recommend']=$val['F'];
            }
            $arr['servicename']=$val['G'];
            if($val['H']==""){
                $arr['serviceaddress']="";
            }else{
                $arr['serviceaddress']=$val['H'];
            }

          $arr['integral']=$val['I'];
            $arr['repeatbuy']=$val['J'];
            $arr['shares']=$val['K'];
            if($lastusername<>$val['A']){
                $lastusername=$val['A'];//保留此次用户名
                $all[]=$arr;
            }else{
                echo  '<meta charset="UTF-8">';
                echo "<script>
	                       alert('用户".$val['A']."名重复,导入失败');
	                       location.href='".U('User/index')."';
			          	</script>";
                exit();
            }

        }
        //批量插入
        M('user')->addAll($all);
        //删除上传的excel
        unlink($filename);
        echo  '<meta charset="UTF-8">';
        echo "<script>
	                       alert('数据插入成功');
	                       location.href='".U('User/index')."';
			          	</script>";
        exit();
    }
    //excel数据导入结束
    /*会员上下级关系修正*/
    function usering(){
        $con['recommend']=array("neq","");
        $con['sour']=0;
           $info=M("user")->where($con)->select();

            foreach ($info as $k=>$v){
                     $rr=M("user")->where(array("username"=>$v['recommend']))->find();
                     $path=$rr['path'].",".$rr['id'];
                     $res=M("user")->where("id=".$v['id'])->save(array("path"=>$path,"fid"=>$rr['id']));
            }

            dump($res);
    }

}