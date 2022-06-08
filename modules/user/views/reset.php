<?php

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MTStores| Reset password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    
    
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">  
  
 
    
    <!--jquery cdn-->
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  
  
  <script src="user_module/js/reset.js"></script>
  
    <!--js cookies-->
  <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js" integrity="sha256-0H3Nuz3aug3afVbUlsu12Puxva3CP4EhJtPExqs54Vg=" crossorigin="anonymous"></script>
  
  
  
<link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  <link id="#load-theme" href="" rel="">
  
    
    
  <script type="text/javascript" src="user_module/js/user.min.js"></script>
    
     
<script src="//cdn.jsdelivr.net/npm/eruda"></script>
<script>eruda.init();</script> 
  
  
  
    <style>
  .box{
      border-radius:10px 20px 70px;
  }      
  .disclaimer {
      display:none;
  }
        .white{
            color:white;
        }
    </style>
  </head>
  <body class="">
      

      
  <section class="section">
      
 <div class="box">
     
<p class="title is-5 button is-link is-small">MTStores| Password Reset</p> 
<hr>
<div clas="columns">
    <p class="box">Please enter your valid email as password reset link will be sent there!!</p>
   
    
    
 <div class="column"> 
<div class="control has-icons-left">
    
 <div class="field">
        
  <input id="email" type="email" class="input is-link " placeholder="Enter Email..." required>
    </div>
    </div>
   </div>
   
   
    <div class="column"> 
<div class="control has-icons-left">
    
 <div class="field">
        
  <input id="phone" type="tel" class="input is-link " placeholder="Enter Phone number"  required>
    </div>
    </div>
   </div>
   
   
    <input id="auth" type="text" value="<?= $data['auth']; ?>" hidden>
    
   <div class="field">
       
   <button id="reset" class="button is-rounded is-outlined is-link">Reset</button>    
   </div>
    
    
</div>
 </div>   
  </section>
  </body>
</html>