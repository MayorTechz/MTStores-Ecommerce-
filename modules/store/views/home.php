<?php


/*This is the view file for the store home. It mostly contains html and css for page format and design with a little php code to display data*/



//checks for active user session for login status



$status="";

$html ="";

if(isset($_SESSION['user'])){
    
 $status ="online";  
 
 
 //to hide login and register button if user is online
 $html = " style='display:none;' ";
 
 
}

else{
   
    
    $html="";
}


$res = $data['res'];//results data



 //rating html
 $star ="<span class='fa fa-star'></span>";
 $no_star ="<span class='fa fa-star-o'></span>";
 $half_star ="<span class='fa fa-star-half-o'></span>";
 
 $zero_star = str_repeat($no_star,"5");
 
 $zero_half = $half_star."".str_repeat($no_star,"4");
 
 $one = $star."".str_repeat($no_star,"4");
 
 $one_half = $one. $half_star."".str_repeat($no_star,"3");
 $two = str_repeat($star,"2");
 $two_half=str_repeat($star,"2").$half_star.str_repeat($no_star,"2");
 $three =  str_repeat($star,"3").str_repeat($no_star,"2");
 $three_half= str_repeat($star,"3").$half_star.str_repeat($no_star,"1");
 
 $four = str_repeat($star,"4").$no_star;
 $four_half= str_repeat($star,"4").$half_star;
 $five = str_repeat($star,"5");
 
 
  //set current timezone of user.
$ip = $_SERVER['REMOTE_ADDR']; // user's IP address 
  
 $tz = $this->Functions->set_tz($ip);
 
 
 //flash product db results
  if(isset($data['flash'])){
 $r = $data['flash'];

}

$_SESSION['pay_auth']  = bin2hex(random_bytes(20));


if(isset($_SESSION['stripe']) && $_SESSION ['stripe'] == 'completed'){
    
    $css = ' display:none; ';
}

?>

<!doctype html>

<html class="" lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#0f3ac3" >

<!-- Windows Phone -->
<meta name="msapplication-navbutton-color" content="#0f3ac3">
<!-- iOS Safari -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

  <title>MT STore | Quality at its peak!!</title>
  <meta name="description" content="A simple ecommerce shop tutorials.">
  <meta name="author" content="MayorTech">

  <meta property="og:title" content="MT Stores ecommerce">
  <meta property="og:type" content="website">
  <meta property="og:url" content="mtsotorez.000webhostapp.com">
  
  <meta property="og:image" content="image.png">
  
<link rel="icon" sizes="192x192" href="../../../public/img/android-icon/192x192.png">

<link rel="apple-touch-icon" sizes="57x57" href="../../../public/img/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="../../../public/img/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="../../../public/img/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="../../../public/img/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="../../../public/img/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="../../../public/img/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="../../../public/img/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="../../../public/img/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="../../..//public/img/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="../../../public/img/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="../../../public/img/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="../../../public/img/favicon-16x16.png">
   


<!--bulma css-->

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">

 

  
  <!--Sweet alert-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" integrity="sha384-Dw2+3qpObGzez20CmU3AMW9GY+Cin5hHaVmupE+SaONsNUANucjrAJ8gLpjHMLXh" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js" integrity="sha384-C9puUm0DsqtZ97l3TI1CYnjClvjaRMP1XLmQZidqapc9iSQNpByN6RImM2XqbEGX" crossorigin="anonymous"></script>

  <!--font awesome icon-->
  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

  
  <!--eruda-->
<script src="//cdn.jsdelivr.net/npm/eruda"></script>
<script>eruda.init();</script>


<!--jquery cdn-->
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>


 <!-- home js asset trigger--> 
  <script src="store_module/js/hm.js"></script>


  
  <!--js cookies-->
  <script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js" integrity="sha256-0H3Nuz3aug3afVbUlsu12Puxva3CP4EhJtPExqs54Vg=" crossorigin="anonymous"></script>
  
  

  
  <!--custom css-->
<link id="main-style" rel="stylesheet" href="store_module/css/home.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Joan&family=Tiro+Devanagari+Marathi&display=swap');

@import url('https://fonts.googleapis.com/css2?family=Joan&display=swap');

body{
   font-family: 'Joan', serif;
font-family: 'Tiro Devanagari Marathi', serif;
}

.cart-btn{
    
font-family: 'Joan', serif;
}
</style>

</head>

<body  class="has-navbar-fixed-top has-navbar-fixed-bottom bg">
    
    <input type="text" id = "category-auth" value="<?= $data['category_auth']; ?>" hidden>
    
<?php

//stripe paayments notifications.
if(isset($_SESSION['response'])){
    
    if($_SESSION['response'] == "success"){
        
 echo'<script> 
         
  Swal.fire("Congrats","Payment was successful","success");      
   </script>';
    }
    
    
    else{
        
 echo'<script> 
         
  Swal.fire("Sorry","Payment Failed!! Try again!!","error");      
   </script>';
        
    }
    
    
    unset($_SESSION['response']);
}

?>
    
    
    
<!--form token-->

<input id="form_token" type="text" value="<?=$_SESSION['form_token'];?>" hidden>
    
    
<script src="https://checkout.stripe.com/checkout.jss"></script>

<!-- paystack -->
<script src="https://js.paystack.co/v1/inline.js"></script>   
    
<script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
    <script src="https://js.stripe.com/v3/"></script>
  
  <div style="
  <?php 
  //hiding of preloader
 if(isset($css)){
 echo $css;
 }unset($_SESSION['stripe']);?>">
      
      
 <div style="" id="preloader">
<div id="loader"></div>
<span style="position:fixed; color:white; top:50%; left:30%;" class="mt-3 title is-3">MTStores <i class="ml-1 fa fa-shopping-cart"></i></span>

 </div>
 
<div id="preloader2">
     
<div id="loader"></div>
<span style="position:fixed; color:white; top:50%; left:30%;" class="mt-3 title is-3">MTStores <i class="ml-1 fa fa-shopping-cart"></i></span>

 </div>
 
 </div>
   
    
    <!-- online status-->
    
    <input id="online-status" type="text" value="<?=$status;?>" hidden>
    
    
  
  <!--Home Fixed navbar  -->
  
  <nav  style="height:12%; border:0px;" class="navTop navbar is-fixed-top baseColor">
      
  <div class="navbar-brand">
      
      
      <!--store logo-->
    <a class="navbar-item" href=" ">
   
      <img src="../../../public/logo/mts.png" alt="Logo" width="" height="">
        
       
    </a>
   
  <!-- search bar-->
  <div class="control has-icons-left mt-3"> 
  
  <input id="search-keyword" type="text" class="input is-link is-rounded is-small" name="search" placeholder="Search here...">
  <span class="icon is-left">
      
   <i class="fa fa-search" ></i> 
  </span>
  </div>
  
  
  
  <!--toggler icon-->
    
    <span class="navbar-burger white" data-target="navbarExampleTransparentExample">
      <span></span>
      <span></span>
      <span></span>
    </span>
    
    
  </div>
  
 <!--Store info--> 
  <div  class="container is-fluid">
      
   <span style="font-size:10px;"   class="info white mt-3"><i class="fa fa-mobile"></i> +127014318</span>  
      
         <span style="font-size:10px;" class="info white mt-3 ml-3"><i class="fa fa-envelope"></i> admin@mtstores.com</span>  
      
  </div>
  
  
  
  <!--navbar menu-->

  <div id="navbarExampleTransparentExample" class="navbar-menu box menuBackground">
      
    <div class="navbar-start container is-fluid">
 <p class="tag is-light is-link">Categories</p>
 <hr>
 
      <a class="navbar-item menus" href="">
  <span class="mr-1 fa fa-home"></span> All</a>
      
      <a class="navbar-item menus" id="men">
  <span class="mr-1 fa fa-chalkboard-user"></span> Men
      </a>
      
          <a id="electronics" class="navbar-item menus">
  <span class="mr-1 fa fa-laptop"></span> Electronics
      </a>  
            <a class="navbar-item menus" id="women">
  <span class="mr-1 fa fa-universal-access"></span> Women  
      </a>
           <a class="navbar-item menus" id="phone">
  <span class="mr-1 fa fa-mobile"></span> Phone/Tablet  
      </a> 
      
          <a class="navbar-item menus" id="children">
  <span class="mr-1 fa fa-child"></span> Children  
      </a>  
    
     
  <!-- currency-->
  <div id="change_currency" class="column currency">
       <p>Change Currency</p>
  <input id="active-currency" type="text" value="<?=$_SESSION['currency'];?>" hidden> 
  
 <div id="naira" class=" button is-dark is-light is-outlined ml-3 is-small">&#x20A6;</div>    
    
    
    <div id="dollar" class="button is-dark is-light is-outlined ml-3 is-small">$</div>   
     <div id="pounds" class=" button is-dark is-light is-outlined ml-3 is-small">£</div>  
    
  </div>

    </div>

    <div class="navbar-end">
      <div class="navbar-item">
        <div class="field is-grouped">
                        <div class="field">
     <button <?=$html?> id="nav-login" class="button is-link is-small is-rounded mr-2">Login</button>       </div>
     
 <div class="field">
     <button <?=$html?> id="nav-reg" class="button baseColor is-small  is-rounded ml-3 white">Register <span class="fa fa-address-book ml-2"></span>
     </button>
     </div>  
     
            <div class="field">
     <button id="nav-logout" class="button is-danger is-small is-rounded ">Logout</button>       </div>
     
       <div class="field">
           <a href="../../../user/profile">
     <button id="nav-profile" class="button baseColor is-small  is-rounded ml-3 white">My Profile <span class="fa fa-user ml-2"></span>
     </button></a>
     </div>  
     
    
     
     
     
     
  </div>
             </div>
      
  </div>
</nav>
  <!-- end of navigation -->
  
  
  
  <!--Search modal -->
  <div style="" id="search-modal" class="modal">
    <input name="form_auth" id="search_auth" type="text" value="<?php echo $_SESSION['auth']; ?>" hidden> 
    
  <div class="modal-background"></div>
  
  <div class="modal-card">
    <header class="modal-card-head">
      <p  class="tag modal-card-title baseColor white is-small">Search Results!!!</p>
      <button id="search-close" class="button is-danger delete" aria-label="close"></button>
    </header>
    
    
    <section class="modal-card-body">
      
        
      <!-- Content ... -->
  
  
  
  <div id="products2" style="display;flex; flex-flow:row wrap; justify-content:center;"  class="columns products">
      
     <!-- search results here --> 
     <p id="results">
         
    Loading.....     
         
     </p>
      
  </div>
  
     
    </section>
</div>
</div>
</div>


<!-- end of search modal-->
  
  
  
  
  
  
 
  <!--header -->
  <div style="display:none;" class="mt-5">
  <section class="">
      
 <div class="container is-fluid mt-5"> 
  <div class="columns mt-5">
      
    <!-- header image--> 
 <div class="column is-half is-half-desktop is-half-tablet ">
      <div class="box is-light is-link">
      <figure class="image is-2by1">
  <img class="" id="headerImg" src="../../../public/img/i1.jpg"> 
  </figure>
  </div>
  </div>   
 <div class="column is-half-desktop is-half-tablet mm">
      <div class="box  is-link ">
     <p class="baseColor white button is-small is-rounded">Welcome!!! </p>  
      <span style="font-size:12;" class=""><br>
      Please note that we wont collect an upfront from you for goods. All payments are done online and afterwards our representative will speak with you to confirm your order. <b> Happy Shopping...</b>

  </div>
  </div>   
      
    </div>  
  </div>
  </section>
  </div>
  
<div class="container is-fluid block mt-5">
    
 <p id="change-theme" class="button is-link is-light is-rounded mr-2 mt-5"> 
 <input class="mr-2" type="checkbox">
 <span id="mode">Dark Mode</span>
 <span style="display: none;" id="t-loader" class="ml-3 button is-loading is-link is-small"></span>
 
 </p>
 



</div> 
  

 <?php 


//display flash product if any

 if(isset($data['flash'])){
     
     
 $row = $r->fetch_assoc();
 
 $p = $row['productName'];
 
 $pid = $row['pid'];
 
 $time="";
 
 $c = $_SESSION['currency'];

     
 $price = $row['nairaPrice'];
$curr = "₦";
 
 $price2 = $row['dollarPrice'];
     $curr2 = "$";

 
     
     $price3 = $row['poundsPrice'];
     $curr3 = "£";
 

 $expiry = $row['expiry'];

$rem = $expiry - time();//time remaining

if($rem < 1 ){
    
       
 $params = array("ss",["expired",$pid]);
 
$db = $this->CustomDB->update("products","SET status=? WHERE pid=?",$params);   
    
    
  $time = "0:0:0:0";//timer for elapsed time.
}

else {
$converter = $this->Functions->seconds_converter($rem);

$days = $converter["days"];


$hours =  $converter["hours"];

$mins =  $converter["minutes"];

$secs = $converter["seconds"];

$counter = $days.":".$hours.":".$mins.":".$secs; 

if(!isset($counter)){
    
    $counter = "0:0:0:0";
}

 $pic = $row['pic1'];
 
echo'  

  <section class="block box">
<div class="mt-4">
   <div id="flash" class ="notification  is-light">
       <p style="display:none;" class="button ism-light" id="loading">Loading...</p>

<div class="columns">
<div class="column is-half">
        <p style="border-radius:12px 34px" class="button is-danger">Flash Sales!!!</p>
        
            <span class="tag is-light is-outlined" id="productName">'.$p.'</span>    
        </div>
        
        <div style="font-weight:bold; font-size:19px;" class="notification is-info is-light " class="column is-half ml-6 timer">
        
  <span class="clk tag is-link is-light">
    <span style="display:none;" class="z">0</span>
  
  <h3 id="d">0</h3>D </span>
               
  <span class=" tag is-link is-light"> 
    <span style="display:none;" class="z2">0</span>
  
  <h3 id="h">0</h3>H </span>
        
  <span class=" tag is-link is-light"> 
  
    <span style="display:none;" class="z3">0</span>
  <h3 id="m">0</h3>M </span> 
  
    <span class=" tag is-link is-light">
    <span style="display:none;" class="z4">0</span>
    <h3 id="s">0</h3>
    S </span>
  
        
        </div>
        
        
        </div>

   <figure class="image is-3by2">

   <img class="image" src="../../../../public/products/'.$pic.'">
  
       
 <input type="text" id="pid" value="'.$pid.'" hidden>
      
   </figure>



   
 <input id="flash-timer" type="text" value="'.$counter.'" hidden>
 </div> 
 
 
    <div id ="flashcart" class="button baseColor white is-small mt-3">Add to cart</div>
    
    <div id="flash-amount" class="button is-link is-light">'.$curr.number_format($price).'</div>
    
    
     
    <div id="flash-amount2" style="display: none;" class="button is-link is-light">'.$curr2.number_format($price2).'</div>
    
    
        
    <div id="flash-amount3"  style="display: none;"  class="button is-link is-light">'.$curr3.number_format($price3).'</div>
    
</div>
 
  </section>
    
';
}

}//isset data['flash']
?>

      

 


<!--products-->

<section>
<div class="container is-fluid">
    
  
  <div style='text-align:center;' class="notification is-link is-light mr-1 notice">
   <span class='fa fa-bell-o'></span> 
   Click on the product to view more information about it..
      
  </div>
  
 <div id="cache">
  
 <div class="tag is-link block" id="category">Category/All</div>
  
   <div id="products" class="columns is-mobile products">
      
      
      
     <!--Product 1-->  
 <?php
 
 $i = 1;  //for generation of id for each products <span class=" fa fa-check">
 
 
   //load products.
  while($row = $res->fetch_assoc()){

 
 $id = 'v'.$i; //first id
 
     $default_price = $row['nairaPrice'];
         $curr ="₦";//base currency for old price
       $curr2="$";//dollar currency
       $curr3="£";//pounds currency
         
  $product = $row['productName'];//product name
  
 $pic = $row['pic1']; //main picture
 
  $price = (int)($row['nairaPrice']);//price in nigerian naira
  
 $discount = (int) ($row['discount']); // % discount
 
 
 $discount = $discount/100;
 
 $old_price = number_format(($discount * $price) + $price);// price sold before in naira
 
 
 //set dollar price details
  $price2 = (int)($row['dollarPrice']);//price in dollars
  
 $old_price2 = number_format(($discount * $price2) + $price2,1); //price sold before in dollars 
  
  
   $price3 = (int)($row['poundsPrice']);  //price in pounds
 
 $old_price3 = number_format(($discount * $price3) + $price3); //price sold before in pounds
 
 //Dont show old prices if no discount is on a product
 if($discount == 0){
     
 $old_price=$old_price2=$old_price3="";    
  $curr=$curr2=$curr3=""; 
  
 }
 


  $product_id = $row['pid'];
 
//ratings
  $rating =$row['rating'];
  
     $rating = round($rating/0.5,0)*0.5;
 
 
 if($rating == "0"){
 $r = $zero_star;    
 }
 elseif($rating =="0.5" ){
     $r= $zero_half;
 }
 elseif($rating=="1"){
     $r= $one;
 }
 elseif($rating=="1.5"){
     $r= $one_half;
 }
 elseif($rating=="2"){
     $r= $two;
 }
 elseif($rating=="2.5"){
     $r= $two_half;
 }
 elseif($rating=="3"){
     $r= $three;
 }
 elseif($rating=="3.5"){
     $r= $three_half;
 }
 elseif($rating=="4"){
     $r= $four;
 }
 elseif($rating=="4.5"){
     $r= $four_half;
 }
 elseif($rating=="5"){
     $r= $five;
 }
 else{
    
$r =$zero;
 
 }
 
 
 
 echo" <div class='column is-half-mobile is-one-quarter-desktop is-one-third-tablet'>
   <div  class='box mb-2'>
      <div id='get_details'>
   <div style='font-size:9px; font-weight:bold; text-align:center; border-radius:50%;' class='button is-success is-light'><span style='display:none;' id='$id' class='fa fa-check'></span> </div>

 <p style='font-size:10px; font-weight:bold; text-transform:uppercase;' id='productName' class='block '>$product</p> 
 
 <input id='pid' type='text' value='".$product_id." ' hidden>
 
   <p style='display:none;' id='loading' class='tag is-link is-light'>
    Loading...
   <span class='button is-loading is-small is-outlined is-link'> </span> </p> 
   
   <figure class='image centerPix'>
       
   <img class='image is-64x64 ' src='../../../public/products/".$pic."'>   
  
   </figure>  
   
  <!--price-->
<div id='price' class='price  mt-2'>
     <span id='currency'>₦</span>".number_format($price)."
</div> 


   <!--old price-->
   <div id='old_price' class='price old_price'>
     <span id='dcurrency'> ".$curr."</span>".$old_price."
</div>  
   
   
     <!--price dollars-->
<div id='price2' class='price  mt-2'>
     <span id='currency'>$</span>
     ".number_format($price2)."
</div> 


   <!--old price dollars-->
   <div id='old_price2' class='price old_price'>
     <span id='dcurrency'>$curr2</span>".$old_price2."
</div>  
   
   
   
     <!--price pounds-->
<div id='price3' class='price  mt-2'>
     <span id='currency'>£</span>".number_format($price3)."
</div> 


   <!--old price pounds-->
   <div id='old_price3' class='price old_price'>
     <span id='dcurrency'>$curr3</span>".$old_price3."
</div>  
   
   </div>  <!--get details-->
   
   
   
   <!--rating-->
 <div class='rating block'>


".$r."

 <!--cart button-->
  <span id='cartbtn' class='button is-small baseColor white'>Add to cart  
      </span>
</div> <!--rating-->

<!--  quantity
<div class='field'>
<div class='control has-icons-left has-icons-right'>
<span id='minus' style='width:12px; height:12px;' class='tag is-link fa fa-minus mb-1'></span> <input style='width:20%; display:'none; class='input is-link is-small ' type='number' value='0' id='qty'><span id='plus' style='width:12px; height:12px' class='tag is-link fa fa-plus '></span>
</div></div>
-->


</div>  <!--box-->

</div> <!--column-->
"
;        
 $i+=1;
 
}
  ?>   
     

</div>  <!--columns-->

</div>


<input id="auth" value="<?=$_SESSION['pay_auth'];?>" hidden>


<!--login modal-->

<div id="login-modal" class="modal">
    
    
  <div class="modal-background"></div>
  
  <div class="modal-card">
    <header class="modal-card-head">
      <p  class="button modal-card-title baseColor white ">Login | MT Stores</p>
      <button id="login-close" class="button is-danger delete" aria-label="close"></button>
    </header>
    <section class="modal-card-body">
      
        
      <!-- Content ... -->
     
 
     <div class="notification is-light is-link">Login to explore all our features.
  </div>  
  
  <div class="box">
<div style="display:flex; justify-content:center; flex-flow:row wrap;" class="columns is-mobile">
    
    <div style="display:none;" class="notification is-danger is-light" id="login-error"> </div>
    
    
    <!--form-->
    
    <form id="log-form">
<div  class="column">
   <div class="control has-icons-right">  
 <input id="l_email" class="input is-link " name="login_email" type="email" placeholder="Email..." required><span class="icon is-right baseColor white"><i class="fa fa-envelope"></i></span>
     </div>
     </div>
   
      
      
     <div class="column">  
     <div class="control has-icons-right">
<input id="l_password" class="input is-link " name="login_password" type="password" placeholder="Password" required><span class="icon is-right baseColor white"><i class="fa fa-key"></i></span>
     </div>
     </div>    
     
  
 <input name="form_auth" id="l_auth" type="text" value="" hidden>
 
 
       <button type="submit" name="login" id="login" class="button is-link is-rounded block mb-1"> Login <span class="ml-2 fa fa-arrow-right"></span> 
    </button>  
     <a href="../../../user/reset_password" class="tag is-warning ml-5" id="forgot-pass">Forgot password</a>
     </form>
     
 </div>
 </div>
     
     </section>
</div>
</div>



<!-- end of login modal-->






 <!-- modal for registration-->

<div id="reg-modal" class="modal">
    
    
  <div class="modal-background"></div>
  
  <div class="modal-card">
    <header class="modal-card-head">
      <p  class="button modal-card-title baseColor white ">Register | MT Stores</p>
      <button id="reg-close" class="button is-danger delete" aria-label="close"></button>
    </header>
    <section class="modal-card-body">
      
        
      <!-- Content ... -->
      <div >Sign in if you have account</div>
 <button id="sign-in" class="tag is-success">Sign In</button>    
 
     <div class="notification is-light is-link">You are not logged in. Register quickly and access our features
  </div>  
  
  <div class="box">
<div style="display:flex; justify-content:center; flex-flow:row wrap;" class="columns is-mobile">
    
    <div style="display:none;" class="notification is-danger is-light" id="error"> </div>
    
    
    <!--form-->
    
    <form id="reg-form">
<div  class="column">
   <div class="control has-icons-right">  
 <input id="email" class="input is-link " name="email" type="email" placeholder="Email..." required><span class="icon is-right baseColor white"><i class="fa fa-envelope"></i></span>
     </div>
     </div>
     
   <!--hhh-->  
     
   <div class="column">
   <div class="control has-icons-right">  
 <input id="Phone" class="input is-link" name="phone" type="tel" placeholder="Phone No.." required><span class="icon is-right baseColor white"><i class="fa fa-phone"></i></span>
     </div>
     </div>
       
        <!--hhh-->  
     
   <div class="column">
   <div class="control has-icons-right">  
 <input id="fname" class="input is-link " name="fname" type="text" placeholder="Surname" required><span class="icon is-right baseColor white"><i class="fa fa-user"></i></span>
     </div>
     </div>
      
      
     <div class="column">  
     <div class="control has-icons-right">
<input id="oname" class="input is-link " name="oname" type="text" placeholder="Other Names" required><span class="icon is-right baseColor white"><i class="fa fa-user"></i></span>
     </div>
     </div>   
     
     
         <div class="column">  
     <div class="control has-icons-right">
<input id="password" class="input is-link " name="password" type="password" placeholder="Password" required><span class="icon is-right baseColor white"><i class="fa fa-key"></i></span>
     </div>
     </div>    
     
        <p>Choose Country</p>
     
   <div class="select is-link"> 

 <select id="country" name="country" required>
     

    
    <option value="+61">Australia</option>
    
    <option value="+55">Brazil</option>
    <option value="+237">Cameroon</option>
    <option value="+225">Cote D'Ivoire</option>
    <option value="+20">Egypt</option>
    <option value="+33">France</option>
    <option value="+49">Germany</option>
    <option value="+233">Ghana</option>
    <option value="+91">India</option>
    <option value="+39">Italy</option>
    <option value="+254">Kenya</option>
    <option value="+52">Mexico</option>
    <option value="+234">Nigeria</option>
    <option value="+351">Portugal</option>
    <option value="+34">Spain</option>
    <option value="+44">United Kingdom</option>
    <option value="+1">United States</option>
</select>      
     </div>
     </div>
 </div>   <!--columns-->
 
 

 
       <button type="submit" name="register" id="register" class="button is-link is-rounded block mb-1"> Register <span class="ml-2 fa fa-arrow-right"></span> 
    </button>  
     
</div>

</div>
</form>
</section>
<!-- end of modal for registration-->








  <!-- modal for basket-->

<div id="basket" class="modal">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p  class="button modal-card-title baseColor white ">My basket</p>
      <button id="basket_close" class="button is-danger delete" aria-label="close"></button>
    </header>
    <section class="modal-card-body">
  <label class="tag is-link is-light">Delivery Method</label>      
    <div class="control">
        <input id="dtype" type="radio" class="mr-1 block" name="del" value='door'>Door delivery (6% of total )
        </div>
        
 <div class="control">
        <input id="dtype" type="radio" class="mr-1 mb-4 block" name="del" value="bus park">Bus Stop (4% of total)
       </div>      
        
     <div class="columns column is-half"> 
  <p class="tag is-light is-link">Location</p> 
  
  <input style="margin:auto;" class="input is-link block" id="location" name="location" type="text" placeholder="Location" required>
        </div>
      <!-- Content ... -->
     
 <div id="basket_table" class="table-container">
      
     
     <div class="notification is-light is-link">Delivery fees will be added during payment!!! 
  </div>  
    <div style="display:none;" id="paymsg" class="notification is-success"> </div>
<table class="table table is-bordered is-striped is-hoverable is-fullwidth">
    <!-- Your table content -->
 <thead>
     
   <tr>
  <th></th>
  <th>Product</th> 
  <th>id</th>
  <th>Price/Unit <span id="c"></span></th>
    <th>Quantity</th>   
    <th>+-Qty</th>
   </tr>  
     </thead>
     
  <tbody>
      
      <!--cart details here-->
        </tbody>
      
   <tfoot>
  <td></td>     
    <td></td>   
 <td class="bold">Total</td> <td><p id="total" class="button is-small baseColor white">--</p></td>     
   </tfoot>   

</table>
     
 </div>   
       <div id="pay" class=" button is-link is-rounded block mb-1">Pay
       <span class="ml-2 fa fa-credit-card"></span> 
      </div>   
     
</div>
</div>

<!-- end of modal for basket-->







  <!-- modal for product info-->

<div id="info-modal" class="modal ">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p style="font-size:12px" id="product_name" class="button is-small modal-card-title baseColor white "></p>
      <button id="info-close" class="button is-danger delete" aria-label="close"></button>
    </header>
    
    <input id="info-pid" type="text" hidden>
    
    <section class="modal-card-body">
        
        
      <!-- Content ... -->
      <p>Touch image to view full size</p>
      
 <!--rate -->
 
 <div class="tag is-warning block">Rate Product</div>
      
<div class="control has-icons-left has-icons-right">
  <div class="select is-small is-success">
    <select name="rate" id="rate-value">
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
     <option value="4">4</option>
   <option value="5">5</option>       
    </select>
  </div>
  <span class="icon is-left">
    <i class="fa fa-star-o"></i>
  </span>
</div>
      <button class="button baseColor white is-small is-rounded" id="submit-rating">Rate</button>
   <!--main product pic-->   
      
      <div style="display:flex; justify-content:center;">
 <figure class="image is-128x128">
   <img  class="image" id="pic1" src="">
     
 </figure> </div>    
      
  <!--description-->
 <div id="desc" class="container is-fluid box">
     
   
 </div>
  
      
  <!--other pic 2-->
  
 <div class="columns is-mobile">
 <div id="pic2" class="column is-one-half-mobile notification is-light is-link mr-2">
   <figure class="image  is-128x128">
   <img  class="image" id="p2" src="../../../public/products/p4.pngg">
     
 </figure>    
     
     
 </div>    
     <!--pic 3-->
 <div id="pic3" class="column is-one-half-mobile notification is-light is-link">
   <figure class="image is-128x128">
   <img  class="image" id="p3" src="../../../public/products/p6.png">
     
 </figure>    
     
     
 </div>         
     
     
 </div>  <!--columns-->   
      
      
      
    
    
    <div class="modal-card-foott">
      <button id="modal_price" class="button is-link ">#340</button>
      <p class="notification is-warning is-light">
  Click photo to view larger size 
      </p>
    </div>
    </section>
  </div>
</div>





  <!--modal end-->
  
  
  
  
  
  
  
  
  


</div> <!--container-->
</section>


<div class="block ml-2">
<p class="page">Page</p>

<button class="link-btn button is-link page white" id="page" >1</button>
</div>

<div class="container is-fluid block mt-4">
    <div class="columns is-mobile">

<!-- show less -->

<div  class=" column is-one-quarter mr-4" ><button id="less" class="link-btn white button is-link is-rounded  is-small"><span class="fa fa-arrow-left mr-1"></span> Less</button></div>


<!--load more button-->


        
<div  class="column is-one-quarter mr-4" ><button id="more" class="link-btn white button is-link is-rounded  is-small">More <span class="fa fa-arrow-right ml-1"></span></button></div>





<div class="column  notification is-success is-light">
 <p>Click more to load more products or go to navigation and browse by categories. You can also search products in the search bar</p>   
    
</div>
    
    </div>
</div>





<!--bottom nav-->

<nav class="navbar is-fixed-bottom is-dark ">
       <div style="display:flex; flex-flow:row wrap;" class="container is-fluid">
    
    
     

<!-- bottom nav item 1 -->
  
  <p id="check_items" class="navbar-item white">
<span style="font-size:28px; " id="bag"  class=""><i class="fa fa-light fa-bag-shopping"></i></span>
<span id="items" style="border-radius:70px 20px 5px 9px;" class="button baseColor white is-small">0</span>
   </p>
    
    
   <p id="online-info" class=""></p> 
    

<!-- bottom nav item 2 -->

  <a href="../../../user/profile" class="white ml-6 mt-2">
<span style="font-size:28px;" id="profile"  class=""><i class="fa fa-user"></i></span>
<p class="  <?php 
if(isset($_SESSION['user'])){ echo' '.$_SESSION['login_status'];}  ?> " id="online-badge"></p>
   </a>
    
    
<!-- bottom nav item 3 -->

  <a class="white ml-6 mt-2">
<span style="font-size:28px;" id="logout"  class=""><i class="fa fa-sign-out"></i></span>
   </a>
    




    </div>
    </nav>

<!-- info -->
   <div class="container ">
     <div class="column">
  <div style="border-radius:70px 50px 20px 15px;" class="box baseColor white"> 
  
      
  <p class="subtitle is-6 white">
      <span class="title is-5 white">MT Stores</span> offers you a wide variety of goods ranging from electronics,toys,bags,footwears,eyewears,jewelry and so on. With our years of experience in ecommerce, we deliver to you fast and convenient.
  </p>
  
    <p><i class="fa fa-home"> </i> Lagos:Opposite Sanrab filling station, no43</p>
  
      
  <p><i class="fa fa-mobile mt-2"> </i> 07014443158</p>
  
  
  <p><i class="fa fa-envelope-o mt-2"> </i> admin@mtstorez.com</p>
  
      </span> </div>   
      
      
</div>



</body>
</html>