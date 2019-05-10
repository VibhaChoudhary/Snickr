<?php 
    //echo $_SERVER['SERVER_ADDR'];
    include '../../controller/channel.php';
?>
<?php
    // redirect user to login page if they're not logged in
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
    parse_str($_SERVER["QUERY_STRING"], $query_array);
    if (!isset($query_array['wurl'])) {
        header('location: /index.php');
    }
    else if(!isset($query_array['cid'])){
        
        if(!isWsMember($_GET['wurl'],$_SESSION['id']))
            header('location: /view/access_denied.php');
        else
            header('location: /index.php');
    }   
    else{
        if(substr($_GET['cid'],0,2)=='CH'){ 
           
            if(!haschannel($_GET['wurl'],substr($_GET['cid'],2))){
                header('location: /view/access_denied.php');
            }
            else if(!isChMember(substr($_GET['cid'],2),$_SESSION['id']) && !isPublic(substr($_GET['cid'],2),$_GET['wurl'])){
                header('location: /view/access_denied.php');
            }
        }
        if(substr($_GET['cid'],0,2)=='DM'){ 
            if(!isWsMember($_GET['wurl'],substr($_GET['cid'],2))){
                header('location: /view/access_denied.php');
            }
        }     
    } 
    $channels = getChannels($_GET['wurl'],$_SESSION['id']);
    $workspace = getWorkspaceInfo($_GET['wurl']);
    $admins = getAdmins($_GET['wurl']);
    $defaults = getDefaultChannels($_GET['wurl']);    
    $favs = getFavourites($_SESSION['id']);
    $direct = getDirectChannels($_GET['wurl'],$_SESSION['id']);
     
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Snickr Team Collaboration tool">
    <title>Welcome to Snickr</title>

    <!-- Font awesome CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" >
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.10/css/bootstrap-select.min.css">
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="/css/home.css" />
    <link rel="stylesheet" href="/css/emoji.css" />
    <link rel="stylesheet" href="/css/main.css">

  </head>

  <body>
   
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
        <div class="navbar-toggler col-md-2">
            <span style="float:left" onclick="handleNav()" class="navbar-toggler-icon" ></span>
            <span  style="float:left; padding:.25rem;">
            <?php $ws=$query_array['wurl'];
                  $res=getWorkspaceInfo($ws);
                  echo $res['wname'];?>
            </span>
        </div>     
        <div class="col-md-3 btn-group">
             <input id="search-key" class="form-control" type="text" style="padding:1px" 
                placeholder="Search" aria-label="Search">  
                
             <a id="search-snickr" class="navbar-toggler" href="#" >
             <span> <i class="fas fa-search" aria-hidden="true"></i></span></a> 
        </div>
    </nav>

    <div id="container" class="container-fluid" style="overflow-y:hidden" >  
        <div class="row">
            
            <!-- hidden div start -->
            <div id ="settings" >
                <div class="col-md-6 offset-md-3 home-wrapper">
              <button id="admin-esc" type="button" class="btn pull-right mb-4"><i class="fas fa-close" ></i>
                </button>
              <h4>Administration</h4>
              <ul class="nav nav-tabs">
                <li ><a data-toggle="tab" class="nav-link active" href="#sts">Settings</a></li>
                <li><a data-toggle="tab" class="nav-link " href="#pms">Permissions</a></li>                
              </ul>

            <div class="tab-content">
                <div id="sts" class="col md-4 tab-pane fade show active">
                    </br>
                    <h6>Workspace name</h6>
                    <div class="input-group mt-2">                             
                         <label id="ws-name"> <?php echo $workspace['wname'];?></label>
                         <button class="ml-2">Edit</button>
                    </div>
                    </br>
                    <h6>Workspace pupose</h6>
                    <div class="input-group mt-2">                        
                         <label id="ws-purpose"> <?php echo $workspace['wpurpose'];?></label>
                         <button class="ml-2">Edit</button>
                    </div>
                    </br>
                    <h6>Workspace admins</h6>
                        <?php foreach($admins as $admin){?>
                        <div class="input-group mt-2">  
                        <label id="admin-name"><?php echo $admin['uname'];?></label>
                        <?php if($admin['uid']!=$_SESSION['id']): ?>
                        <button id="remove-admin" class="ml-2">Remove</button>
                        <?php else: ?>
                        <label class="ml-2">(Creator of the workspace)</label>
                        <?php endif; ?></br></div><?php }?>
                        <a id="add-admin" href="#">+Add more admin</a>
                    </br>  
                    </br>                        
                    <h6>Default Channels</h6>                        
                        <?php foreach($defaults as $default){?>
                        <div class="input-group mt-2">  
                        <label id="<?php echo $default['cid'];?>"><?php echo $default['cname'];?></label>
                        <?php if($default['cname']!=strtolower("General")): ?>
                        <button class="ml-2">Remove</button>
                        <?php else: ?>
                        <label class="ml-2">(Primary channel can not be removed)</label>
                        <?php endif; ?></br></div><?php }?>
                        <a href="#">+Add more defaults</a>
                    </br></br>
                    <label class="alert alert-danger"> This will delete this workspace </label>
                    <h6>Delete Workspace</h6> 
                    <button id="delete-ws" class="ml-2">Delete</button>
                </div>
                <div id="pms" class="tab-pane fade ">                 
                    </br>
                    <?php 
                    $permissions = getPermissions($_GET['wurl']);
                    foreach($permissions as $permission){
                        $a=$permission['default_allowed'];
                        $pid=$permission['pid'];?>
                        <div >
                            <label class="h6 ml-2"><?php echo $permission['pdescp'];?></label></br>
                            <div class="p-0 btn-group" id="add-pub-channel" data-toggle="buttons">
                                <label class=" btn btn-default">
                                <input type="radio" id="<?php echo $pid?>" name="<?php echo $pid?>" class="toggle" 
                                <?php if($a==0):?>checked<?php endif;?>>Admin only
                                </label>  
                                <label class=" btn btn-default ">
                                <input type="radio" id="<?php echo $pid?>" name="<?php echo $pid?>" class="toggle"
                                <?php if($a==1):?>checked<?php endif;?>>All Members
                                </label>
                            </div>
                        </div><?php
                    }?>
                    <button class="ml-2">Save Changes</button>
                </div>
            </div>
        </div>           
            </div>    
            <div id="add-ch">           
                <div class="col-md-6 offset-md-3 home-wrapper" >
            <button id="ch-esc" type="button" class="btn pull-right mb-4"><i class="fas fa-close" ></i>
            </button>
              <h4>Create Channel</h4>
                <?php 
                $puballowed = isPublicAllowed($_GET['wurl'],$_SESSION['id']); 
                $pvtallowed = isPrivateAllowed($_GET['wurl'],$_SESSION['id']);
               if($puballowed || $pvtallowed): ?>
                <div class="btn-group" id="ctype" data-toggle="buttons">
                    <?php if($puballowed): ?>
                    <label class=" btn btn-default">
                       <input type="radio" name="chtype" id="public" class="toggle" value="0" checked> Public
                    </label>  
                    <?php endif; ?> 
                    <?php if($pvtallowed): ?>
                    <label class=" btn btn-default ">
                        <input type="radio" name="chtype" id="private" value="1" class="toggle">Private
                    </label>
                    <?php endif; ?> 
                </div>
                
              <div class="form-group mt-2">
                <label>Channel Name</label>
                 <input id="ch-name" type="text" class="form-control mr-2" >
                 <div id="ch-name-invalid" style="display:none;" class="alert alert-danger">Channel name required</div>
              </div>
              <div class="form-group">
                <label>Channel Purpose</label>
                 <input id="ch-purpose" type="text" class="form-control mr-2" >
                 <div id="ch-purpose-invalid" style="display:none;" class="alert alert-danger">Channel purpose required</div>
              </div>                  
              <div class="form-group"> 
                  <label>Add people(optional)</label>
                  <div class="form-control form-control-lg ">
                    <span class="to-input"></span>
                    <div id="mem-list" class="all-members">                         
                    </div>
                    <input autocomplete="off" id="mem" class="form-control mr-2" type="text" placeholder="select members" aria-label="Search">
                   </div>
                  
              </div>
              <div id="wsmember-list"  class="mt-1 mb-2 ml-4 mr-4">      
                    <?php 
                    $all_members=getWorkspaceMembers($query_array['wurl']);
                    if($all_members == -1) echo "";
                    else{
                        foreach($all_members as $member){
                            echo '<div style="cursor:pointer" class="border-top p-2 " id="DM'.$member['uid'].'">';
                            echo $member['uname'];
                            echo '</div>' ;                                 
                        }
                    }?>
               </div>  
                <button id="create-ch-btn" class="btn btn-secondary" name="create-btn" class="btn btn-lg btn-block">Create</button>
                <?php else:?> 
                <p>Channel creation is not allowed in this workspace</p>
                <?php endif;?> 
            </div>
            </div>
            <div id="browse-channels">
                <div class="col-md-6 offset-md-3 home-wrapper" >
                <button id="bc-esc" type="button" class="btn pull-right mb-4"><i class="fas fa-close" ></i>
                </button>
                <h4 class="p-2 mt-4">Search Channels</h4>
                <div class="input-group mt-3 p-2"> 
                    <input autocomplete="off" id="search-ch" class="form-control mr-2" type="text" placeholder="Search channel to join" aria-label="Search">
                    <a id="join_channel" class="btn border border-primary">Join</a> 
                </div>
                <label class="text-muted p-2">All Public Channels:</label>
                <div id="channel-list" class="mt-1 mb-2 ml-4 mr-4">                        
                    <?php 
                    $all_channels=getPublicChannels($_GET['wurl']);
                    if($all_channels == -1) echo "";
                    else{
                        foreach($all_channels as $channel){
                            echo '<div style="cursor:pointer" class="border-top p-2 " id="CH'.$channel['cid'].'">';
                            echo $channel['cname'];
                            echo '</div>' ;                                 
                        }
                    }?>
                </div>                    
            </div>    
            </div>
            <div id="browse-members">
                <div class="col-md-6 offset-md-3 home-wrapper" >
                <button id="dm-esc" type="button" class="btn pull-right mb-4"><i class="fas fa-close" ></i>
                </button>
                <h4 class="p-2 mt-4">Direct Messages</h4>
                <div class="input-group mt-3 p-2"> 
                    <input autocomplete="off" id="search-mem" class="form-control mr-2" type="text" placeholder="Add member to start a conversation" aria-label="Search">
                    <a id="add_member" class="btn border border-primary">Go</a> 
                </div>
                <label class="text-muted p-2">Workspace Members:</label>
                <div id="member-list" class="mt-1 mb-2 ml-4 mr-4">                        
                    <?php 
                    $all_members=getWorkspaceMembers($query_array['wurl']);
                    if($all_members == -1) echo "";
                    else{
                        foreach($all_members as $member){
                            echo '<div style="cursor:pointer" class="border-top p-2 " id="DM'.$member['uid'].'">';
                            echo $member['uname'];
                            echo '</div>' ;                                 
                        }
                    }?>
                </div>                    
            </div>    
            </div>
            <div id="workspace-invite">
                <div class="col-md-6 offset-md-3 home-wrapper" >
              <button id="wi-esc" type="button" class="btn pull-right mb-4"><i class="fas fa-close" ></i></button>
              <h4>Invite People to the workspace</h4>  
              <div class="mt-3 p-2">
                <div class="form-control form-control-lg ">
                    <span class="to-input"></span>
                    <div id="invite-email-list" class="all-mail">                         
                    </div>
                    <input  type="text" name="email" class="enter-email-id " placeholder="Enter the email ids .." >                    
                </div>
                <div id="ws-email-invalid" style="display:none;" class="alert alert-danger">Email not valid</div>
                <div class="mt-1 p-2"><a id="invite-mem" class="btn border border-primary">Invite</a> </div>
               </div>
            </div>
            </div>
            <div id="channel-invite">
                <div class="col-md-6 offset-md-3 home-wrapper" >
              <button id="ci-esc" type="button" class="btn pull-right mb-4"><i class="fas fa-close" ></i></button>
              <h4>Invite People to the channel</h4>  
                <div class="form-control form-control-lg ">
                    <span class="to-input"></span>
                    <div id="ch-mem-list" class="all-members">                        
                    </div>
                    <input autocomplete="off" id="ch-mem" class="form-control mr-2" type="text" placeholder="select members" aria-label="Search">
                </div>
                <div id="ch-member-list"  class="mt-1 mb-2 ml-4 mr-4">      
                    <?php 
                    $all_members=getWorkspaceMembers($query_array['wurl']);
                    if($all_members == -1) echo "";
                    else{
                        foreach($all_members as $member){
                            echo '<div style="cursor:pointer" class="border-top p-2 " id="DM'.$member['uid'].'">';
                            echo $member['uname'];
                            echo '</div>' ;                                 
                        }
                    }?>
                </div>  
                <div class="mt-1 p-2">
                    <a id="invite-ch-mem" class="btn border border-primary">Invite</a> 
                </div>
            </div>
            </div>
            <div id="mySidenav" class="col-md-2 flex-md-nowrap p-0 text-muted sidenav">
                <nav class="nav flex-column">
                  <a id="pref" class="nav-link active" href="#">Preferences</a>
                  <a id="invite-a" class="nav-link" href="#">Invite people</a>
                  <?php $admin_status = isAdmin($query_array['wurl'],$_SESSION['id']);
                  if($admin_status):?>
                  <a id="admin" class="nav-link disabled" href="#">Administration</a>
                  <?php endif; ?>
                  <a class="nav-link" href="http://<?php echo $_SERVER['SERVER_NAME']?>">Switch Workspace</a>
                  <a class="nav-link" href="http://<?php echo $_SERVER['SERVER_NAME']?>/logout.php">Logout</a>
            </nav>
            </div>  
            <!--hidden div end -->
            <div id = "sidemenu" class="col-md-2 ml-sm-auto col-lg-2 flex-md-nowrap p-0 d-md-block bg-light sidebar ">
                <div class="sidebar-sticky">
                <div class="user-list ">
                    <img class="d-flex rounded-circle avatar z-depth-1-half mr-3" src="/img/u1.png" alt="user-profile"> 
                    <h6>
                    <?php
                        $res = getUserDisplayName($_SESSION['id'],$query_array['wurl']);
                        echo $res;?>
                    </h6>                        
                    <div class = "dropdown-submenu ">
                    <select id="status" class="selectpicker">                   
                        <option data-content="<i class='fa fa-circle mr-2 small green'></i>Available"></option>
                        <option data-content="<i class='fa fa-circle mr-2 small red'></i>Busy"></option>
                        <option data-content="<i class='fa fa-circle mr-2 small yellow'></i> Away"></option>
                        <option data-content="<i class='fas fa-umbrella-beach mr-1 red'></i> Vacationing"></option>
                        <option data-content="<i class='far fa-sad-tear mr-2 blue'></i> Out Sick"></option>
                    </select>
                    </div>    
                </div>
                <div id="fav">
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span class="text-uppercase">Favourites</span>                            
                    </h6>
                    <div id= "fav-c">
                        <ul class="nav flex-column mb-2">
                            <?php 
                            if($favs == -1) echo "";
                            else{
                                foreach($favs as $fc){
                                    echo '<li class="nav-item">';
                                    echo '<a id="CH'.$fc['cid'].'"style="cursor:pointer" class="nav-link">';
                                    echo '<i style="padding-right:0.5rem" class=';
                                    if($fc['cprivate']==1) echo '"fa fa-lock"></i>';
                                    else echo '"fa fa-unlock"></i>';
                                    echo $fc['cname'];
                                    echo '</a></li>' ;                                 
                                }
                            }?>
                        </ul>
                    </div>
                </div>
                <div id="channels">
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                      <span class="text-uppercase">Channels</span>
                           <a style="cursor:pointer" id ="ch-setting" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           <span  data-feather="more-vertical"></span></a>                 
                        <div style="font-size:0.8rem;width:auto" class="bg-light text-dark dropdown-menu" aria-labelledby="ch-setting">
                            <a id ="ch-setting-add" style="cursor:pointer" class="dropdown-item" ><span class="mr-1" data-feather="plus-circle"></span>Create channel</a>
                            <a id ="ch-setting-brws" style="cursor:pointer" class="dropdown-item" ><span class="mr-1" data-feather="search"></span>Browse channel</a>
                         </div>
                    </h6>
                    <div id= "fav-c" >
                        <ul class="nav flex-column mb-2">
                        <?php 
                        if($channels == -1) echo "";
                        else{
                            foreach($channels as $ch){
                                echo '<li class="nav-item">
                                <a id="CH'.$ch['cid'].'" style="cursor:pointer" class="nav-link" >';
                                echo '<i class=';
                                if($ch['cprivate']==1)echo '"fa fa-lock"></i>';
                                else echo '"fa fa-unlock"></i>';
                                echo $ch['cname'];
                                echo '</a></li>' ;                                 
                            }
                        }?>
                        </ul>  
                     </div>
                </div>
                <div id="direct-msgs">
                      <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted ">
                      <span class="text-uppercase">Direct Message</span>
                        <a id="direct-msg" style="cursor:pointer" class="d-flex align-items-center text-muted">
                        <span data-feather="plus-circle"></span>
                        </a>
                        </h6>
                    <div id="fav-c">
                    <ul id="direct" class="nav flex-column mb-2">
                    <?php
                    if($direct == -1) echo "";
                    else{
                        foreach($direct as $dc){
                            echo '<li class="nav-item">';
                            echo '<a id="DM'.$dc['userid'].'" style="cursor:pointer" class="nav-link"> ';
                            echo '<i style="padding-right:0.5rem" class="fa fa-user"></i>';
                            echo $dc['uname'];
                            echo '</a></li>' ;                                 
                        }
                    }
                    ?>
                    </ul></div>
                </div>                
            </div>
            </div>
            <main  role="main" class="col-md-10 ml-sm-auto col-lg-10 pt-3 px-4" style="height:calc(100vh - 40px)" >
                <div class="d-flex justify-content-between align-items-center pb-2 border-bottom">
            <div id="channel-info" class="input-group" >
                    <div>
                        <h5 id="cname">
                        <?php 
                        $chid=$query_array['cid'];
                        if(substr($chid,0,2)=='CH') {
                            $channel=getChannelInfo(substr($chid,2));
                            echo $channel['cname'];
                        }
                        else{
                            $user = getUserInfo(substr($chid,2));
                            echo $user['uname'];
                        } ?>
                        </h5>
                        <?php
                        if(substr($chid,0,2)=='CH'){
                            echo '<h6 id="mem-count" style="font-size:.8rem;" class="card-subtitle mb-2 text-muted"><i style="padding-right:0.5rem" class="fa fa-users"></i>';
                            $cm_count=getChannelMemberCount(substr($chid,2));
                            echo "$cm_count members";
                            echo '</h6>';
                        }
                        ?>
                     
                    </div>
                     <?php if(substr($_GET['cid'],0,2)=='CH' && !isChMember(substr($_GET['cid'],2),$_SESSION['id'])  && isPublic(substr($_GET['cid'],2),$_GET['wurl'])): ?>
                        <div id="join-btn" class="ml-4">
                         
                          <button id="join-ch" class="btn btn-sm btn-outline-secondary">
                            Join
                          </button></div>
                          <?php endif;?>
                </div>
                
              
                <div id="settings-menu" class="dropdown-submenu pull-left">                          
                      <button id="dropdownSettings" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-gear"></i>
                        Settings
                      </button>
                      <div class="dropdown-menu" aria-labelledby ="dropdownSettings">
                        <a id="leave-ch-drp" class="dropdown-item" style="cursor:pointer">Leave channel</a>
                        <a id="invite-ch-drp" class="dropdown-item" style="cursor:pointer">Invite people</a>
                        <a id="fav-ch-drp" class="dropdown-item" style="cursor:pointer">Add to Favourites</a>
                        <a id="detail-ch-drp" class="dropdown-item" style="cursor:pointer">Channel details</a>
                        
                      </div>
                </div>
                
            </div>  
                <div id ="chat-div" style="overflow-y:auto;height:calc(100vh - 225px)">
            <div id = "ch_msg" class="chat-container mt-3" >                
                <?php
                    $messages = getMessages($_GET['cid'],$_GET['wurl'],$_SESSION['id']);
                    
                    if($messages!=-1){ 
                    
                        if(count($messages)==0){
                            echo "<p>There are no posts in this channel. Start collaborating by posting new messages</p>";                    
                        }
                        else{
                           foreach($messages as $msg){
                           echo '<div class="chats-text-cont">';
                           echo '<div class ="user-list">';
                           echo '<img class="d-flex rounded-circle avatar z-depth-1-half mr-3" src="/img/u1.png" alt="user-profile">';
                           echo '<a href="#"><span class="h6">';
                           if($msg['frm']==$_SESSION['id'])echo "You"; else echo $msg['frm'];
                           echo '</span></a><span class="small px-3">'.$msg['message_ts'].'</span></a>';
                           echo '<p>'.$msg['mcontent'].'</p>'; 
                           echo '</div></div> ';
                            } 
                        }
                        
                    }                       
                ?>                    
            </div> 
            </div>
                <div id="msg-div" style="margin-top:20px">                    
                <div class="input-group my-group" style="border-color: black;"> 
                    <div style="width:calc(100% - 80px);margin-right:10px" class="lead emoji-picker-container">
                    <input id="msg" type="email"  class="form-control" placeholder="Type message" data-emoji-input="unicode" data-emojiable="true">
                    </div>
                    <a id="send_msg" name="send-msg" class="btn btn-secondary" >Send</a> 
                </div>  
            </div>   
            </main>
        </div>        
    </div>
               
    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="/assets/js/vendor/popper.min.js" ></script>
    <script src="/dist/js/bootstrap.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.10/js/bootstrap-select.min.js"></script>
    <!-- Emoji picker JavaScript -->
    <script src="/js/config.js"></script>
    <script src="/js/util.js"></script>
    <script src="/js/jquery.emojiarea.js"></script>
    <script src="/js/emoji-picker.js"></script>
    <script>
        feather.replace()    
    </script>
    <script>
     function handleNav(){
            var x = document.getElementById("mySidenav");
              if (x.style.width == 0 || x.style.width == "0px"){
                x.style.width = "250px";
              } else {
                x.style.width = 0;
              }
        }
    
    </script>
    <script>
        $(document).ready(function() {
           
            $('body').click(function(){
               if($('#mySidenav').width() != 0 )
                    $('#mySidenav').width(0);
              
            });
            $(function() {
                  window.emojiPicker = new EmojiPicker({
                  emojiable_selector: '[data-emojiable=true]',
                  assetsPath: '/img/emoji/',
                  popupButtonClasses: 'fa fa-smile-o'
                });
                window.emojiPicker.discover();        
            });
            $('#chat-div').scrollTop($('#chat-div')[0].scrollHeight);  
            $("#send_msg").click(function(){
                $.ajax({
                    url: location.href,
                    type: "POST",
                    data: "msg=" + $("#msg").val(),
                    success:function(data){
                       // alert(data)
                    $("#ch_msg").load(location.href +" #ch_msg>*");
                    $("#direct-msgs ul").load(location.href +" #direct-msgs ul");
                    $("#msg").val('');
                          $(".emoji-wysiwyg-editor").text('');
                       $('#chat-div').animate({scrollTop:$('#ch_msg')[0].scrollHeight}); 
                        
                     
                    }
                });                     
            });
            $('.selectpicker').selectpicker({
                style: 'btn-default text-left btn-sm bg-white',
                width:'127px',
                size: 5
            });
            setInterval(function(){
                $.ajax({ success: function(data){
                   $("#channel-info").load(location.href +" #channel-info>*");
                   $("#ch-pupose").load(location.href +" #ch-pupose>*");
                $("#ch_msg").load(location.href +" #ch_msg>*");
                
                    //location.reload();
                }
                });
            }, 5000);  
            
            $("#direct-msg").click(function() {
              $("#browse-members").show();
            });
            
             
            $("#invite-a").click(function() {
              $("#workspace-invite").show();
            });
            $("#wi-esc").click(function() {
               $("#workspace-invite").hide();
               $("#invite-email-list span").text('');
               $("#ws-email-invalid").hide();
               $(".enter-email-id").val('');
              
            }); 
            $("#dm-esc").click(function() {
              $("#browse-members").hide();
              $('#search-mem').val('');
            }); 
            $("#ch-esc").click(function() {
              
            $("#ch-name").val('');
            $("#ch-purpose").val('');
            $("#mem-list span").text('');
            $("#mem-list ").text('');
            $("#mem").text('');
              $("#add-ch").hide();
            });
            $("#bc-esc").click(function() {
              $("#browse-channels").hide();
              $('#search-ch').val('');
            }); 
            $("#ci-esc").click(function() {
              $("#channel-invite").hide();
              $('#ch-mem-list').text('');
              $('#ch-mem').text('');
            }); 
          
            $("#ch-setting-add").click(function() {
              $("#add-ch").show();
            });
            $("#ch-setting-brws").click(function() {
              $("#browse-channels").show();
            });
            $("#search-mem").on("keyup",function() {
                var value = $(this).val().toLowerCase();
                $("#member-list div").filter(function() {
                  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            $("#add-mem").on("keyup", function() {
                //$("#wsmember-list").toggle();
                var value = $(this).val().toLowerCase();
                $("#wsmember-list div").filter(function() {
                  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            $("#search-ch").on("keyup",function() {
                var value = $(this).val().toLowerCase();
                $("#channel-list div").filter(function() {
                  $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            
            $("#member-list div").click(function(e) {
                $('#search-mem').val($(this).text());
                var uid = e.target.id;
                $('#search-mem').data("uid",e.target.id);
                $('#search-mem').data("uname",$('#search-mem').val());
               // alert($('#search-mem').data("uid"));
            });
            
            $("#channel-list div").click(function(e) {
                $('#search-ch').val($(this).text());
                var cid = e.target.id;
                $('#search-ch').data("cid",e.target.id);
                $('#search-ch').data("cname",$('#search-ch').val());
                //alert($('#search-ch').data("cid"));
            });
            
            $("#wsmember-list div").click(function(e) {
                var uid = e.target.id;
                $("#mem").val($(this).text());
                var getValue = $("#mem").val();                
                $('#mem-list').append('<span id="'+ uid +'" class="members">'+ getValue +' <span class="cancel-member"><i class="far fa-times-circle"></i></span></span>');
                $('#mem-list').data(uid,uid);
                $("#mem").val('');
                
            });
            
            
            $("#add_member").click(function(){
                    var uid = $('#search-mem').data("uid");
                    var uname = $('#search-mem').data("uname");
                    refreshChat(uid);
                    var item='<li class="nav-item"><a id="'+uid+'" class="nav-link">'+
                    '<i style="padding-right:0.5rem" class="fa fa-user"></i>'+uname+
                    '</a></li>';
                    //$("#direct-msgs ul").append(item);
                    $("#browse-members").hide();
                    $('#search-mem').val('');                       
                                        
                    
            }); 
            function joinChannel(cid){
                $.ajax({
                        type: "POST",
                        data: {joincid: cid},
                        success:function(data){
                            //alert(data);
                            $("#browse-channels").hide();
                            $('#search-ch').val('');
                            refreshChat("CH"+cid);  
                            $("#channels ul").load(location.href +" #channels ul");
                                                      
                }});   
            }
            $("#join_channel").click(function(){
                    var cid = $('#search-ch').data("cid").substring(2);
                    var cname = $('#search-ch').data("cname"); 
                    joinChannel(cid);
                     
            }); 

            function refreshChat(uid){
                if(uid.substr(0,2)=='DM')
                    $('#settings-menu').hide();
                else
                    $('#settings-menu').show();
                var new_url =location.origin+"/"+location.pathname.split('/')[1]+"/"+location.pathname.split('/')[2]+"/"+uid+"/"+"messages";
               // alert(new_url);
                window.history.pushState(null,null, new_url);
                $("#channel-info").load(document.URL +" #channel-info>*");
                $("#ch-pupose").load(location.href +" #ch-pupose>*");
                $("#ch_msg").load(document.URL +" #ch_msg>*");
                $('#chat-div').animate({scrollTop:$('#ch_msg')[0].scrollHeight});
            }
            $("#direct-msgs li.nav-item").on("click",function(e){
                var uid = e.target.id;
                refreshChat(uid); 
               
            });
            
            $("#channels ul").on("click",function(e){
                var uid = e.target.id;
                refreshChat(uid); 
               
            });
            
            $("#fav li.nav-item").on("click",function(e){
                var uid = e.target.id;
                refreshChat(uid); 
               
            });
            $("#channels li.nav-item").on("click",function(e){
                var uid = e.target.id;
                refreshChat(uid); 
                //alert(data);
               
            });
          
            $("#create-ch-btn").click(function(e){
                e.preventDefault();
                var memlist = $('#mem-list span:even').map(function() {
                        return $(this).prop("id");              
                    }).get();
                    
               // alert(memlist);
                var valid=1;
                if($('#ch-name').val() == ''){
                    valid=0;
                   $("#ch-name-invalid").show();
                }
                if($('#ch-purpose').val() == ''){
                    valid=0;
                   $("#ch-purpose-invalid").show();
                }
               
                if(valid==1){
                    var ctype = $('#ctype input:radio:checked').val();
                    //alert(ctype);
                    var cname = $("#ch-name").val();
                    var cpurpose = $("#ch-purpose").val();
                    
                    $.ajax({
                        type: "POST",
                        data: {chtype:ctype, chname: cname, chpurpose: cpurpose,chmem: memlist},
                        success:function(){
                            $("#ch-name").val('');
                            $("#ch-purpose").val('');
                            $("#mem-list span").text('');
                            $("#mem-list ").text('');
                            $("#mem").text('');
                            
                        }
                    });   
                 
                    $("#add-ch").hide();  
                    //refreshChat(ch);  
                    $("#channels ul").load(location.href +" #channels ul");
                                     
                }        
                  
            });
             
            $(document).on('click','.cancel-member',function(){              
                $(this).parent().remove();
                
            }); 
            
            $('#ch-name').keypress(function(){
                   $("#ch-name-invalid").hide();
               });
               $('#ch-purpose').keypress(function(){
                   $("#ch-purpose-invalid").hide();
               });
            $(".enter-email-id").keydown(function (e) {
                  $("#ws-email-invalid").hide();
              if (e.keyCode == 13 || e.keyCode == 32) {
                //alert('You Press enter');
                 var getValue = $(this).val();
                 $('.all-mail').append('<span class="email-ids">'+ getValue +' <span class="cancel-email"><i class="far fa-times-circle"></i></span></span>');
                 $(this).val('');
              }
            });
            $(document).on('click','.cancel-email',function(){              
                $(this).parent().remove();
                
            });            
            $("#invite-mem").click(function(e){
                e.preventDefault();
                var mails = $.trim($("#invite-email-list span").text());
                var emails = mails.split(" ");
                                
                if(emails != '') {
                     
                   
                    //alert(emails);
                     var valid=1; 
                     var regExp = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
                     for (var i in emails) {
                         value = emails[i];                         
                         if(!regExp.test(value) ){ 
                            // alert(value);
                             valid=0;
                            $("#ws-email-invalid").show();}
                     }
                     
                }
                if(valid==1){
                        $.ajax({
                        type: "POST",
                        data: {wsinvite: emails},
                        success:function(data){
                            //alert(data);
                            
                            $(".enter-email-id").val('');
                            $("#invite-email-list span").text('');
                            $("#ws-email-invalid").hide();
                            $("#workspace-invite").hide();  
                        }
                    });   
                                     
                }        
                  
            });
            
            $("#ch-member-list div").click(function(e) {
                var uid = e.target.id;
                $("#ch-mem").val($(this).text());
                var getValue = $("#ch-mem").val();                
                $('#ch-mem-list').append('<span id="'+ uid +'" class="members">'+ getValue +' <span class="cancel-member"><i class="far fa-times-circle"></i></span></span>');
                $("#ch-mem").val('');
                
            });
            
            $("#invite-ch-mem").click(function(e){
                e.preventDefault();
                var memlist = $('#ch-mem-list span:even').map(function() {
                        return $(this).prop("id");              
                    }).get();
                    
               // alert(memlist);
                $("#channel-info").load(document.URL +" #channel-info>*");

                $("#ch-mem-list span").text('');
                $("#channel-invite").hide();                  
                $.ajax({
                    type: "POST",
                    data: {chinvite: memlist}
                    
                });  
            });
            $(document).on("click", "#invite-ch-drp", function() {
               $("#channel-invite").show();
            });
            $(document).on("click", "#leave-ch-drp", function() {
                var cid = location.pathname.split("/")[3].substr(2);
                $.ajax({
                        type: "POST",
                        data: {leavech: cid},
                        success:function(data){
                           // alert(data);
                            refreshChat("CH"+cid);
                                $("#channels ul").load(location.href +" #channels ul");
                        }
                             
                });              
            });
             $(document).on("click", "#admin", function() {
               $("#settings").show();
            });
             $(document).on("click", "#admin-esc", function() {
               $("#settings").hide();
            });
            $(document).on("click", "#search-snickr", function() {
                var key = $('#search-key').val();
                var path = location.pathname.split("/");
                if(key!=''){
                    var newurl = location.origin + "/" + path[1] + "/" + path[2] + "/" + "search/" + key;
                   // alert(newurl);
                    window.location.replace(newurl);
                }
                else{
                    var newurl = location.origin + "/" + path[1] + "/" + path[2] + "/" + "search";
                    window.location.replace(newurl);
                }
            });
            $(document).on("keyup", "#search-key", function(e) {
                 var keynew = $('#search-key').val();
                 var key = e.which;
                 if(key == 13){
                    $('#search-snickr').click();
                    return false;
                 }
                 
            });
            $(document).on("click", "#join-ch", function(e) {
                 var cid = location.pathname.split("/")[3].substr(2);
                 joinChannel(cid);
                 
            });
            
           
            
        });
    </script>   
   
  </body>
</html>
