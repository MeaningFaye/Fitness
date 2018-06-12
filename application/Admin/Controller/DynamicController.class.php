<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class DynamicController extends AdminbaseController{

	protected $users_model,$role_model;

	public function _initialize() {
		parent::_initialize();

	}

	// 管理员列表
	public function index(){

        // dump(I('request.'));
        $where['status']=array(array('eq',1),array('exp','IS NULL'),'OR');

        $user_login=I('request.user_login');
        if(!empty($user_login)){
            $where['user_login']=array('like',"%$user_login%");
        }

        $title=I('request.title');
        if(!empty($title)){
            $where['title']=array('like',"%$title%");
        }
            # code...
            # 
        $model_dynamic=M('dynamic');
        $count=$model_dynamic->alias("a")
        ->join("__USERS__ c ON a.userid = c.id")->where($where)->count();
            
        $page = $this->page($count, 20);
            
        $res=$model_dynamic->alias("a")
        ->join("__USERS__ c ON a.userid = c.id")->where($where)->limit($page->firstRow , $page->listRows)->select();

        // dump($res);
        $this->assign("page", $page->show('Admin'));
        $this->assign("res",$res);
        // dump($res);die;
     
		$this->display();
	}

	 public  function watch(){
        $did=I('get.did');
        $model_dynamic=M('dynamic');
        $res=$model_dynamic->alias("a")
        ->join("__USERS__ c ON a.userid = c.id")
        ->where("did=$did")
        ->find();
        $this->assign("res",$res);
        $this->display();
    }

	// 管理员删除
	public function delete(){
	    $did = I('get.did',0,'intval');
		$res=M('dynamic')->where("did=$did")->delete();
		if($res){
			$this->success("删除成功");
		}else{
			$this->error("删除失败");
		}

	}



}