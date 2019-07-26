<?php
function haschannel($wurl,$cid){  
    global $conn;
    $query = "SELECT count(*) as c 
    FROM channel where cid =? and 
    wid =(select wid from workspace where wurl=?)";   
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $cid,$wurl);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res = $result->fetch_assoc(); 
        $stmt->close();
         if($res['c'] > 0) return 1; 
        else return 0;
    }
    else{
        $stmt->close();
        return -1;
    }     
}
function isAdmin($wurl,$id){  
    global $conn;
    $query = "SELECT count(*) as c FROM workspace_admin natural join workspace 
    where wurl=? and uid=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $wurl,$id);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res = $result->fetch_assoc(); 
        $stmt->close();
         if($res['c'] > 0) return 1; 
        else return 0;
    }
    else{
        $stmt->close();
        return -1;
    }     
}
function isPublicAllowed($wurl,$id){  
    global $conn;
    $query = "SELECT count(*) as c from workspace_permission NATURAL JOIN permission
    where pname = 'add_pub_channel' and wid = (select wid from workspace where wurl=?) and pallowed = 1 union SELECT count(*) as c FROM workspace_admin natural join workspace 
    where wurl=? and uid=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssi',$wurl,$wurl,$id);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res = $result->fetch_assoc(); 
        $stmt->close();
        return $res['c'] ; 
    }
    else{
        $stmt->close();
        return -1;
    }     
}
function isPrivateAllowed($wurl,$id){  
    global $conn;
    $query = "SELECT count(*) as c from workspace_permission NATURAL JOIN permission
    where pname = 'add_pvt_channel' and wid = (select wid from workspace where wurl=?) and pallowed = 1 union SELECT count(*) as c FROM workspace_admin natural join workspace 
    where wurl=? and uid=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssi',$wurl,$wurl,$id);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res = $result->fetch_assoc(); 
        $stmt->close();
        return $res['c'] ; 
    }
    else{
        $stmt->close();
        return -1;
    }     
}
function isWsMember($wurl,$id){  
    global $conn;
    $query = "SELECT count(*) as c FROM workspace_member natural join workspace 
    where wurl=? and uid=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $wurl,$id);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res = $result->fetch_assoc(); 
       // print_r($res);
        $stmt->close();
        return $res['c'] ; 
    }
    else{
        $stmt->close();
        return -1;
    }     
}

function isChMember($cid,$id){  
    global $conn;
    $query = "SELECT count(*) as c FROM channel_member where cid=? and uid=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $cid,$id);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res = $result->fetch_assoc(); 
        $stmt->close();
        //print_r(mysqli_error_list ($conn));
        // print_r($res);
        if($res['c'] > 0) return 1; 
        else return 0;
    }
    else{
        $stmt->close();
        return -1;
    }     
}

function isPublic($cid,$wurl){ 
    global $conn;
    $query = "SELECT cprivate as c FROM channel where cid=? and wid=(select wid from workspace where wurl=?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $cid,$wurl);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res = $result->fetch_assoc(); 
        $stmt->close();
        if($res['c']) return 0; 
        else return 1 ; 
    }
    else{
        $stmt->close();
        return -1;
    }     
}
function getPublicChannels($wurl){  
    global $conn;
    $query = "SELECT cid,cname FROM channel
    where wid=(select wid from workspace where wurl=?) and cprivate=0";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $wurl);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res_array = array();
        while ($row = $result->fetch_assoc()) {
           $res_array[] = $row;
        }
        $stmt->close();
        return $res_array ; 
    }
    else{
        $stmt->close();
        return -1;
    }     
}
function getWorkspaceMembers($wurl){  
    global $conn;
    $query = "SELECT u.uid,u.uname FROM workspace_member wm,user u 
    where wm.wid=(select wid from workspace where wurl=?) and u.uid=wm.uid ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $wurl);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res_array = array();
        while ($row = $result->fetch_assoc()) {
           $res_array[] = $row;
        }
        $stmt->close();
        return $res_array ; 
    }
    else{
        $stmt->close();
        return -1;
    }     
}
function getWorkspaceInfo($wurl){  
    global $conn;
    $query = "SELECT * FROM workspace where wurl=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $wurl);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res = $result->fetch_assoc(); 
        $stmt->close();
        return $res ; 
    }
    else{
        $stmt->close();
        return -1;
    }     
}
function getChannelMemberCount($cid){  
    global $conn;
    $query = "SELECT count(*) as c FROM channel_member where cid=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $cid);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res = $result->fetch_assoc(); 
        $stmt->close();
        return $res['c'] ; 
    }
    else{
        $stmt->close();
        return -1;
    }     
}
function getChannelInfo($cid){  
    global $conn;
    $query = "SELECT * FROM channel where cid=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $cid);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res = $result->fetch_assoc(); 
        $stmt->close();
        return $res ; 
    }
    else{
        $stmt->close();
        return -1;
    }     
}
function getAdmins($wurl){
    global $conn;
    $query = "select uid,uname 
    from user where 
    uid in (SELECT uid FROM workspace_admin natural join workspace where wurl=?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $wurl);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res_array = array();
        while ($row = $result->fetch_assoc()) {
           $res_array[] = $row;
        }
        $stmt->close();
      
        return $res_array ; 
    }
    else{
        $stmt->close();
        return -1;
    }       
}
function getUserInfo($uid){  
    global $conn;
    $query = "SELECT * FROM user where uid=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $uid);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res = $result->fetch_assoc(); 
        $stmt->close();
        return $res ; 
    }
    else{
        $stmt->close();
        return -1;
    }         
}
function getWorkspaces($uid){  
    global $conn;
    $query = "SELECT * FROM workspace_member natural join workspace where uid=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $uid);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res_array = array();
        while ($row = $result->fetch_assoc()) {
           $res_array[] = $row;
        }
        $stmt->close();
        return $res_array ; 
    }
    else{
        $stmt->close();
        return -1;
    }     
}
function getUserDisplayName($uid,$wurl){
    global $conn;
    $query = "SELECT case when prvalue = 0 and unickname is not null then unickname else uname end name
    FROM user_preferences natural join preferences natural join user natural join workspace
    where uid=? and wurl =? and prname='display_full_name'" ;
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is',$uid,$wurl);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res=$result->fetch_assoc();
        $stmt->close();
        return $res['name'];
    }
    else{
        $stmt->close();
        return -1;
    }     
}
function getGeneralChannelId($wurl){  
    global $conn;
    $query = "SELECT cid FROM channel where wid=(select wid from workspace where wurl=?) and cname='general'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $wurl);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res = $result->fetch_assoc(); 
        $stmt->close();
        return $res['cid'] ; 
    }
    else{
        $stmt->close();
        return -1;
    }     
}
function getChannels($wurl,$uid){
    global $conn;
    $query = "SELECT c.cid,c.cname,c.cprivate 
    FROM channel c,workspace w,channel_member cm
    where w.wurl=? and c.wid = w.wid and cm.uid =? and cm.cid = c.cid
    order by cprivate desc";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si',$wurl,$uid);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res_array = array();
        while ($row = $result->fetch_assoc()) {
           $res_array[] = $row;
        }
        $stmt->close();
      
        return $res_array ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}
function getDirectChannels($wurl,$uid){
    global $conn;
    $query = "SELECT touid as userid,uname\n"
    . "    FROM direct_message,workspace,user where fromuid=? and wurl=? and user.uid=touid and direct_message.wid = workspace.wid UNION SELECT fromuid as userid,uname FROM direct_message,workspace,user where touid=? and wurl=? and user.uid=fromuid and direct_message.wid = workspace.wid";
    $stmt = $conn->prepare($query);
    //print_r(mysqli_error_list ($conn ));
    $stmt->bind_param('isis',$uid,$wurl,$uid,$wurl);
    
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res_array = array();
        while ($row = $result->fetch_assoc()) {
           $res_array[] = $row;
        }
        $stmt->close();
        return $res_array ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}
function getFavourites($uid){
    global $conn;
    $query = "SELECT cid,cname,ccreator,cpurpose,cprivate,cdefault,c.create_ts 
    FROM channel c natural join channel_member cm
    where cm.uid=? and cm.cstarred = 1 order by c.cprivate desc;";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $uid);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res_array = array();
        while ($row = $result->fetch_assoc()) {
           $res_array[] = $row;
        }
        $stmt->close();
      
        return $res_array ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}

function getDefaultChannels($wurl){
    global $conn;
    $query = "SELECT cid,cname
    FROM channel 
    where channel.cdefault=1 and wid = (select wid from workspace where wurl=?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $wurl);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res_array = array();
        while ($row = $result->fetch_assoc()) {
           $res_array[] = $row;
        }
        $stmt->close();
      
        return $res_array ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}

function getPermissions($wurl){
    global $conn;
    $query = "SELECT * from permission natural join workspace_permission 
    where wid=(select wid from workspace where wurl=?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $wurl);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res_array = array();
        while ($row = $result->fetch_assoc()) {
           $res_array[] = $row;
        }
        $stmt->close();
      
        return $res_array ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}

function getMessages($cid,$wurl,$uid){
    if(substr($cid,0,2) == 'CH')
        return getChannelMessage(substr($cid,2));
    else
        return getDirectMessage(substr($cid,2),$wurl,$uid);
}
function getChannelMessage($cid){
    global $conn;
    $query = "SELECT mid,mcontent,uname as frm,
              DATE_FORMAT(message_ts,'%M %e %Y %T') as msg_ts
              FROM channel_message cm,user
              where cm.cid=? and cm.fromuid = user.uid order by date(msg_ts) asc;";
    $stmt = $conn->prepare($query);
    //print_r(mysqli_error_list ($conn ));
    $stmt->bind_param('s', $cid);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res_array = array();
        while ($row = $result->fetch_assoc()) {
           $res_array[] = $row;
        }
        $stmt->close();
      
        return $res_array ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}
function getDirectMessage($cid,$wurl,$uid){
    global $conn;
    $query = "select * from (SELECT dmid,dmcontent as mcontent,uname as frm,
    DATE_FORMAT(message_ts,'%M %e %Y %T') as msg_ts
    FROM direct_message dm,user u,workspace ws
    where dm.wid = ws.wid and ws.wurl=? and dm.touid=? and dm.fromuid=?
    and dm.fromuid = u.uid UNION
    SELECT dmid,dmcontent as mcontent,uname as frm, 
    DATE_FORMAT(message_ts,'%M %e %Y %T') as msg_ts
    FROM direct_message dm,user u,workspace ws
    where dm.wid = ws.wid and ws.wurl=? and dm.fromuid=? and dm.touid=?
    and dm.fromuid = u.uid) as combined
    order by date(msg_ts) asc";

    $stmt = $conn->prepare($query);
    //print_r(mysqli_error_list ($conn ));
    $stmt->bind_param('sissis',$wurl,$uid,$cid,$wurl,$uid,$cid);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res_array = array();
        while ($row = $result->fetch_assoc()) {
           $res_array[] = $row;
        }
        $stmt->close();
      
        return $res_array ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}


//Inserts
function createChannelMember($cid,$uid){
    //echo $cid;
    //echo $uid;
    global $conn;
    $query = "INSERT into channel_member
    SET cid = CAST(? AS UNSIGNED INTEGER),uid=?";
    $stmt = $conn->prepare($query);
   // print_r(mysqli_error_list ($conn));
    $stmt->bind_param('si',$cid,$uid);
    //print_r(mysqli_error_list ($conn));
    $result = $stmt->execute();
     //print_r(mysqli_error_list ($conn));
    if($result){
        echo "1";
        return 1 ; 
    }
    else{
        echo "0";
        $stmt->close();
        return -1;
    }   
}

function createWorkspaceMember($id,$wurl){
    echo $id;
    echo $wurl;
    global $conn;
    $query = "INSERT into workspace_member
    SET uid=?,wid=(SELECT wid from workspace where wurl=?)";
    $stmt = $conn->prepare($query);
    print_r(mysqli_error_list ($conn));
    $stmt->bind_param('is',$id,$wurl);
    print_r(mysqli_error_list ($conn));
    $result = $stmt->execute();
    //echo $result;
    if($result){
        return 1 ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}
function createMessage($cid,$wurl,$id,$msg){ 
    $chid = substr($cid,2);
    global $conn;
    if(substr($cid,0,2)=='CH') {
        $query = "INSERT into channel_message 
        SET cid= CAST(? AS UNSIGNED INTEGER), fromuid=?, mcontent=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sis',$chid,$id,$msg);
    } 
    if(substr($cid,0,2)=='DM') {
        $query = "INSERT into direct_message 
        SET wid=(SELECT wid from workspace where wurl=?), fromuid=?, touid=CAST(? AS UNSIGNED INTEGER), dmcontent=?";
        $stmt = $conn->prepare($query);
        //print_r(mysqli_error_list ($conn));
        $stmt->bind_param('siss',$wurl,$id,$chid,$msg);
    } 
    $result = $stmt->execute();
    if($result){
        return 1 ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}
function createChannel($wurl,$cname,$id,$cpurpose,$cprivate){ 
     global $conn;
    $query = "INSERT into channel
    SET wid=(SELECT wid from workspace where wurl=?),
    cname=?,ccreator=?,cpurpose=?,cprivate=?";
    $stmt = $conn->prepare($query);
    print_r(mysqli_error_list ($conn ));
    $stmt->bind_param('ssiss',$wurl,$cname,$id,$cpurpose,$cprivate);
    $result = $stmt->execute();
    $id = $stmt->insert_id;
    echo $id;
    if($result){
        return $id ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}
function createWorkspace($id,$wname,$wurl){
    echo $id;
    echo $wname;
    echo $wurl;
    global $conn;
    $query = "INSERT into workspace
    SET wcreator=?,wname=?,wurl=?";
    $stmt = $conn->prepare($query);
    print_r(mysqli_error_list ($conn));
    $stmt->bind_param('iss',$id,$wname,$wurl);
    print_r(mysqli_error_list ($conn));
    $result = $stmt->execute();
    //echo $result;
    if($result){
        return 1 ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}
function createWorkspaceInvite($id,$email,$wurl){
         global $conn;
    $query = "INSERT into workspace_invite
    SET wid=(SELECT wid from workspace where wurl=?),toemail=?,fromuid=?";
    $stmt = $conn->prepare($query);
    //echo "wsinvit";
   // print_r(mysqli_error_list ($conn));
    $stmt->bind_param('ssi',$wurl,$email,$id);
    $result = $stmt->execute();
    // print_r(mysqli_error_list ($conn));
    //echo $result;
    if($result){
        return 1 ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}
function createChannelInvite($cid,$id,$toid){ 
     global $conn;
    $query = "INSERT into channel_invite
    SET cid = CAST(? AS UNSIGNED INTEGER),touid=?,fromuid=?";
    $stmt = $conn->prepare($query);
    //print_r(mysqli_error_list ($conn));
    $stmt->bind_param('sii',$cid,$toid,$id);
    $result = $stmt->execute();
    //print_r(mysqli_error_list ($conn));
    //echo $result;
    if($result){
        return 1 ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}


//Delete
function deleteChannelMember($cid,$id){ 
     global $conn;
    $query = "DELETE FROM channel_member where cid=? and uid=?";
    $stmt = $conn->prepare($query);
    //print_r(mysqli_error_list ($conn));
    $stmt->bind_param('si',$cid,$id);
    $result = $stmt->execute();
    //print_r(mysqli_error_list ($conn));
    //echo $result;
    if($result){
        return 1 ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}

function deleteWorkspaceMember($wurl,$id){ 
     global $conn;
    $query = "DELETE FROM workspace_member where wid=(select wid from workspace where wurl=?) and uid=?";
    $stmt = $conn->prepare($query);
    //print_r(mysqli_error_list ($conn));
    $stmt->bind_param('si',$wurl,$id);
    $result = $stmt->execute();
    //print_r(mysqli_error_list ($conn));
    //echo $result;
    if($result){
        return 1 ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}


//search queries

function searchDirectMessages($key,$wurl,$id){
    global $conn;
    $query = "SELECT *  
    FROM direct_message,user 
    where direct_message.fromuid=user.uid and (fromuid =? or touid =?) and MATCH(dmcontent) AGAINST (?) and wid=(select wid from workspace where wurl=?) ";
    $stmt = $conn->prepare($query);
   // print_r(mysqli_error_list ($conn));
    $stmt->bind_param('iiss',$id,$id,$key,$wurl);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res_array = array();
        while ($row = $result->fetch_assoc()) {
           $res_array[] = $row;
        }
        $stmt->close();
      
        return $res_array ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}
function searchChannelMessages($key,$wurl,$id){
    global $conn;
    $query = "SELECT mid,cid,fromuid,(select uname from user where uid = fromuid) as uname,mcontent,message_ts  
    FROM channel_message natural join channel 
    where cprivate=0 and MATCH(mcontent) AGAINST (?) and wid=(select wid from workspace where wurl=?) union
    SELECT mid,cid,fromuid,(select uname from user where uid = fromuid) as uname,mcontent,message_ts  
    FROM channel_message natural join channel natural join channel_member
    where cprivate=1 and uid =? and MATCH(mcontent) AGAINST (?) and wid=(select wid from workspace where wurl=?
    )";
    $stmt = $conn->prepare($query);
   // print_r(mysqli_error_list ($conn));
    $stmt->bind_param('ssiss',$key,$wurl,$id,$key,$wurl);
    if($stmt->execute()){
        $result = $stmt->get_result();
        
        $res_array = array();
        while ($row = $result->fetch_assoc()) {
           $res_array[] = $row;
        }
        $stmt->close();
      
        return $res_array ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}

function searchMembers($key,$wurl,$id){
    global $conn;
    $param = "%".$key."%";
    $query = "SELECT *  
    FROM workspace_member wm,user where user.uid = wm.uid and uname like ? and wid=(select wid from workspace where wurl=?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss',$param,$wurl);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res_array = array();
        while ($row = $result->fetch_assoc()) {
           $res_array[] = $row;
        }
        $stmt->close();
      
        return $res_array ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}

function searchChannels($key,$wurl,$id){
    global $conn;
    $param = "%".$key."%";
    $query = "SELECT cid,cname,ccreator,cpurpose,cprivate,cdefault,create_ts  
    FROM channel where wid=(select wid from workspace where wurl=?) and cname like ? and cprivate=0 union
    SELECT cid,cname,ccreator,cpurpose,cprivate,cdefault,create_ts   
    FROM channel natural join channel_member where wid=(select wid from workspace where wurl=?) and cname like ? and cprivate=1 
    and uid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssi',$wurl,$param,$wurl,$param,$id);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res_array = array();
        while ($row = $result->fetch_assoc()) {
           $res_array[] = $row;
        }
        $stmt->close();
      
        return $res_array ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}

function updateUser($id,$uname,$nickname,$uphone,$ujob){
    global $conn;
    $query = "Update user set uname=?, unickname=?, uphone=?, ujob=? where uid = ?";
    $stmt = $conn->prepare($query);
    print_r(mysqli_error_list ($conn));
    $stmt->bind_param('ssssi',$uname,$nickname,$uphone,$ujob,$id);
    $result = $stmt->execute();
    print_r(mysqli_error_list ($conn));
    echo $result;
    if($result){
        return 1 ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}


function channelInvites($wurl,$uid){
    global $conn;
    $query = "SELECT chid,fromuid,cid,(select uname from user where fromuid=uid) as uname,cname,invite_ts
        FROM channel_invite NATURAL JOIN CHANNEL
        WHERE wid = (select wid from workspace where wurl=?) and touid=?  
        AND (cid, touid) NOT IN (SELECT cid,uid FROM channel_member) and attended=0 order by invite_ts desc";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $wurl,$uid);
    if($stmt->execute()){
        $result = $stmt->get_result();
        $res_array = array();
        while ($row = $result->fetch_assoc()) {
           $res_array[] = $row;
        }
        $stmt->close();
      
        return $res_array ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}

function ignoreChannelInvite($id){
    global $conn;
    $query = "Update channel_invite set attended=1 where chid = ?";
    $stmt = $conn->prepare($query);
    //print_r(mysqli_error_list ($conn));
    $stmt->bind_param('i',$id);
    $result = $stmt->execute();
   // print_r(mysqli_error_list ($conn));
    echo $result;
    if($result){
        return 1 ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}
function updatePermission($wurl,$pname,$pvalue){
    global $conn;
    $query = "Update workspace_permission set pallowed=? 
    where pid = (select pid from permission where pname=?) and wid =(select wid from workspace where wurl=?)";
    $stmt = $conn->prepare($query);
   // print_r(mysqli_error_list ($conn));
    $stmt->bind_param('iss',$pvalue,$pname,$wurl);
    $result = $stmt->execute();
    //print_r(mysqli_error_list ($conn));
   // echo $result;
    if($result){
        return 1 ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}


function updateWorkspace($wurl,$wname,$wpurpose){
    global $conn;
    $query = "Update workspace set wname=?,wpurpose=? where wurl=?";
    $stmt = $conn->prepare($query);
  // print_r(mysqli_error_list ($conn));
    $stmt->bind_param('sss',$wname,$wpurpose,$wurl);
    $result = $stmt->execute();
    //print_r(mysqli_error_list ($conn));
   // echo $result;
    if($result){
        return 1 ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}


function updateFav($cid,$uid){
    global $conn;
    $query = "Update channel_member set cstarred=1 where cid=? and uid=?";
    $stmt = $conn->prepare($query);
  // print_r(mysqli_error_list ($conn));
    $stmt->bind_param('ii',$cid,$uid);
    $result = $stmt->execute();
    //print_r(mysqli_error_list ($conn));
   // echo $result;
    if($result){
        return 1 ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}

function insertAdmin($wurl,$uid){
    global $conn;
    $query = "Insert into workspace_admin 
    SET wid=(select wid from workspace where wurl=?),uid=? ";
    $stmt = $conn->prepare($query);
  // print_r(mysqli_error_list ($conn));
    $stmt->bind_param('si',$wurl,$uid);
    $result = $stmt->execute();
    //print_r(mysqli_error_list ($conn));
   // echo $result;
    if($result){
        return 1 ; 
    }
    else{
        $stmt->close();
        return -1;
    }   
}
