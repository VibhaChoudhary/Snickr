<?php 
include 'workspace.php';

function channelInvite($cid,$wurl,$ulist)
{   print_r($ulist);
    foreach($ulist as $user){
       $uid = substr($user,2); 
       $from = getUserInfo($_SESSION['id']);
       $to = getUserInfo($uid);
        if($user!=-1){ 
           $uname = $from['uname'];
           $email = $to['uemail'];
           $result = sendChannelInvitationEmail($uname,$email,$wurl,$cid);
           
           if($result){
              createChannelInvite($cid,$_SESSION['id'],$uid,);
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
    $ccid = createChannel($wurl,$cname,$_SESSION['id'],$cpurpose,$ctype);
    if (!empty($cmem)){
        channelInvite($ccid,$wurl,$cmem);
    }        
}
if (isset($_POST['chinvite'])){
    if (!empty($_POST['chinvite'])){
        $chinvite = htmlspecialchars($_POST['chinvite']); 
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
if (isset($_POST['leavech'])){
    if (!empty($_POST['leavech'])){
        $leavecid = htmlspecialchars($_POST['joincid']); 
        deleteChannelMember($leavecid,$_SESSION['id']);
    }   
}