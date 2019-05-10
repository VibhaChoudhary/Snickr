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
    if(!isWsMember($query_array['wurl'],$_SESSION['id'])){
      // echo "true";echo $query_array['wurl'];echo $query_array['key'];
       header('location: /view/access_denied.php');
    }   
    if(!isset($query_array['key'])){
        header('location: /index.php');
    }
    $key = htmlspecialchars($query_array['key']);
    
    //$key = $query_array['key'];
    //echo $key;
    //echo $query_array['wurl'];
    
    $cmsg_results=''; $dmsg_results='';$ch_results='';$user_results='';
    $cc=0;$cu=0;$cm=0;$total=0;
    if(!empty($key)){
        $cmsg_results=searchChannelMessages($key,$_GET['wurl'],$_SESSION['id']);
        $dmsg_results=searchDirectMessages($key,$_GET['wurl'],$_SESSION['id']);
        $ch_results=searchChannels($key,$_GET['wurl'],$_SESSION['id']);
        $user_results=searchMembers($key,$_GET['wurl'],$_SESSION['id']); 
        $cc=count($ch_results);$cu=count($user_results);$cm=count($cmsg_results) +  count($dmsg_results);
        $total = $cc + $cu + $cm;       
    }
    else $total = 0;
   
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
    <div id="search-ws">           
        <div class="col-md-6 offset-md-3 home-wrapper" >
                <button id="search-esc" type="button" class="btn pull-right mb-4"><i class="fas fa-close" ></i>
                </button>
                
                <div class="input-group mt-3 p-2"> 
                    <input autocomplete="off" id="search-key" class="form-control mr-2" type="text" placeholder="Search users,channels or messages" value="<?php if(!empty($key)) echo $key ?>" aria-label="Search">
                    <a id="search-btn" class="btn border border-primary">Search</a> 
                </div>
                <h4 class="p-2 ">Search Results</h4>
                <label class="p-2">Total <?php echo $total?> results found for <span class="text-muted"><?php echo "\"$key\""?></span></label>
                <ul class="nav nav-tabs">
                    <li ><a data-toggle="tab" class="nav-link active" href="#ch-result">
                    Channels(<?php echo $cc?>)</a></li>
                    <li ><a data-toggle="tab" class="nav-link " href="#msg-result">
                    Messages(<?php echo $cm?>)</a></li>  
                    <li ><a data-toggle="tab" href="#user-result" class="nav-link ">
                    Users(<?php echo $cu?>)</a></li>                      
                </ul>
                <div class="tab-content">
                    <div id="ch-result" class="col md-4 tab-pane fade show active"></br>
                         <?php  if(!empty($key)){foreach($ch_results as $channel){ ?>
                            <a id="<?php echo $channel['cid']?>" href="#"><?php echo $channel['cname']?></a></br>
                         <?php }}?>
                    </div>
                    <div id="msg-result" class="col md-4 tab-pane fade ">
                        </br>
                         <?php  if(!empty($key)){if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
                            $newurl = "https"; 
                            else
                                $newurl = "http"; 
                            $newurl .="://";
                            $ws = $newurl.$_SERVER['HTTP_HOST']."/workspace/".explode('@',$_GET['wurl'])[0]."/CH";
                            foreach($cmsg_results as $chmsg){ ?>
                            <a href="<?php echo $ws.$chmsg['cid']."/messages"?>">#CH<?php echo $chmsg['cid'];?></a>
                            <div class="chats-text-cont">
                               <div class ="user-list">
                                    <img class="d-flex rounded-circle avatar z-depth-1-half mr-3" src="/img/u1.png" alt="user-profile">
                                    <a href="#"><span class="h6">
                                    <?php echo $chmsg['uname'];?>
                               </span></a><span class="small px-3"> <?php echo $chmsg['message_ts'] ?>
                               </span>
                               <p><?php echo $chmsg['mcontent'];?></p> 
                              </div></div>
                        <?php }
                           if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
                            $newurl = "https"; 
                            else
                                $newurl = "http"; 
                            $newurl .="://";
                            $ws = $newurl.$_SERVER['HTTP_HOST']."/workspace/".explode('@',$_GET['wurl'])[0]."/DM";
                            foreach($dmsg_results as $dmsg){ ?>
                            <a href="<?php echo $ws.$dmsg['fromuid']."/messages"?>">#DM<?php echo $dmsg['fromuid'];?></a>
                            <div class="chats-text-cont">
                               <div class ="user-list">
                                    <img class="d-flex rounded-circle avatar z-depth-1-half mr-3" src="/img/u1.png" alt="user-profile">
                                    <a href="#"><span class="h6">
                                    <?php echo $dmsg['uname'];?>
                               </span></a><span class="small px-3"> <?php echo $chmsg['message_ts'] ?>
                               </span>
                               <p><?php echo $dmsg['dmcontent'];?></p> 
                              </div></div>
                            <?php }}?>
                    </div>
                    <div id="user-result" class="col md-4 tab-pane fade">
                         <?php  if(!empty($key)){foreach($user_results as $user){ ?></br>
                            <div class ="user-list">
                            <img class="d-flex rounded-circle avatar z-depth-1-half mr-3" src="/img/u1.png" alt="user-profile">
                                    <a id="<?php echo $user['uid'];?>" href="#"><span class="h6">
                                    <?php echo $user['uname'];?>
                               </span></a></div>
                         <?php }}?></br>
                    </div>
                </div>
                    
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
        $(document).ready(function() {   
            $(document).on("click", "#ch-result a", function(e) {
                var id = e.target.id;                
                var path = location.pathname.split("/");
                var newurl = location.origin + "/" + path[1] + "/" + path[2] + "/" + "CH" + id + "/messages";
                // alert(newurl);                
                window.location.replace(newurl);        
              
            });
            $(document).on("click", "#search-esc", function(e) {
                $("#search-ws").hide();
                if(document.referrer!="")
                    window.location.replace(document.referrer);
                else 
                    window.location.replace(location.origin);
            });
            function performSearch(keynew){
                //alert(keynew);
                if(keynew!=''){
                                  
                    var path = location.pathname.split("/");
                    var newurl = location.origin + "/" + path[1] + "/" + path[2] + "/search/"+keynew;
                    window.history.pushState(null,null, newurl);
                    $("#search-ws").load(location.href +" #search-ws");
                    /*
                    $.ajax({
                            url: newurl,
                            type: "POST",
                            data: {keychange: keynew},
                            success:function(data){
                                //alert(data);
                                $("#search-ws").load(location.href +" #search-ws");
                            }
                                 
                    }); */
                }   
            }
            $(document).on("click", "#search-btn", function() {
                var keynew = $('#search-key').val();
                performSearch(keynew);  
            });
            $(document).on("keyup", "#search-key", function(e) {
                 var keynew = $('#search-key').val();
                 var key = e.which;
                 if(key == 13){
                    
                    performSearch(keynew);  
                 }
                 
            });
            
                 
        });
    </script> 
  </body>
</html>    