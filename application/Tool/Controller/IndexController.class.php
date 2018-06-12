<?php
namespace Tool\Controller;
use Common\Controller\HomebaseController;

class IndexController extends HomebaseController{
	
	public function index(){
		$this->display(":index");
	}

    //bmi
    public function bmi(){
        $this->display(":bmi");
    }

    public function brm(){
        $this->display(":brm");
    }

    public function tizhong(){

        $this->display(":tizhong");
    }
    public function water(){
        
        $this->display(":water");
    }


        //会员动态列表
    public  function dynamiclist(){
        $where['status']=array(array('eq',1),array('exp','IS NULL'),'OR');
            # code...
            # 
        $model_dynamic=M('dynamic');
        $count=$model_dynamic->alias("a")
        ->join("__USERS__ c ON a.userid = c.id")->where($where)->count();
            
        $page = $this->page($count, 20);
            
        $res=$model_dynamic->alias("a")
        ->join("__USERS__ c ON a.userid = c.id")->where($where)->limit($page->firstRow , $page->listRows)->order("did desc")->select();


        $hot=$model_dynamic->alias("a")
        ->join("__USERS__ c ON a.userid = c.id")->where($where)->limit("0,6")->order("did desc")->select();

        $this->assign("hot",$hot);
        // dump($res);
        $this->assign("page", $page->show('Admin'));
        $this->assign("res",$res);
        $this->display(":dynamiclist");
    }
        //会员动态列表详情
    public  function dynamicdetails(){
        $did=I('get.did');
        $model_dynamic=M('dynamic');
        $res=$model_dynamic->alias("a")
        ->join("__USERS__ c ON a.userid = c.id")->where("did=$did")->find();
        $this->assign("res",$res);
        $this->display(":dynamicdetails");
    }
}