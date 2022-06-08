<?php

if(isset($_POST['logout'])){
    
   session_destroy();
   
   header('location:../../../store/home');
   exit;
    
}

 
 //check for session expiry
 
 if(time() - $_SESSION['time'] > 30*60){
     
 header('location:../../../store/home');    
 }
 
 $token = uniqid().bin2hex(random_bytes(20)).uniqid();//csrf token

$_SESSION['csrf'] = $token;

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTStores| My Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!--jquery cdn-->
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<!--Sweet alert-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" integrity="sha384-Dw2+3qpObGzez20CmU3AMW9GY+Cin5hHaVmupE+SaONsNUANucjrAJ8gLpjHMLXh" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js" integrity="sha384-C9puUm0DsqtZ97l3TI1CYnjClvjaRMP1XLmQZidqapc9iSQNpByN6RImM2XqbEGX" crossorigin="anonymous"></script>


  <!--js cookies-->
  <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js" integrity="sha256-0H3Nuz3aug3afVbUlsu12Puxva3CP4EhJtPExqs54Vg=" crossorigin="anonymous"></script>
  
    
    
    
    <script type="text/javascript" src="user_module/js/us.min.js"></script>
    
     
<script src="//cdn.jsdelivr.net/npm/eruda"></script>
<script>eruda.init();</script> 
  <style>
  body,html{
      background-color:#e0eafc;
  }
    .baseColor{
   
 background-image:radial-gradient( circle farthest-corner at 10% 20%,  rgba(37,145,251,0.98) 0.1%, rgba(0,7,128,1) 99.8% );
 
 }   
  .white{
      color:white;
      font-weight:bold;
  }
  .disclaimer{
    display:none;  
      
  }    
      td{
          font-size:13px;
      }
  </style>  
    
  <link id="load-theme" href=""  rel="stylesheet"> 
      
     
  </head>
  <body class="has-navbar-fixed-top">
    
 <nav  style="height:12%; border:0px;" class="navTop navbar is-fixed-top baseColor">
      
  <div class="navbar-brand">
      
      
      <!--store logo-->
    <a class="navbar-item" href=" ">
   
      <img src="../../../public/logo/mts.png" alt="Logo" width="" height="">
        
       
    </a>
   

 <!--Store info--> 
  <div  class="container is-fluid">
      
   <span style="font-size:12px;"   class="info white mt-3"><i class="fa fa-group mr-1"></i><?=$_SESSION['user'];?></span>  
      
          
      
  </div>
  
  
  

   
     
  </div>
            
 
</nav>

<div class="container is-fluid block mt-5">
    
 <p id="change-theme" class="button is-link is-light is-rounded mr-2 mt-5"> 
 <input class="mr-2" type="checkbox">
 <span id="mode">Dark Mode</span>
 <span style="display: none;" id="t-loader" class="ml-3 button is-loading is-link is-small"></span>
 
 </p>
 



</div> 

    
    <input id="token" type="text" value="<?= $token;?>" hidden>
    <!--details modal-->

<div id="details-modal" class="modal">
    
    
  <div class="modal-background"></div>
  
  <div class="modal-card">
    <header class="modal-card-head">
      <p  class="button modal-card-title baseColor white ">Payment Details</p>
      <button id="details-close" class="button is-danger delete" aria-label="close"></button>
    </header>
    <section class="modal-card-body">
      
        
      <!-- Content ... -->
     
 
     <div class="notification is-light is-link">This is the products ordered for in payment.
  </div>  
  
  <div class="box">
<div class="table-container">
 <table  class="table is-stripped is-outlined is-hoverable is-bordered">
    <thead>
 <tr>
 <th>Product</th>  
 <th>Quantity</th>
   <th>Price<span id="currency-info"></span></th>
  
 </tr>       
        
        
    </thead> 
     
 <tbody id="show_details">
     
     
     
     
     
 </tbody>    
     
     
     
 </table> 
   

</section>
</div>
</div>
</div>
</div>

<!-- end of details modal-->




    
    
    

      
      
      
  <section class="section">
      
 <div class="columns is-mobile">
     
   <div class="column is-half-mobile is-half-tablet">
       
     <div style="display:flexx; flexx-flow:column wrap; justify-content:center;" class="box">
         
    <figure class="image is-64x64 ml-2 mt-2 mb-2">
 <img id="prof" src="../../../../public/img/user.jpg" class="image is-rounded">   
     
 </figure>
 
 <h2>My profile</h2>
 
  <p style="font-weight:bold;" class="tag is-success is-light  is-small  "><span class="fa fa-user-o mr-1"></span><?=$data['fname'];?>...</p>
 
 
  <p style="font-weight:bold;" class="tag is-success is-light  is-small mt-2 "><span class="fa fa-phone mr-1"></span><?=$data['phone'];?></p>
 
 
   <p style="font-weight:bold;" class="tag is-danger is-light  is-small mt-2 "><span class="fa fa-globe mr-1">Beware of scam</span></p>
 
 
          
      </div> 
       
   </div>
   
   
   
   
   
        <div class="column is-half-mobile is-half-tablet">
       
     <div style="display:flex; justify-content:center; flex-flow:row wrap;" class="box">
         
    <p id="transactions" style="font-weight:bold;" class="tag is-link is-light  is-small block mr-3"><span class="fa fa-credit-card mr-1"></span><a href="#transac">My Transactions</a></p>
    

        <p id="change_password" style="font-weight:bold;" class="tag is-link is-light  is-small block mr-3"><span class="fa fa-key mr-1"></span><a href="../../../user/reset_password">Reset password</a></p>
    
    
          <p id="edit_profile" style="font-weight:bold;" class="tag is-link is-light  is-small block mr-3"><span class="fa fa-paper-plane-o mr-1"></span>Edit Profile</p>
          
          
                    <a href="../../../store/home" id="edit_profile" style="font-weight:bold;" class="tag is-link is-light  is-small block mr-3"><span class="fa fa-home mr-1"></span>HomePage</a>
          
          
          
          
    <form action="" method="post">
      
           <button type="submit" name="logout" id="logout" style="font-weight:bold;" class="tag is-danger is-light  is-small block mr-3"><span class="fa fa-power-off mr-1"></span>Logout</button>
    </form>
     
    
    
          
      </div> 
       
   </div>
     
     
   
     
 </div>  
  </section>
  
  
  <section class="section">
      
  <div class="container is-fluid box">
    <p class="button is-link baseColor white">Recent Transactions</p> 
    <div class="table-container">
   
   <p>Click on status button to check more details</p>
   
<table id="transac" class="table is-stripped is-bordered is-hoverable is-narrow">
 <tr>
 <th>S/N</th>   
 <th>Reference</th>
 <th>Amount</th>
 <th>Date</th>
 <th>Status</th>
 </tr>       
   
   
<tbody id="load-more">
   
  <?php
  
 $r= $data['d'];
 $sn = 1;
 while($row = $r->fetch_assoc()){
     
 $ref = $row['reference'];
 
 $amount = $row['totalPaid'];
 $amount = number_format($amount);
 $date = $row['date'];
 
 $receipt = $row['receipt'];

 
 $receipt = explode("*",$receipt);
 
 
 if($receipt[2] == "nairaPrice"){
    $currency = "₦" ;
     
 }
 
 if($receipt[2] == "dollarPrice"){
    $currency = "$" ;
     
 }
 
 if($receipt[2] == "poundsPrice"){
    $currency = "£" ;
     
 }
 
 
 $status = $row['status'];
 
 if($status == "check"){
     
     $css = "success";
 }
 
 else{
     
     $css="danger";
 }
  
   echo'
        
   <tr>
       
 <td>'.$sn.'</td>     
 <td>'.$ref.'</td>
 <td>'.$currency.$amount.'</td>
 <td>'.$date.'</td>
 <td class="details"><span class="tag is-'.$css.' fa fa-'.$status.'"></span></td>
   </tr>';  
   
   $sn++;
   
 }
   ?>
   </tbody>
 
    </table>
    

    </div>  
    

     
     
         <div id="more" class=" button is-link is-small">More</div>
      
     <input type="number" value="1" id="page" hidden>
    
  </div>  
  </section>
  
  <section style="margin:auto;" class="section">
      
  <div class="container">
      
  <div class ="box  baseColor white">
      
  <p>If you need any help, please call any of our contact lines below. Note that we wont ask for any dine to assist you. Do not fall a victim of scam .</p>
   
   <p class="block">Contacts </p> 
      
   <p style="font-weight:bold; font-size:22px;">
       
  <span class="fa fa-phone mr-2">+76537373737</span> |  <span class="fa fa-envelope mr-2">admin@you.com</span> 
       
   </p>   
      
      
  </div>   
      
      
      
      
  </div>    
      
      
      
      
  </section>
  
  </body>
</html>