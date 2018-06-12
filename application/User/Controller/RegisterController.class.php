<?php
namespace User\Controller;

use Common\Controller\HomebaseController;

class RegisterController extends HomebaseController {
	
    // 前台用户注册
	public function index(){
	    if(sp_is_user_login()){ //已经登录时直接跳到首页
	        redirect(__ROOT__."/");
	    }else{
	        $this->display(":register");
	    }
	}
	
	// 前台用户注册提交
	public function doregister(){
    	
    	if(isset($_POST['email'])){
    	    
    	    //邮箱注册
    	    $this->_do_email_register();
    	    
    	}elseif(isset($_POST['mobile'])){
    	    
    	    //手机号注册
    	    $this->_do_mobile_register();
    	    
    	}else{
    	    $this->error("注册方式不存在！");
    	}
    	
	}
	
	// 前台用户手机注册
	private function _do_mobile_register(){
	    
	    if(!sp_check_verify_code()){
	        $this->error("验证码错误！");
	    }
	     
        $rules = array(
            //array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
            array('mobile', 'require', '手机号不能为空！', 1 ),
            array('mobile','','手机号已被注册！！',0,'unique',3),
            array('password','require','密码不能为空！',1),
            array('password','5,20',"密码长度至少5位，最多20位！",1,'length',3),
        );
        	
	    $users_model=M("Users");
	     
	    if($users_model->validate($rules)->create()===false){
	        $this->error($users_model->getError());
	    }
	    
	    // if(!sp_check_mobile_verify_code()){
	    //     $this->error("手机验证码错误！");
     //    }
	     
	    $password=I('post.password');
	    $mobile=I('post.mobile');
	    
	    $users_model=M("Users");
	    $data=array(
	        'user_login' => '',
	        'user_email' => '',
	        'mobile' =>$mobile,
	        'user_nicename' =>'',
	        'user_pass' => sp_password($password),
	        'last_login_ip' => get_client_ip(0,true),
	        'create_time' => date("Y-m-d H:i:s"),
	        'last_login_time' => date("Y-m-d H:i:s"),
	        'user_status' => 1,
	        "user_type"=>2,//会员
	    );
	    
	    $result = $users_model->add($data);
	    if($result){
	        //注册成功页面跳转
	        $data['id']=$result;
	        session('user',$data);
	        $this->success("注册成功！",__ROOT__."/");
	         
	    }else{
	        $this->error("注册失败！",U("user/register/index"));
	    }
	}	
}