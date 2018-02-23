<?php
require 'config.php';
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->post('/login','login'); 
$app->post('/getUser','getUser');
$app->post('/signup','signup'); 
$app->post('/userShow','userShow'); 
$app->post('/update','update'); 
$app->post('/userDelete','userDelete'); 
$app->run();

//ฟังก์ชันล็อกอิน
function login() {
    
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    
    try {
        
        $db = getDB();
        $userData ='';

        $sql = "SELECT username,name,email FROM user WHERE username=:username and password=:password ";
        
        $stmt = $db->prepare($sql);
        $stmt->bindParam("username", $data->username, PDO::PARAM_STR);
        $password=hash('sha256',$data->password);
        $stmt->bindParam("password", $password, PDO::PARAM_STR);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_OBJ);
        
        if(!empty($userData))
        {
            $username=$userData->username;
        }
        
        $db = null;
         if($userData){
               $userData = json_encode($userData);
                echo '{"userData": ' .$userData . '}';
            } else {
               echo '{"error":{"text":"ชื่อผู้ใช้และรหัสผ่านไม่ถูกต้อง"}}';
            }         
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

//ค้นหาสมาชิก
function getUser() {
    
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;

    try {
        
        $userData = '';
        $db = getDB();

        $sql = "SELECT * FROM user WHERE user_id=:user_id";			
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_OBJ);
       
        $db = null;
 
        if($userData)
            echo '{"userData": ' . json_encode($userData) . '}';
            else
            echo '{"userData": "ไม่พบข้อมูล"}';
            
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

//แสดงสมาชิกทั้งหมด
function userShow() {
    
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    //$user_id=$data->user_id;

    try {
        
        $userData = '';
        $db = getDB();

		$sql = "SELECT * FROM user";			
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $userData = $stmt->fetchAll(PDO::FETCH_OBJ);
       
        $db = null;
 
        if($userData)
            echo '{"userData": ' . json_encode($userData) . '}';
            else
            echo '{"userData": ""}';
            
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

// เพิ่มข้อมูล
function signup() {
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $email=$data->email;
    $name=$data->name;
    $username=$data->username;
    $password=$data->password;
    
    try {

        $db = getDB();
          
        /*Inserting user values*/
        $sql="INSERT INTO user(username,password,email,name)VALUES(:username,:password,:email,:name)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("username", $username,PDO::PARAM_STR);
        $password=hash('sha256',$data->password);
        $stmt->bindParam("password", $password,PDO::PARAM_STR);
        $stmt->bindParam("email", $email,PDO::PARAM_STR);
        $stmt->bindParam("name", $name,PDO::PARAM_STR);
        $stmt->execute();
            
    $db = null;

    echo '{"success":{"text":"บันทึกข้อมูลสำเร็จ"}}';

    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

//แก้ไขข้อมูล
function update(){

    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;
    $name=$data->name;
    $email=$data->email;
   
    try {
        
        $feedData = '';
        $db = getDB();

        $sql = "UPDATE user SET name=:name,email=:email WHERE user_id=:user_id"; 
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt->bindParam("name", $name, PDO::PARAM_STR);
        $stmt->bindParam("email", $email, PDO::PARAM_STR);
        $stmt->execute();
       
        $db = null;
 
        echo '{"success":{"text":"แก้ไขข้อมูลสำเร็จ"}}';
            
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

//ลบข้อมูล
function userDelete(){
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id=$data->user_id;
   
    try {
  
        $db = getDB();
        $sql = "Delete FROM user WHERE user_id=:user_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
            
        $db = null;
        echo '{"success":{"text":"ลบข้อมูลสำเร็จ"}}';
       
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }   
}

?>
