<?php 
include 'authorization.php';
include 'dbqueries.php';

function workspaceInvite($emaillist,$wurl)
{  
    //print_r($emaillist);
    foreach($emaillist as $email){
       $user = getUserInfo($_SESSION['id']);
       if($user!=-1){ 
           $uname = $user['uname'];
          // echo $uname;
           $result = sendInvitationEmail($uname,$email,$wurl);
           if($result){
              createWorkspaceInvite($_SESSION['id'],$email,$wurl);
           }
       }
    }
}
if (isset($_POST['wsname'])) {
    
    $wname=htmlspecialchars($_POST['wsname']);    
    $prefix = str_replace(' ', '-', $wname);
    $wsurl = uniqid("$prefix");
    $wsurl .= "@snickr.com";
    createWorkspace($_SESSION['id'],$wname,$wsurl);
    if (!empty($_POST['emaillist'])){
        $emaillist = trim($_POST['emaillist']);
        $emaillist = explode(" ",$emaillist);
        $emaillist = htmlspecialchars($emailist);
        workspaceInvite($emaillist,$wsurl);
    }
}

if (isset($_POST['wsinvite'])){
    if (!empty($_POST['wsinvite'])){
        $wsinvite = htmlspecialchars($_POST['wsinvite']);
        $wurl = htmlspecialchars($_GET['wurl']);
        workspaceInvite($wsinvite,$_GET['wurl']);
    }
}
if (isset($_POST['msg'])){
    if (!empty($_POST['msg'])){
        //$chid=htmlspecialchars($_GET['chid']);
        //$wurl=htmlspecialchars($_GET['wurl']);
        createMessage($_GET['cid'],$_GET['wurl'],$_SESSION['id'],$_POST['msg']);
    }
}

if (isset($_POST['leavews'])){
         $ws = htmlspecialchars($_POST['leavews']);
         deleteWorkspace($ws,$_SESSION['id']);
}