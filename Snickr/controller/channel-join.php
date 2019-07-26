<?php
include 'authorization.php';
include 'dbqueries.php';
if (empty($_SESSION['id'])) {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
            $link = "https"; 
        else
            $link = "http"; 
 
        $link .= "://"; 
        $link .= $_SERVER['HTTP_HOST'];   
        $link .= $_SERVER['REQUEST_URI']; 
        $_SESSION['redirect_url'] = $link; 
        header('location: /login.php');
        exit;
}

    
if(!empty($_GET['wsurl'])){
    $wurl=htmlspecialchars($_GET['wsurl']);
    if(isWsMember($wurl,$_SESSION['id'])){
        if(!empty($_GET['chid'])){
            $cid=htmlspecialchars($_GET['cid']);
            $status = createChannelMember(substr($cid,2),$_SESSION['id']);
             if($status!=-1){
                if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
                    $newurl = "https"; 
                else
                    $newurl = "http"; 
                $newurl .="://";
                $ws = $_SERVER['HTTP_HOST']."/workspace/".explode('@',$wurl[0])."/CH".$cid."/messages";
                $newurl.= $ws;
                header("Location: $newurl", true, 303);
             }
             else
                echo "some error occured";
        }
        else{
            header('location: /index.php');   
        }
    }
   
    else{    
         header('location: /index.php');       
    }    

}

