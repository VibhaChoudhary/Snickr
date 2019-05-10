<?php
include (dirname(__FILE__).'/controller/authorization.php');
include (dirname(__FILE__).'/controller/dbqueries.php');
if (empty($_SESSION['id'])) {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
            $link = "https"; 
        else
            $link = "http"; 
 
        $link .= "://"; 
        $link .= $_SERVER['HTTP_HOST'];   
        $link .= $_SERVER['REQUEST_URI']; 
        $_SESSION['redirect_url'] = $link; 
        //echo  $_SESSION['redirect_url'];
        
        header('location: /login.php');
        exit;
}
else{
    
    if(!empty($_GET['wsurl'])){
        $wurl=htmlspecialchars($_GET['wsurl']);
        $general=getGeneralChannelId($wurl);
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
            $newurl = "https"; 
        else
            $newurl = "http"; 
        $newurl .="://";
        $ws = $_SERVER['HTTP_HOST']."/workspace/".explode('@',$wurl)[0]."/CH".$general."/messages";
        $newurl.= $ws;
        if(isWsMember($wurl,$_SESSION['id']))
            header("Location: $newurl", true, 303);
        else{    
            $status = createWorkspaceMember($_SESSION['id'],$wurl);
            if($status!=-1){
              header("Location: $newurl", true, 303);
            }                
            else
                echo "some error occured";
        }    
    }
    
}
