<?php 
include 'workspace.php';

function channelInvite($cid,$wurl,$ulist)
{   print_r($ulist);
    foreach($ulist as $user){
       $uid = substr($user,2); 
       $from = getUserInfo(htmlspecialchars($_SESSION['id']));
       $to = getUserInfo($uid);
        if($user!=-1){ 
           $uname = $from['uname'];
           $email = $to['uemail'];
           $result = sendChannelInvitationEmail($uname,$email,$wurl,$cid);           
           if($result){
              createChannelInvite($cid,$from['uid'],$uid,);
           }
        }
    }    
}
if (isset($_POST['chname'])) {
    $cname = htmlspecialchars($_POST['chname']); 
    $cpurpose = htmlspecialchars($_POST['chpurpose']); 
    $ctype = htmlspecialchars($_POST['chtype']);
    $cmem = htmlspecialchars($_POST['chmem']);
    $wurl = htmlspecialchars($_GET['wurl']);
    $creator = htmlspecialchars($_SESSION['id']);
    $ccid = createChannel($wurl,$cname,$creator,$cpurpose,$ctype);
    if (!empty($cmem)){
        channelInvite($ccid,$wurl,$cmem);
    }        
}
if (isset($_POST['chinvite'])){
    if (!empty($_POST['chinvite'])){
        $chinvite = $_POST['chinvite']; 
        $ch = htmlspecialchars($_GET['cid']);
        $wurl = htmlspecialchars($_GET['wurl']);
        channelInvite(substr($ch,2),$wurl,$chinvite);
       
    }
}
if (isset($_POST['joincid'])){
    if (!empty($_POST['joincid'])){
        $chinvite = htmlspecialchars($_POST['joincid']); 
        createChannelMember($chinvite,$_SESSION['id']);
    }
}
if (isset($_POST['ignorei'])){
    $ch = htmlspecialchars($_POST['ignorei']); 
    ignoreChannelInvite($ch);    
}
if (isset($_POST['leavech'])){
    if (!empty($_POST['leavech'])){
        $leavecid = htmlspecialchars($_POST['leavech']); 
        deleteChannelMember($leavecid,$_SESSION['id']);
    }   
}
if (isset($_POST['addfav'])){
    if (!empty($_POST['addfav'])){
        $favcid = htmlspecialchars($_POST['addfav']); 
        updateFav($favcid,$_SESSION['id']);
    }   
}

if (isset($_POST['adminlist'])){
    if (!empty($_POST['adminlist'])){
        $adminlist = $_POST['adminlist']; 
        changeAdmins($adminlist);       
    }
}

function changeAdmins($adminlist){
    $wurl = htmlspecialchars($_GET['wurl']);
    foreach($adminlist as $user){
         $uid = substr($user,2); 
         insertAdmin($wurl,$uid);
    }
}