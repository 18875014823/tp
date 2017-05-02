<?php
namespace Home\Controller\User;
require_once 'Application/Home/TreeNode/TreeNode.class.php';
use Think\Controller;
use Think\Model;
use TreeNode\TreeNode;
use Think\Upload;
session_start();
class UserController extends Controller{
    private $userModel;
    private $customerModel;
    private $houseModel;
    private $sheduleModel;
    /**
     * 构造函数
     */
    public function __construct(){
        parent::__construct();
        $this->userModel = D("TbEmployee");//new Model("tb_employee");
        $this->customerModel = new Model("tb_customer");
        $this->houseModel = new Model("tb_house");
        $this->sheduleModel = new Model("tb_schedule");
    }
    /**
     * 登录
     */
    public function login($phone, $password){
        //查询数据
        $users = $this->userModel->where("phone='$phone'")->select();
        $_SESSION["eId"] = $users[0]["eid"];
        $host = $_SERVER["HTTP_HOST"];
        if(count($users) > 0){
            $u = $users[0];
            if($password == $u["pwd"]){
                $_SESSION["loginUser"] = $u["ename"];
                //查询用户菜单
                $menus = $this->userModel->table("tb_employeejob ej, tb_jobmenu jm, tb_menu m")
                ->field("m.*")
                ->where("ej.jId = jm.jId and jm.mId = m.mId and ej.eId =".$u["eid"])
                ->select();
                $_SESSION["menus"] = $menus;
                //密码正确
//                 header("location:http://localhost:8080/tp/Application/Home/View/welcome.php");
                $this->display("newWelcome");
            }else{
                //密码错误
                $_SESSION["loginError"] = "密码错误";
                header("location:http://localhost:8080/tp/login.php");
            }
        }else{
            $_SESSION["loginError"] = "用户名错误";
            header("location:http://localhost:8080/tp/login.php");
        }
    }
    
    /**
     * 客户列表
     */
    public function testTree(){
        $node1 = new TreeNode();
        $node1->id = -1;
        $node1->text = "我的客户";
        $eId = $_SESSION["eId"];
        $myCustomer = $this->customerModel->table("tb_customer c, tb_customeremployee ce")
        ->field("c.*")->where("c.cId = ce.cId and ce.eId =%d",$eId)->select();
        
        $children = array();
        foreach ($myCustomer as $customer){
            $node = new TreeNode();
            $node->id = $customer["cid"];
            $node->text = $customer["cname"];
            array_push($children, $node);
        }
        $node1->children = $children;
        echo "[".json_encode($node1)."]";
//         $this->ajaxReturn($node1);
    }
    
    
    /**
     * 客户详细信息
     */
    public function customerInformation($cId){
        $condition = array();
        
        if($cId != null && $cId != ""){
            $condition["cId"] = array("EQ",$cId);
        }
        $data = $this->customerModel->where($condition)->select();
//         echo "[".json_encode($data[0])."]";
        $this->ajaxReturn($data);
    }
    
    /**
     * 获取客户回访记录
     */
    public function loadRecord($pageNo = 1,$pageSize = 10,$eId){
        //当前客户的Id
        $cId = $_SESSION["cId"];
    
        $page = $this->customerModel->table("tb_record r1")->
        field("r1.rId, c1.cName, r1.rContent, r1.rThisTime, r1.rNextTime, r1.rPreTime,r1.cMoment")
        ->join("tb_customer c1 on r1.cId = c1.cId")->where("r1.cId = %d and r1.eId = %d",array($cId,$eId))->select();
        
//         $sql = "select r1.rId, c1.cName, r1.rContent, r1.rThisTime, r1.rNextTime, r1.rPreTime,
//                 r1.cMoment from tb_record r1 JOIN tb_customer c1 ON r1.cId = c1.cId
//                 where r1.cId = ? and r1.eId = ?";
        $this->ajaxReturn($page);
    }
    
    /**
     * 增加员工
     */
    public function addEmployee(){
        $eName = $_REQUEST["eName"];
        $eNo = $_REQUEST["eNo"];
        $pwd = $_REQUEST["pwd"];
        $eSex = $_REQUEST["eSex"];
        $eStates = $_REQUEST["eStates"];
        $eId = null;
        if(isset($_REQUEST["eId"])){
            $eId = $_REQUEST["eId"];
        }
        if($eSex == 1){
            $eSex = "女";
        }else{
            $eSex = "男";
        }
        $ePhone = $_REQUEST["ePhone"];
        
        header("Content-Type:text/html; charset=utf-8");
        $Dao = M("tb_employee");
        // 构建写入的数据数组
        $data["eNo"] = $eName;
        $data["eName"] = $eNo;
        $data["pwd"] = $pwd;
        $data["eSex"] = $eSex;
        $data["phone"] = $ePhone;
        $data["eStates"] = $eStates;
        
        // 写入数据
        $lastInsId = $Dao->add($data);
        if($lastInsId != null && $lastInsId != ""){
            echo "插入数据 id 为：$lastInsId";
        } else {
            $this->error('数据写入错误！');
        }
    }
    
    
    /**
     * 员工列表
     */
    public function loadEmployeeList($pageNo = 1,$pageSize = 10,$forStates=null,$forName=null,$forPhone=null){
        $condition = array();
        
        if($forStates != null && $forStates != ""){
            //$condition .= " and eStates = '$forStates'";
            $condition["eStates"] = array("EQ",$forStates);
        }
        if($forName != null && $forName != ""){
            //$condition .= " and eName like '$forName'";
            $condition["eName"] = array("like","%$forName%");
        }
        if($forPhone != null && $forPhone != ""){
            //$condition .= " and phone like '$forPhone'";
            $condition["phone"] = array("like","%$forPhone%");
        }
        
        $total = $this->userModel->where($condition)->count();
        
        //->fetchSql(true)
        $rows = $this->userModel->where($condition)->page($pageNo,$pageSize)->select();
        $page = array("total"=>$total, "rows"=>$rows, "pageNo"=>$pageNo, "pageSize"=>$pageSize);
        $this->ajaxReturn($page);
//         $page = $this->userModel->loadEmployeeListByPage($pageNo,$pageSize,$forName,$forPhone,$forStates);
//         echo json_encode($page);
    }
    
    /**
     * 职位列表
     */
    public function loadJobList($pageNo = 1,$pageSize = 10){
        $searchUserName = "";
        if(isset($_POST["searchUserName"])){
            $searchUserName = $_POST["searchUserName"];
        }
        $searchTrueName = "";
        if(isset($_POST["searchTrueName"])){
            $searchTrueName = $_POST["searchTrueName"];
        }
    
        $total = $this->userModel->table("tb_job")->count();
        
        //->fetchSql(true)
        $rows = $this->userModel->table("tb_job")->page($pageNo,$pageSize)->select();
        $page = array("total"=>$total, "rows"=>$rows, "pageNo"=>$pageNo, "pageSize"=>$pageSize);
        $this->ajaxReturn($page);
    }
    
    /**
     * 获取日程
     */
    public function loadSchedule($pageNo = 1,$pageSize = 10){
        $eId = $_SESSION["eId"];
        $date = "";
        if (isset($_REQUEST["date"])){
            $date = $_REQUEST["date"];
        }
//         $data = $this->userModel->loadSchedule($eId,$pageNo,$pageSize,$date);
//         echo json_encode($data);

        $total = $this->userModel->table("tb_schedule")->count();
        
        //->fetchSql(true)
        $rows = $this->userModel->table("tb_schedule")->page($pageNo,$pageSize)->select();
        $page = array("total"=>$total, "rows"=>$rows, "pageNo"=>$pageNo, "pageSize"=>$pageSize);
        $this->ajaxReturn($page);
    }
    
    
    /**
     * 获取楼盘信息
     */
    public function loadHouseList($pageNo = 1,$pageSize = 10){
//         $data = $this->customerModel->loadHouseList($pageNo,$pageSize);
//         echo json_encode($data);

        $total = $this->userModel->table("tb_house")->count();
        
        //->fetchSql(true)
        $rows = $this->userModel->table("tb_house")->page($pageNo,$pageSize)->select();
        $page = array("total"=>$total, "rows"=>$rows, "pageNo"=>$pageNo, "pageSize"=>$pageSize);
        $this->ajaxReturn($page);
    }
    
    
    /**
     * 获取投诉
     */
    public function loadComplainList($pageNo = 1,$pageSize = 10,$comDealResult = null){
        $condition = array();
        
        if($comDealResult != null && $comDealResult != ""){
            $condition["comDealResult"] = array("EQ",$comDealResult);
        }
        
        $eId = $_SESSION["eId"];
        $date = "";
        if (isset($_REQUEST["date"])){
            $date = $_REQUEST["date"];
        }
//         $data = $this->customerModel->loadComplainList($pageNo,$pageSize,$comDealResult);
//         echo json_encode($data);

        
        $total = $this->userModel->table("tb_complain c1, tb_customer c2, tb_employee e1")->where("c2.cId=c1.cId and e1.eId=c1.eId")
        ->where($condition)->count();
        //->fetchSql(true)
        $rows = $this->userModel->table("tb_complain c1, tb_customer c2, tb_employee e1")->page($pageNo,$pageSize)
        ->field("c1.comId,c1.comTheme,c1.comSort,c2.cName,c1.comDate,c1.comLevel,e1.eName,c1.comDealProcess,
            c1.comDealResult,c1.comCostTime,c1.comBack,c1.comSure,c1.comMore")->where("c2.cId=c1.cId and e1.eId=c1.eId")->where($condition)//->fetchSql(true)
        ->select();
//         echo $rows;
        $page = array("total"=>$total, "rows"=>$rows, "pageNo"=>$pageNo, "pageSize"=>$pageSize);
        $this->ajaxReturn($page);
        
//         $sql = "select c1.comId,c1.comTheme,c1.comSort,c2.cName,c1.comDate,c1.comLevel,e1.eName
//             ,c1.comDealProcess,c1.comDealResult,c1.comCostTime,c1.comBack,c1.comSure,c1.comMore from tb_complain c1,
//             tb_customer c2,tb_employee e1 where c2.cId = c1.cId and e1.eId = c1.eId ";
    }
    
    /**
     * 异步提交--添加或修改楼盘信息
     */
    public function addHouse($deal = null){
        $data = $this->houseModel->create();
        if($deal == 0){
            $result = $this->houseModel->field("hArea,hDevelop,hPrice,hSort,hMore")->add();
//             $sql = "insert into tb_house(hArea,hDevelop,hPrice,hSort,hMore) values(?,?,?,?,?)";
        }
        if($deal == 1){
            $result = $this->houseModel->field("hArea,hDevelop,hPrice,hSort,hMore")->where("hId=%d",$data["hId"])->save();
        }
        $this->ajaxReturn($result);
    }
    
    /**
     * 获取选中行的楼盘信息
     */
    public function loadPickHouse($hId = null){
        $data = $this->houseModel->find($hId);
        $this->ajaxReturn($data);
    }
    
    /**
     * 删除楼盘信息
     */
    public function deleteHouse($row = null){
        $data = 0;
        foreach ($row as $rows){
            $result = $this->houseModel->delete($rows["hid"]);
            $data += $result;
        }
        $this->ajaxReturn($data);
    }
    
    /**
     * 增加日程
     */
    public function addSchedule(){
        $eId = $_SESSION["eId"];
        $data = $this->sheduleModel->create();
        $result = $this->sheduleModel->field("eId,sCreatTime,sTargetTime,sContent")->add();
        $this->ajaxReturn($result);
    }
    
    /**
     * 删除日程
     */
    public function deleteSchedule($row = null){
        $data = 0;
        foreach ($row as $rows){
            $result = $this->sheduleModel->delete($rows["sid"]);
            $data += $result;
        }
        $this->ajaxReturn($data);
    }
    
    
    /**
     * 同步请求分页查询楼盘信息
     * @param number $pageNo
     * @param number $pageSize
     */
    public function loadHouseByPage($pageNo = 1,$pageSize = 2, $result=-1){
        $total = $this->userModel->table("tb_house")->count();
        
        //->fetchSql(true)
        $rows = $this->userModel->table("tb_house")->page($pageNo,$pageSize)->select();
        $page = array("total"=>$total, "rows"=>$rows, "pageNo"=>$pageNo, "pageSize"=>$pageSize);
//         $this->ajaxReturn($page);
        
//         $_SESSION["page"] = $page;
        $this->assign("page",$page);
        $this->assign("result",$result);
        $this->display("loadHouseByPage");
    }
    
    
    /**
     * 同步请求---增加或修改楼盘信息
     */
    public function addOrUpdateHouse(){
        $data = $this->houseModel->create();
        if($data["hId"] == -1){
            $result = $this->houseModel->field("hArea,hDevelop,hPrice,hSort,hMore")->add();
        }else{
            $result = $this->houseModel->field("hArea,hDevelop,hPrice,hSort,hMore")->where("hId=%d",$data["hId"])->save();
        }
        $this->loadHouseByPage(1,2,$result);
    }
    
    
    /**
     * 同步请求提交文件
     */
    public function testUpload(){
        $con = array(
            "maxSize"=>0,
            "rootPath"=>"./Public/upload/",
            "savePath"=>"",
            "saveName"=>round(0,1000.)."".time(),
            "exts"=>"jpg,png,gif"
        );
        $up = new Upload($con);
        $info = $up->uploadOne($_FILES["picture"]);
        if(!$info) {// 上传错误提示错误信息
            echo $this->error($up->getError());
        }else{// 上传成功 获取上传文件信息
            print_r($info);
        }
        
    }
    
    
    public function testUpload2(){
        $con = array(
            "maxSize"=>0,
            "rootPath"=>"./Public/upload/",
            "savePath"=>"",
            "saveName"=>round(0,1000.)."".time(),
            "exts"=>"jpg,png,gif"
        );
        $up = new Upload($con);
        $info = $up->uploadOne($_FILES["picture"]);
        if(!$info) {// 上传错误提示错误信息
            echo $this->error($up->getError());
        }else{// 上传成功 获取上传文件信息
            $this->ajaxReturn($info);
        }
    }
    
    
    
    
    
    
    

}

?>