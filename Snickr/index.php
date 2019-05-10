<?php 
    include 'controller/workspace.php';
?>
<?php
    // redirect user to login page if they're not logged in
    if (empty($_SESSION['id']))        
        header('location: /login.php');
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <!-- Font awesome CSS -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" >

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/css/main.css">
  <link rel="stylesheet" href="/css/home.css">
  <title>Snicker - Home</title>
</head>

<body>
  <div class="container">
    <div class="row">
        <!--hidden div -->
        <div id="add-ws">           
            <div class="col-md-6 offset-md-3 home-wrapper" >
              <h4 >Create Workspace</h4>  
              <div class="form-group">
                <label>Workspace Name</label>
                 <input id="ws-name" type="text" name="wrsname" class="form-control form-control-lg" >
                 <div id="ws-name-invalid" style="display:none;" class="alert alert-danger">Workspace name required</div>
              </div>                  
              <div class="form-group">
                <label>Add people(Optional)</label>
                <div class="form-control form-control-lg ">
                    <span class="to-input"></span>
                    <div id="email-list" class="all-mail">                         
                    </div>
                    <input  type="text" name="email" class="enter-email-id " placeholder="Enter the email id .." >
                </div>
                 <div id="ws-email-invalid" style="display:none;" class="alert alert-danger">Email not valid</div>
              </div> 
                <button id="create-ws-btn" class="btn btn-secondary" name="create-btn" class="btn btn-lg btn-block">Create</button>
                <button id="cancel-ws-btn" class="btn btn-secondary" name="cancel-btn" class="btn btn-lg btn-block">Cancel</button>
                
            </div>
        </div>
                    
      <!--hidden div end -->
      <div class="d-flex flex-column col-md-12 flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
          <h5 class="my-0 mr-md-auto font-weight-normal">Welcome to SnicKr</h5>      
          <nav class="my-2 my-md-0 mr-md-3">
            
            <a class="p-2 text-dark" href="#">My Account</a>
            <a class="p-2 text-dark" href="#">Workspaces</a>  
            <a class="p-2 text-dark" id="create-ws" style="cursor:pointer">Create worksace</a>  
          </nav>
            <div>
                <h5><?php echo $_SESSION['username']; ?></h5>
                <a class="btn btn-outline-primary" href="logout.php">Log out</a>
            </div>
      </div>
      <div class="col-md-6 offset-md-3 home-wrapper">
        <!-- Display messages -->
        <?php if($_SESSION['message']): ?>
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong><?php echo $_SESSION['message']; ?></strong>
          </div>
        <?php endif;  ?>
        <?php if (!$_SESSION['verified']): ?>
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            You need to verify your email address!
            Sign into your email account and click
            on the verification link we just emailed you
            at
            <strong><?php echo $_SESSION['email']; ?></strong>
          </div>
        
        </br></br>
         
          
        <?php else:?>
        
        <h6>Your workspaces are:</h6>
        <div id="ws-container">
             <div id="ws-list">
                <ul class="nav flex-column mb-2">
                <?php                
                $ws_list = getWorkspaces($_SESSION['id']);
                if($ws_list==-1) echo '<p> some error occured</p>';
                if($ws_list==NULL) echo '<p> Yor are not currently part of any workspace.</p>';
                else{                    
                    foreach($ws_list as $ws) { 
                        $general=getGeneralChannelId($ws['wurl']);
                        $link = "workspace/".explode('@',$ws['wurl'])[0]."/CH".$general."/messages";
                        $name = $ws['wname'];
                        echo '<li class="nav-item">
                        <a style="cursor:pointer" class="nav-link" href="'.$link.'" >';
                        echo $name;
                        echo '</a></li>' ;
                    
                    } 
                }
                ?>
           
            
            </div>
        </div>
        <?php endif;?>
      </div>
    </div>
 </div>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   <script>
        $(document).ready(function() {
            $('#create-ws').click(function(){
               $("#add-ws").show();
            });
            $('#cancel-ws-btn').click(function(){
                $(".ws-name").val('');
                $("#email-list span").text('');
                $("#ws-email-invalid").hide();
                $("#add-ws").hide();  
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
            $('#ws-name').keypress(function(){
                   $("#ws-name-invalid").hide();
               });
                
            $("#create-ws-btn").click(function(e){
                e.preventDefault();
                var maillist = $("#email-list span").text();
                var valid=1;
                if($('#ws-name').val() == ''){
                    valid=0;
                   $("#ws-name-invalid").show();
                }
                
                if(maillist != '') {
                     var mails = $.trim(maillist);
                     var emails = mails.split(" ");
                     var regExp = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
                     for (var i in emails) {
                         value = emails[i];                         
                         if(!regExp.test(value) ){ 
                             alert(value);
                             valid=0;
                            $("#ws-email-invalid").show();}
                     }
                     
                }
                if(valid==1){
                    var ws = $("#ws-name").val();
                    var maillist = $("#email-list span").text();
                   
                     $.ajax({
                        type: "POST",
                        data: {wsname: ws, emaillist: maillist},
                        success:function(data){
                            alert(data);
                            $("#ws-container").load(" #ws-list");
                            $(".enter-email-id").val('');
                            $(".ws-name").val('');
                            $("#email-list span").text('');
                            $("#ws-email-invalid").hide();
                            $("#add-ws").hide();  
                        }
                    });   
                                     
                }        
                  
            });   
        });
        
       
  
   </script>        
</body>
</html>