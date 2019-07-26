<?php 
include 'user.php';
function workspaceInvite($emaillist,$wurl)
{  
    //print_r($emaillist);
    foreach($emaillist as $email){
       $user = getUserInfo(htmlspecialchars($_SESSION['id']));
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
        $emaillist = $emailist;
        workspaceInvite($emaillist,$wsurl);
    }
}

if (isset($_POST['wsinvite'])){
    if (!empty($_POST['wsinvite'])){
        $wsinvite = $_POST['wsinvite'];
        $wurl = htmlspecialchars($_GET['wurl']);
        workspaceInvite($wsinvite,$_GET['wurl']);
    }
}
if (isset($_POST['msg'])){
    if (!empty($_POST['msg'])){
        $chid=htmlspecialchars($_GET['cid']);
        $wurl=htmlspecialchars($_GET['wurl']);
        $msg = htmlspecialchars($_POST['msg']);
        $id = htmlspecialchars($_SESSION['id']);
        createMessage($chid,$wurl,$id,$msg);
    }
}

if (isset($_POST['leavews'])){
         $ws = htmlspecialchars($_POST['leavews']);
         deleteWorkspace($ws,$_SESSION['id']);
}

if (isset($_POST['perm'])){
    $wurl=htmlspecialchars($_GET['wurl']);
    $pname = "add_pub_channel";
    $pvalue = htmlspecialchars($_POST['add_pub_channel']);
    updatePermission($wurl,$pname,$pvalue);
    $pname = "add_pvt_channel";
    $pvalue = htmlspecialchars($_POST['add_pvt_channel']);
    updatePermission($wurl,$pname,$pvalue);
    $pname = "archive_channel";
    $pvalue = htmlspecialchars($_POST['archive_channel']);
    updatePermission($wurl,$pname,$pvalue);
    $pname = "remove_pub_member";
    $pvalue = htmlspecialchars($_POST['remove_pub_member']);
    updatePermission($wurl,$pname,$pvalue);
    $pname = "remove_pvt_member";
    $pvalue = htmlspecialchars($_POST['remove_pvt_member']);
    updatePermission($wurl,$pname,$pvalue);
    $pname = "send_invite";
    $pvalue = htmlspecialchars($_POST['send_invite']);
    updatePermission($wurl,$pname,$pvalue);
}


if (isset($_POST['wsinfo'])){
    $wurl=htmlspecialchars($_GET['wurl']);
    $wname = htmlspecialchars($_POST['wname']);
    $wpurpose = htmlspecialchars($_POST['wpurpose']);
    updateWorkspace($wurl,$wname,$wpurpose);
}