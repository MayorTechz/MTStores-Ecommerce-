<?php

/*Developed By Abel Mayowa 2022*/

/*This is the class that holds the logic behind all the functioons of the store home paghe store home.*/

/*Some functions used here can be found inside engine folder under Functions.php You can modify to your requirement or even add yours.*/

/*Any features for the store home should be added here */

class Store extends Trongate
{
    /*Home function is used to load the home page and pass some datas to the frontend*/

    function home()
    {
       

        //set the default currency into session cookie on page load. This is to allow us remember the currency the user is browsing the site with
        if (!isset($_SESSION["currency"]) || $_SESSION["currency"] == "") {
            $_SESSION["currency"] = "naira";
        }
        $data = []; //array to save data

        $type = "normal"; //load products that are not flash sales that last for a while.

        $params = ["s", [$type]]; //binding variable and its value

        $result = $this->CustomDB->select(
            "products",
            "WHERE type=? ORDER BY id DESC LIMIT 16",
            $params
        ); //query to fetch products from db

        $data["res"] = $result; //results from db saved into data array as declared earlier

        $token = bin2hex(random_bytes(50)); //token to check for csrf

        $_SESSION["form_token"] = $token;

        //get random flash product from products table
        $params = ["ss", ["active", "flash"]];

        $db = $this->CustomDB->select(
            "products",
            "WHERE status=? AND type=? ORDER BY RAND() LIMIT 1",
            $params
        );

        if ($db->num_rows >= 1) {
            $data["flash"] = $db;
        }
        
        
        //get 2 random products as featured
        
        
        $type = "normal";
        $params = ["s",[$type]];
        
     $featured= $this->CustomDB->select(
            "products",
            "WHERE type=? ORDER BY RAND() LIMIT 4",
            $params
        );
        
        

        if ($featured->num_rows >= 1) {
            
 $h ='
 
<section class="section mt-3">
 
<div style="flex-flow:row wrap;" id="featured" class="columns is-mobile">'; 

while($row = $featured->fetch_assoc()){
   
  //$row = $featured->fetch_assoc();
  
 $p = $row['productName'];
 
 $pid = $row['pid'];
 
 $pic = $row['pic1'];
 
 $time="";
 
 $c = $_SESSION['currency'];

     
 $price = $row['nairaPrice'];
$curr = "₦";
 
 $price2 = $row['dollarPrice'];
 
 
     $curr2 = "$";

 
     $price3 = $row['poundsPrice'];
     
     $curr3 = "£";     
            
            

     $h.='
  
   
 <div class="column is-half">
<div id="fp" class="fp">

<p style="border-radius:12px 34px; font-size:10px;" class="button is-link">Featured</p>
        
        
       
  <span class="tag is-light is-outlined" id="fproductName">'.$p.'</span>    
  
<span id="fp-amount" style=" " class="tag is-primary is-light price">'.$curr.number_format($price).'</span>


<span id="fp-amount2" style="display:nonee;" class="tag is-primary is-light price2">'.$curr2.number_format($price2).'</span>

<span id="fp-amount3" style="display:nonee;" class="tag is-primary is-light price3">'.$curr3.number_format($price3).'</span>
  
 <p style="display:none;" class="button is-light" id="loading">Loading...</p>

   <figure class="image is-square">

   <img id="f"  class="fpimg image is-roundedd" src="../../../../public/products/'.$pic.'">
  
       
 <input type="text" id="fppid" value="'.$pid.'" hidden>
      
   </figure>
   
  </div>
  
    <span style="color:white;" id ="fpcart" class="button baseColor white is-small mt-3 is-light"><i class="fa fa-plus"></i></span>
    
    
    
    
    </div>';
    
}//while

$h.='</div>
</section>';
 
         
        }//num rows
        
       
 $data['featured'] = $h;
         $_SESSION['category_auth']  = bin2hex(random_bytes(30));
         
         $data['category_auth'] = $_SESSION['category_auth'];

        $this->view("home", $data); //load home.php and pass data to be used.
    } //end of function home

    /*This function is for saving the users choice of currency which overrides the initial currency saved on home pageload*/

    function save_currency()
    {
        session_regenerate_id();
        if (isset($_SESSION["currency"])) {
            unset($_SESSION["currency"]);
        }

        $ch = $_GET["currency_choice"];

        $_SESSION["currency"] = $ch;

        $c = "";

        if ($c == "naira") {
            $c = "₦";
        } elseif ($c == "pounds") {
            $c = "£";
        } else {
            $c = '$';
        }

        $reply = ["response" => $c];
    } //end of currency change

    /*This is used to load a product information like price,descriptions,picture etc */
    function product_info()
    {
        $pid = $_GET["product_id"]; //product id of the users clicked product

        $pid = $this->Functions->clean($pid, "", ""); //clean data coming in

        $curr = $_GET["curr"]; //current currency the user is browsing the site with

        $curr = $this->Functions->clean($curr, "", "");

        $price = ""; //column name of price to be fetched

        if ($curr == "₦") {
            $price = "nairaPrice";
        }

        if ($curr == "$") {
            $price = "dollarPrice";
        }

        if ($curr == "£") {
            $price = "poundsPrice";
        }

        $params = ["s", [$pid]]; //binding variable with their values

        $res = $this->CustomDB->select("products", "WHERE pid=?", $params); //fetch data from db

        $row = $res->fetch_assoc();

        //needed data
        $product = $row["productName"];

        $pic1 = $row["pic1"];

        $pic2 = $row["pic2"];

        $pic3 = $row["pic3"];

        $description = $row["description"];

        $p = $row[$price];

        //send all datas to frontend as the request was through ajax.

        $reply = [
            "product" => $product,
            "pic1" => $pic1,
            "pic2" => $pic2,
            "pic3" => $pic3,
            "description" => $description,
            "curr" => $curr,
            "price" => $p,
        ];

        exit(json_encode($reply));
    } //product_info

    /*This checks for login status of a user. Users must be registered and logged in before payment can be made to be able to track transaction details*/

    function check_login()
    {
        $msg = "0"; //failure message sent to frontend via ajax

        if (!isset($_SESSION["user"])) {
            echo $msg;

            exit();
        } else {
            echo "1"; //success message

            exit();
        }
    } //check login

    /*This is used to calculate all items picked by user,totap amount of all items calculated olus delivery and payment gateway token gotten from db*/

    public function pay()
    {
        session_regenerate_id();
        //get timezone of current user.

        $ip = $_SERVER["REMOTE_ADDR"];

        $set_time = $this->Functions->set_tz($ip); //timezone set

        $token = $_SESSION["pay_auth"]; //for csrf check

        $form_auth = $_POST["auth"]; //token sent by user from form

        if ($token !== $form_auth) {
            exit("access denied!!!");
        }

        $delivery = ""; //delivery type

        $recpt = ""; //reciept containing payment data

        $c_phone = ""; //phone number of customer

        //payment gate way verification  needed to verify payment before accrediting and payment details to db
        if (!isset($_POST["verify"])) {
            $pid = $this->Functions->clean($_POST["pid"], "", ""); //NB:Last character in the string is the quantity of the product ordered for.

            $delivery = $this->Functions->clean($_POST["delivery"], "", ""); //delivery type

            $location = $_POST["location"];

            $location = $this->Functions->clean($location, "", "");

            //ensure location is filled
            if (!isset($location)) {
                exit(json_encode("Please fill your delivery location!!"));
            }

            $email = $this->Functions->clean($_SESSION["user"], "", "");

            //get user phone no
            $params = ["s", [$email]];

            $db = $this->CustomDB->select("user", " WHERE email=? ", $params);

            $row = $db->fetch_assoc();

            $c_phone = $row["phoneNo"]; //customer phone number

            //get payment gateway key
            $param = ["s", ["gateway"]];
            $r = $this->CustomDB->select("token", "WHERE type=? ", $param);

            $row = $r->fetch_assoc();

            $pay_token = $row["key1"]; //payment gateway key key

            $ref = bin2hex(random_bytes(5)) . uniqid(); //payment reference.
            $_SESSION["reference"] = $ref;

            $set_tz = $this->Functions->set_tz($ip); //timezone set.

            $tz = date_default_timezone_get();

            $d = date("d/m/Y"); //payment day

            $time = date("h:i:sa");

            $date = $d . " " . $time . " GMT:" . $tz; //payment time

            $_SESSION["date"] = $date;

            $currency = $this->Functions->clean($_POST["curr"], "", "");
            if ($currency == "₦") {
                $currency = "nairaPrice";

                $c = "NGN"; //data to be sent to gateway
            } elseif ($currency == "$") {
                $currency = "dollarPrice";

                $c = "USD"; //data to be sent to gateway
            } elseif ($currency == "£") {
                $currency = "poundsPrice";

                $c = "GBP";
            } else {
                exit(json_encode("Payment Failed"));
            }

            $pid = explode("-", $pid); //set each product id with corresponding quantity into an array

            //get the prices of each item and sum

            $s = 0; //initial sum of product.
            $sum;

            foreach ($pid as $p) {
                $quantity = substr($p, -1); //qty of the product. remember the $pid last character in the string is the quantity

                $p = substr_replace($p, "", -1); //remove the quantity leaving us only with the product id

                //check if product is a flash product and is expired
                $type = "flash";

                $params = ["ss", [$p, $type]];

                $db = $this->CustomDB->select(
                    "products",
                    "WHERE pid=? AND type=?",
                    $params
                );

                if ($db->num_rows >= 1) {
                    $row = $db->fetch_assoc();

                    $expiry = $row["expiry"];
                    $pname = $row["productName"];

                    if ($expiry - time() < 1) {
                        exit(
                            json_encode(
                                "<strong>" .
                                    $pname .
                                    "</strong> is a flash product that is expired. Remove and try payment again"
                            )
                        );
                    }
                }

                //get product details needed for receipts

                $params = ["s", [$p]];

                $res = $this->CustomDB->select(
                    "products",
                    "WHERE pid=?",
                    $params
                );

                while ($row = $res->fetch_assoc()) {
                    $product = $row["pid"];

                    $pname = $row["productName"];

                    $price = $row[$currency];

                    $total = $price * $quantity; //total amount with respect to quantity

                    $s += $total;

                    $sum = $s; //put total into $sum variable.

                    $recpt .=
                        $pname .
                        "*" .
                        $quantity .
                        "*" .
                        $currency .
                        "*" .
                        $total .
                        ","; // all products with total amount and corresponding quantity and total sum separated by and(&) to be used for receipts.
                } //while
            } //foreach

            //get the total amount to be paid including delivery fees

            if ($delivery == "bus park") {
                $sum = $sum + (4 / 100) * $sum;
            } elseif ($delivery == "door") {
                $sum = $sum + (6 / 100) * $sum;
            } else {
                exit(json_encode("Choose a delivery method"));
            }

            if ($sum > 0) {
                $msg = "1"; //success message
            }

            $_SESSION["receipt"] = $recpt; //put receipt in session in case payment gets verified

            $_SESSION["receipt"] = $recpt;

            $_SESSION["sum"] = $sum; //total amount to be paid

            $_SESSION["delivery"] = $delivery; //delivery type

            $_SESSION["phone"] = $c_phone; //user phone number

            $_SESSION["location"] = $location; //delivery location

            //datas to be sent to frontend to payment gateway
            $response = [
                "status" => $msg,
                "currency" => $c,
                "amount" => $sum,
                "reference" => $ref,
                "token" => $pay_token,
                "email" => $email,
                "order" => $pid,
                "phone" => $c_phone,
            ];

            exit(json_encode($response));

            //}//func pay
        }

        //Verify payment before saving into db.

        if (isset($_POST["verify"])) {
            //get timezone of current user.
            $ip = $_SERVER["REMOTE_ADDR"]; // user's IP address

            $set = $this->Functions->set_tz($ip);

            $new_tz = date_default_timezone_get(); //current timezone

            $user = $_SESSION["user"];

            $reference = $_POST["reference"];

            $reference = $this->Functions->clean($reference, "", "");

            $_SESSION["reference"] = $reference; //for use if payment was through stripe

            $d = date("d/m/Y"); //payment day

            $time = date("h:i:sa");

            $date = $d . " " . $time . " GMT:" . $new_tz; //payment time

            //send request to paystack payment gateway to get transaction status to be sure user is debited
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL =>
                    "https://api.paystack.co/transaction/verify/" . $reference,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer sk_test_502ce66b84ddbb2c582e6bdaf666bd29927273f2",
                    "Cache-Control: no-cache",
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                //curl error

                echo "Error occurred!! please try again later. #:" . $err;
                exit();
            } else {
                //verification endpoint success
                $dec = json_decode($response);

                $reply = $dec->data->status;

                //payment details

                $receipt = $_SESSION["receipt"];

                $total_paid = $_SESSION["sum"];

                $c_phone = $_SESSION["phone"];

                $delivery = $_SESSION["delivery"];

                $location = $_SESSION["location"];

                $attended_to_by = "";

                if ($reply == "success") {
                    $status = "check";

                    //incase of multiple references payment ,gateway will handle that so no worries about that.

                    //insert to db transactions

                    $var = [
                        $user,
                        $date,
                        $reference,
                        $receipt,
                        $total_paid,
                        $status,
                        $c_phone,
                        $delivery,
                        $attended_to_by,
                        $location,
                    ];

                    $p = ["ssssssssss", "?,?,?,?,?,?,?,?,?,?", $var];

                    $i = $this->CustomDB->insert(
                        "transactions",
                        "user,date,reference,receipt,totalPaid,status,c_phone,delivery_type,attended_to_by,delivery_location",
                        $p
                    );

                    unset($total_paid);

                    unset($c_phone);

                    unset($delivery);

                    unset($receipt);

                    unset($location);
                }

                //if success verification
                else {
                    $status = "close";

                    //insert to db failed transactions.

                    $receipt = ""; //no product data for failed payment

                    $total_paid = "-"; //user not debited

                    $c_phone = "";

                    $delivery = "";

                    $var = [
                        $user,
                        $date,
                        $reference,
                        $receipt,
                        $total_paid,
                        $status,
                        $c_phone,
                        $delivery,
                    ];

                    $p = ["ssssssss", "?,?,?,?,?,?,?,?", $var];

                    $i = $this->CustomDB->insert(
                        "transactions",
                        "user,date,reference,receipt,totalPaid,status,c_phone,delivery_type",
                        $p
                    );

                    echo "failed";

                    exit();
                } //user not debited
            } //else no curl error.
        } //verify
    } //pay

    function stripe_pay()
    {
        session_regenerate_id();

        //receives succesful payment and save transaction details to db

        //payment details

        $user = $_SESSION["user"];

        $reference = $_SESSION["reference"];

        $date = $_SESSION["date"];

        $receipt = $_SESSION["receipt"];

        $total_paid = $_SESSION["sum"];

        $c_phone = $_SESSION["phone"];

        $delivery = $_SESSION["delivery"];

        $location = $_SESSION["location"];

        $attended_to_by = "";

        $status = "check";

        //insert to db transactions

        $var = [
            $user,
            $date,
            $reference,
            $receipt,
            $total_paid,
            $status,
            $c_phone,
            $delivery,
            $attended_to_by,
            $location,
        ];

        $p = ["ssssssssss", "?,?,?,?,?,?,?,?,?,?", $var];

        $i = $this->CustomDB->insert(
            "transactions",
            "user,date,reference,receipt,totalPaid,status,c_phone,delivery_type,attended_to_by,delivery_location",
            $p
        );

        unset($total_paid);

        unset($c_phone);

        unset($delivery);

        unset($receipt);

        unset($location);

        unset($date);

        unset($reference);

        unset($total_paid);

        unset($c_phone);

        unset($delivery);

        unset($receipt);

        unset($location);

        unset($date);

        unset($reference);

        if ($i->affected_rows >= 1) {
            $_SESSION["response"] = "success"; //to display success payment message.
            
            $_SESSION['stripe'] = 'completed';

            header("location:../");
        }

        exit();
    }

    /*This is used for handling the search of products at the front end. The search is according to the currency used by users */

    function search()
    {
        $results = "";

        $keyword = $this->Functions->clean($_GET["keyword"], "", "");

        $params = ["s", [$keyword . "%"]];

        $res = $this->CustomDB->select(
            "products",
            "WHERE productName LIKE ?",
            $params
        );

        while ($row = $res->fetch_assoc()) {
            $product = $row["productName"];

            $disc = $row["discount"];

            $price = $row["nairaPrice"];

            $old_price = $price + ($disc / 100) * $price;

            $price2 = $row["dollarPrice"];

            $old_price2 = $price2 + ($disc / 100) * $price2;

            $price3 = $row["poundsPrice"];

            $old_price3 = $price3 + ($disc / 100) * $price3;

            $pid = $row["pid"];

            $pic = $row["pic1"];

            $s = $_SESSION["currency"];

            $hide = "  style='display:none;'  ";

            //NOTE:- Do not change the position of the $results string because its position is used to get some values out of the which will be used in processing the add to cart event for products that appear on search results at the frontend. If you must alter this string, then you will have to adjust some part of the js codes in "assets/js/home.js" that handles the addition of products on search results to cart. In summary, adjust the $results strings only if you knw what you are doing!!!

            //search done and currency choice of user is naira
            if ($s == "naira") {
                $results .= "
  <!--Product 1-->
  <div class='column'>
  <div class='box mb-2'>
  <div id='get_details'>
  <div style='font-size:9px; font-weight:bold; text-align:center; border-radius:50%;' class='button is-success is-light'><span style='display:none;' id='v1' class='fa fa-check'></span> </div>
 <p style='font-size:10px; font-weight:bold; text-transform:uppercase;' id='productName' class='block '>$product</p>
 
<input id='pid' type='text' value='$pid' hidden>
 
 <p style='display:none;' id='loading' class='tag is-link is-light'>   Loading...
 <span class='button is-loading is-small is-outlined is-link'> </span> </p>
 <figure class='image centerPix'> <img class='image is-64x64 ' src='../../../public/products/$pic'></figure>
 
  <!--price-->
  <div id='price' class='price  mt-2'>      <span id='currency'>₦</span>$price</div>
 
 
  <!--old price-->
<div id='old_price' class='price '>  <span id='dcurrency'>₦ </span>$old_price
</div>
 
 <!--price dollars-->
 <div $hide id='price2' class='price  mt-2'>
 <span id='currency'></span>
   </div>
   
 <!--old price dollars-->
 <div $hide id='old_price2' class='price old_price'>
  <span id='dcurrency'></span></div>
 
<!--price pounds-->
 <div $hide id='price3' class='price  mt-2'>
 <span id='currency'></span></div>
 
 
  <!--old price pounds-->
   <div $hide id='old_price3' class='price old_price'>
 <span id='dcurrency'></span>
  </div>
 </div>
  <!--get details-->  
   
   
    <span id='cartbtn' class='button is-small baseColor white'>Add to cart</span>
   
 </div><!--box-->
 

 </div>  <!--column-->
 <hr>

  ";
            } //currency is naira

            //currency is in dollar

            if ($s == "dollar") {
                $results .= "
  <!--Product -->
  <div class='column'>
  <div class='box mb-2'>
  <div id='get_details'>
  <div style='font-size:9px; font-weight:bold; text-align:center; border-radius:50%;' class='button is-success is-light'><span style='display:none;' id='v1' class='fa fa-check'></span> </div>
 <p style='font-size:10px; font-weight:bold; text-transform:uppercase;' id='productName' class='block '>$product</p>
 
<input id='pid' type='text' value='$pid' hidden>
 
 <p style='display:none;' id='loading' class='tag is-link is-light'>   Loading...
 <span class='button is-loading is-small is-outlined is-link'> </span> </p>
 <figure class='image centerPix'> <img class='image is-128x128 ' src='../../../public/products/$pic'></figure>
 
  <!--price-->
  <div $hide id='price' class='price  mt-2'>      <span id='currency'>₦</span>$price</div>
 
 
  <!--old price-->
<div $hide id='old_price' class='price '>  <span id='dcurrency'>₦ </span>$old_price
</div>
 
 <!--price dollars-->
 <div  id='price2' class='price  mt-2'>
     <span id='currency'>$</span>$price2
   </div>
   
 <!--old price dollars-->
 <div id='old_price2' class='price old_price'>
  <span id='dcurrency'>$</span>$old_price2</div>
 
<!--price pounds-->
 <div $hide id='price3' class='price  mt-2'>
 <span id='currency'></span></div>
 
 
  <!--old price pounds-->
   <div $hide id='old_price3' class='price old_price'>
 <span id='dcurrency'></span>
  </div>
 </div>
  <!--get details-->  
   
   
    <span id='cartbtn' class='button is-small baseColor white'>Add to cart</span>
   
 </div><!--box-->
 

 </div>  <!--column-->
 
 <hr>

  ";
            }

            //currency is pounds

            if ($s == "pounds") {
                $results .= "
  <!--Product -->
  <div class='column'>
  <div class='box mb-2'>
  <div id='get_details'>
  <div style='font-size:9px; font-weight:bold; text-align:center; border-radius:50%;' class='button is-success is-light'><span style='display:none;' id='v1' class='fa fa-check'></span> </div>
 <p style='font-size:10px; font-weight:bold; text-transform:uppercase;' id='productName' class='block '>$product</p>
 
<input id='pid' type='text' value='$pid' hidden>
 
 <p style='display:none;' id='loading' class='tag is-link is-light'>   Loading...
 <span class='button is-loading is-small is-outlined is-link'> </span> </p>
 <figure class='image centerPix'> <img class='image is-128x128 ' src='../../../public/products/$pic'></figure>
 
  <!--price-->
  <div $hide id='price' class='price  mt-2'>      <span id='currency'>₦</span>$price</div>
 
 
  <!--old price-->
<div $hide id='old_price' class='price '>  <span id='dcurrency'>₦ </span>$old_price
</div>
 
 <!--price dollars-->
 <div $hide id='price2' class='price  mt-2'>
     <span id='currency'>$</span>$price2
   </div>
   
 <!--old price dollars-->
 <div $hide id='old_price2' class='price old_price'>
  <span id='dcurrency'>$</span>$old_price2</div>
 
<!--price pounds-->
<div  id='price3' class='price  mt-2'>       <span id='currency'>£  </span>$price3    </div>
  
   
  <!--old price pounds-->
   <div id='old_price3' class='price old_price'>
 <span id='dcurrency'>£</span>$old_price3
  </div>
 </div>
  <!--get details-->  
   
   
    <span id='cartbtn' class='button is-small baseColor white'>Add to cart</span>
   
 </div><!--box-->
 

 </div>  <!--column-->
 
<hr>
  ";
            } //if pounds
        } //while

        if (empty($results)) {
            echo "Nothing was found. Try another keyword!!";

            exit();
        } else {
            echo $results;

            exit();
        }
    } //search func

    /*This function is used to load more or less products on home page. Each page contains 16 products*/

    function load_page()
    {
        $category = strtolower($_GET["category"]); //category of products to be loaded

        $category = $this->Functions->clean($category, "", "");

        $current_page = $this->Functions->clean($_GET["current"]);

        // $previous_page = $current_page -1;

        $action = $_GET["action"]; // action user wants i.e(next or previous page)

        $action = $this->Functions->clean($action);

        $limit = "16";

        $offset = $this->CustomDB->page_offset($action, $limit, $current_page); //offset claculator

        //html codes for rating of products

        $star = "<span class='fa fa-star'></span>";
        $no_star = "<span class='fa fa-star-o'></span>";
        $half_star = "<span class='fa fa-star-half-o'></span>";

        $zero_star = str_repeat($no_star, "5");

        $zero_half = $half_star . "" . str_repeat($no_star, "4");

        $one = $star . "" . str_repeat($no_star, "4");

        $one_half = $one . $half_star . "" . str_repeat($no_star, "3");

        $two = str_repeat($star, "2");

        $two_half =
            str_repeat($star, "2") . $half_star . str_repeat($no_star, "2");

        $three = str_repeat($star, "3") . str_repeat($no_star, "2");

        $three_half =
            str_repeat($star, "3") . $half_star . str_repeat($no_star, "2");

        $four = str_repeat($star, "4") . $no_star;

        $four_half = str_repeat($star, "4") . $half_star . $no_star;

        $five = str_repeat($no_star, "5");

        $params;

        $res;

        $type = "normal";

        $query_str = "";

        $query_str = " WHERE category=? AND type=? ";

        if ($category == "all") {
            $query_str = " WHERE type=?";

            $params = ["sss", [$type, $limit, $offset]];
        } else {
            $params = ["ssss", [$category, $type, $limit, $offset]];
        }

        $res = $this->CustomDB->select(
            "products",
            "$query_str ORDER BY id DESC LIMIT ? OFFSET ?",
            $params
        ); //db query to fetch products

        $i = 0; //generation of id. i.e first id is 0

        $output = "";

        $s = $_SESSION["currency"]; //current currency user is browsing site with

        $hide = ' style="display:none;" ';

        while ($row = $res->fetch_assoc()) {
            $id = "v" . $i; //first id

            $product_id = $row["pid"];

            $product = $row["productName"];

            $pic = $row["pic1"];

            $discount = $row["discount"];

            $price = (int) $row["nairaPrice"];

            $old_price = $price + ($discount / 100) * $price;

            $curr = "₦";

            $price2 = (int) $row["dollarPrice"];

            $old_price2 = $price2 + ($discount / 100) * $price2;

            $curr2 = "$";

            $price3 = (int) $row["poundsPrice"];

            $old_price3 = $price3 + ($discount / 100) * $price3;

            $curr3 = "£";

            $rating = $row["rating"];

            //set conditions for each star of rating
            if ($rating == "0") {
                $r = $zero_star;
            } elseif ($rating == "0.5") {
                $r = $zero_half;
            } elseif ($rating == "1") {
                $r = $one;
            } elseif ($rating == "1.5") {
                $r = $one_half;
            } elseif ($rating == "2") {
                $r = $two;
            } elseif ($rating == "2.5") {
                $r = $two_half;
            } elseif ($rating == "3") {
                $r = $three;
            } elseif ($rating == "3.5") {
                $r = $three_half;
            } elseif ($rating == "4") {
                $r = $four;
            } elseif ($rating == "4.5") {
                $r = $four_half;
            } elseif ($rating == "5") {
                $r = $five;
            } else {
                $r = $zero;
            }

            //html to load product if the user is browsing site witg naira as preffered choice
            if ($s == "naira") {
                $output .=
                    "<div class='column is-half-mobile is-one-quarter-desktop is-one-third-tablet'>
   <div  class='box mb-2'>
      <div id='get_details'>
   <div style='font-size:9px; font-weight:bold; text-align:center; border-radius:50%;' class='button is-success is-light'><span style='display:none;' id='$id' class='fa fa-check'></span> </div>

 <p style='font-size:10px; font-weight:bold; text-transform:uppercase;' id='productName' class='block '>$product</p> 
 
 <input id='pid' type='text' value='" .
                    $product_id .
                    " ' hidden>
 
   <p style='display:none;' id='loading' class='tag is-link is-light'>
    Loading...
   <span class='button is-loading is-small is-outlined is-link'> </span> </p> 
   
   <figure class='image centerPix'>
       
   <img class='f image is-64x64 ' src='../../../public/products/" .
                    $pic .
                    "'>   
  
   </figure>  
   
  <!--price-->
<div id='price' class='price  mt-2'>
     <span id='currency'>₦</span>" .
                    number_format($price) .
                    "
</div> 


   <!--old price-->
   <div id='old_price' class='price old_price'>
     <span id='dcurrency'> " .
                    $curr .
                    "</span>" .
                    number_format($old_price) .
                    "
</div>  
   
   
     <!--price dollars-->
<div $hide id='price2' class='price  mt-2'>
     <span id='currency'>$</span>
     " .
                    $price2 .
                    "
</div> 


   <!--old price dollars-->
   <div $hide id='old_price2' class='price old_price'>
     <span id='dcurrency'>$curr2</span>" .
                    $old_price2 .
                    "
</div>  
   
   
   
     <!--price pounds-->
<div $hide id='price3' class='price  mt-2'>
     <span id='currency'>£</span>" .
                    $price3 .
                    "
</div> 


   <!--old price pounds-->
   <div $hide id='old_price3' class='price old_price'>
     <span id='dcurrency'>$curr3</span>" .
                    $old_price3 .
                    "
</div>  
   
   </div>  <!--get details-->
   
   
   
   <!--rating-->
 <div class='rating block'>


" .
                    $r .
                    "



 <!--cart button-->
  <span id='cartbtn' class='button is-small baseColor white'>Add to cart  
      </span>
</div> <!--rating-->

</div>  <!--box-->

</div> <!--column-->
       ";
                $i += 1;
            }

            //html to load product if the user is browsing site with dollar as preffered choice
            elseif ($s == "dollar") {
                $output .=
                    "<div class='column is-half-mobile is-one-quarter-desktop is-one-third-tablet'>
   <div  class='box mb-2'>
      <div id='get_details'>
   <div style='font-size:9px; font-weight:bold; text-align:center; border-radius:50%;' class='button is-success is-light'><span style='display:none;' id='$id' class='fa fa-check'></span> </div>

 <p style='font-size:10px; font-weight:bold; text-transform:uppercase;' id='productName' class='block '>$product</p> 
 
 <input id='pid' type='text' value='" .
                    $product_id .
                    " ' hidden>
 
   <p style='display:none;' id='loading' class='tag is-link is-light'>
    Loading...
   <span class='button is-loading is-small is-outlined is-link'> </span> </p> 
   
   <figure class='image centerPix'>
       
   <img class='f image is-64x64 ' src='../../../public/products/" .
                    $pic .
                    "'>   
  
   </figure>  
   
  <!--price-->
<div $hide id='price' class='price  mt-2'>
     <span id='currency'>₦</span>" .
                    $price .
                    "
</div> 


   <!--old price-->
   <div $hide id='old_price' class='price old_price'>
     <span id='dcurrency'> " .
                    $curr .
                    "</span>" .
                    $old_price .
                    "
</div>  
   
   
     <!--price dollars-->
<div id='price2' class='price  mt-2'>
     <span id='currency'>$</span>
     " .
                    number_format($price2) .
                    "
</div> 


   <!--old price dollars-->
   <div id='old_price2' class='price old_price'>
     <span id='dcurrency'>$curr2</span>" .
                    number_format($old_price2) .
                    "
</div>  
   
   
   
     <!--price pounds-->
<div $hide id='price3' class='price  mt-2'>
     <span id='currency'>£</span>" .
                    $price3 .
                    "
</div> 


   <!--old price pounds-->
   <div $hide id='old_price3' class='price old_price'>
     <span id='dcurrency'>$curr3</span>" .
                    $old_price3 .
                    "
</div>  
   
   </div>  <!--get details-->
   
   
   
   <!--rating-->
 <div class='rating block'>


" .
                    $r .
                    "



 <!--cart button-->
  <span id='cartbtn' class='button is-small baseColor white'>Add to cart  
      </span>
</div> <!--rating-->

</div>  <!--box-->

</div> <!--column-->
       ";
                $i += 1;
            }

            //if $s is dollar
            //html to load product if the user is browsing site with pounds as preffered choice
            elseif ($s == "pounds") {
                $output .=
                    "<div class='column is-half-mobile is-one-quarter-desktop is-one-third-tablet'>
   <div  class='box mb-2'>
      <div id='get_details'>
   <div style='font-size:9px; font-weight:bold; text-align:center; border-radius:50%;' class='button is-success is-light'><span style='display:none;' id='$id' class='fa fa-check'></span> </div>

 <p style='font-size:10px; font-weight:bold; text-transform:uppercase;' id='productName' class='block '>$product</p> 
 
 <input id='pid' type='text' value='" .
                    $product_id .
                    " ' hidden>
 
   <p style='display:none;' id='loading' class='tag is-link is-light'>
    Loading...
   <span class='button is-loading is-small is-outlined is-link'> </span> </p> 
   
   <figure class='image centerPix'>
       
   <img class='f image is-64x64 ' src='../../../public/products/" .
                    $pic .
                    "'>   
  
   </figure>  
   
  <!--price-->
<div $hide id='price' class='price  mt-2'>
     <span id='currency'>₦</span>" .
                    $price .
                    "
</div> 


   <!--old price-->
   <div $hide id='old_price' class='price old_price'>
     <span id='dcurrency'> " .
                    $curr .
                    "</span>" .
                    $old_price .
                    "
</div>  
   
   
     <!--price dollars-->
<div $hide id='price2' class='price  mt-2'>
     <span id='currency'>$</span>
     " .
                    $price2 .
                    "
</div> 


   <!--old price dollars-->
   <div $hide id='old_price2' class='price old_price'>
     <span id='dcurrency'>$curr2</span>" .
                    $old_price2 .
                    "
</div>  
   
   
   
     <!--price pounds-->
<div id='price3' class='price  mt-2'>
     <span id='currency'>£</span>" .
                    number_format($price3) .
                    "
</div> 


   <!--old price pounds-->
   <div id='old_price3' class='price old_price'>
     <span id='dcurrency'>$curr3</span>" .
                    number_format($old_price3) .
                    "
</div>  
   
   </div>  <!--get details-->
   
   
   
   <!--rating-->
 <div class='rating block'>


" .
                    $r .
                    "



 <!--cart button-->
  <span id='cartbtn' class='button is-small baseColor white'>Add to cart  
      </span>
</div> <!--rating-->

</div>  <!--box-->

</div> <!--column-->
       ";
                $i += 1;
            }

            //elseif $s is pounds
            else {
                exit("0");
            }
        } //while

        if (empty($output)) {
            exit("0");
        } else {
            exit($output);
        }
    } //more func

    /*This handles the rating of products by users. Only logged in users can rate product*/

    function rate()
    {
        session_regenerate_id();

        if (!isset($_SESSION["user"])) {
            exit("Only Registered User can vote");
        }

        $vote = $_POST["vote"];

        $pid = $_POST["pid"];

        $pid = $this->Functions->clean($pid, "", "");

        $vote = $this->Functions->clean("", $vote, "");

        $status = ""; //vote status

        //check if user already rated product to avoid multiple rating by a user

        $params = ["ss", [$_SESSION["user"], $pid]];

        $res = $this->CustomDB->select(
            "vote",
            "WHERE user=? AND pid=?",
            $params
        );

        $row = $res->fetch_assoc();

        if ($res->num_rows == 1) {
            $vote_before = $row["value"]; //get user previous rating

            $status = "revote"; //the product will be revoted

            //remove the previous vote
            $params = ["s", [$pid]];

            $res = $this->CustomDB->select("products", "WHERE pid=?", $params);

            $row = $res->fetch_assoc();

            $voters = $row["voters"]; //total number of raters of the product

            $votes = $row["votes"]; //total value of all rating

            $new_votes = $votes - $vote_before; //remove the rating value of user from all ratings

            $new_voters = $voters - 1; //remove user from all the people who have rated before.

            //avoid division by zero in the case whereby only this user have rated the product previously.
            if ($new_voters < 1) {
                $rating = 0;
            } else {
                $rating = $new_votes / $new_voters; //value of remaining rating after removing users rating
            }

            //update db with rating details as it is aftere removing this users rating.

            $params = ["ssss", [$new_votes, $new_voters, $rating, $pid]];

            $res = $this->CustomDB->update(
                "products",
                "SET votes=?,voters=?,rating=? WHERE pid=?",
                $params
            );
        } //if voted before

        //get the rating and the total number of raters as it is

        $params = ["s", [$pid]];

        $res = $this->CustomDB->select("products", "WHERE pid=?", $params);

        $row = $res->fetch_assoc();

        $voters = $row["voters"];

        $votes = $row["votes"];

        //start new rating

        $new_votes = $votes + $vote; //adding user new choice of rating to the one in db

        $new_voters = $voters + 1; //total number of raters plus one

        $rating = $new_votes / $new_voters; //rating is sum of all rating value by users divided by total number of user who rated the product

        //update rating table with new data
        $params = ["ssss", [$new_votes, $new_voters, $rating, $pid]];

        $res = $this->CustomDB->update(
            "products",
            "SET votes=?,voters=?,rating=? WHERE pid=?",
            $params
        );

        //take records of who has voted

        if ($status !== "revote") {
            //save new voters

            $params = ["sss", "?,?,?", [$_SESSION["user"], $pid, $rating]];

            $res = $this->CustomDB->insert("vote", "user,pid,value", $params);
        } else {
            //update voter's info

            $params = ["sss", [$pid, $rating, $_SESSION["user"]]];

            $res = $this->CustomDB->update(
                "vote",
                "SET pid=?,value=? WHERE user=?",
                $params
            );
        }

        exit("Rating Saved!!!");
    } //rate

    /*Handles the addition of flash product to cart.*/

    function add_flash_product()
    {
        //get all details of the product and send to frontend to be appended on table in cart.

        $pid = $_POST["pid"];

        $pid = $this->Functions->clean($pid);

        $currency = $_SESSION["currency"];

        if ($currency == "naira") {
            $curr = "nairaPrice";

            $icon = "₦";
        }

        if ($currency == "dollar") {
            $curr = "dollarPrice";

            $icon = "$";
        }

        if ($currency == "pounds") {
            $curr = "poundsPrice";

            $icon = "£";
        }

        $params = ["s", [$pid]];

        $db = $this->CustomDB->select("products", "WHERE pid=?", $params);

        $row = $db->fetch_assoc();

        $name = $row["productName"];

        $price = number_format($row[$curr]);

        $qty = 1;

        //send info about product via ajax to frontend
        $data = [
            "name" => $name,
            "pid" => $pid,
            "price" => $price,
            "qty" => $qty,
            "currency" => $currency,
            "icon" => $icon,
        ];

        echo json_encode($data);

        exit();
    }

    /*This changes the status of a flash product when it is expired to avoid customers still seeing a flash product wheb it has expired*/

    function change_flashProduct_status()
    {
        $pid = $_POST["pid"];

        $pid = $this->Functions->clean($pid);

        $status = "expired";

        $params = ["ss", [$status, $pid]];

        $db = $this->CustomDB->update(
            "products",
            "SET status=? WHERE pid=?",
            $params
        );
    } //

    //load  categories of produad

    function load_category()
    {
        $auth = $this->Functions->clean($_GET["auth"], "", "");

        if ($auth !== $_SESSION["category_auth"]) {
            exit("Access denied");
        }


        $output = ""; //the varuable that holds the html of fetched products.

        $category = $this->Functions->clean($_GET["category"], "", "");

        $type = "normal"; //avoid displaying flash products.

        $params = ["ss", [$category, $type]];

        $db = $this->CustomDB->select(
            "products",
            "WHERE category=? AND type=? LIMIT 16",
            $params
        ); //get products

        $i = 1; //for generation of id used to show tick icon when product is added to cart.

        $r; //to hold rating value

        //html for rating
        $star = "<span class='fa fa-star'></span>";
        $no_star = "<span class='fa fa-star-o'></span>";
        $half_star = "<span class='fa fa-star-half-o'></span>";

        $zero_star = str_repeat($no_star, "5");

        $zero_half = $half_star . "" . str_repeat($no_star, "4");

        $one = $star . "" . str_repeat($no_star, "4");

        $one_half = $one . $half_star . "" . str_repeat($no_star, "3");
        $two = str_repeat($star, "2");
        $two_half =
            str_repeat($star, "2") . $half_star . str_repeat($no_star, "2");
        $three = str_repeat($star, "3") . str_repeat($no_star, "2");
        $three_half =
            str_repeat($star, "3") . $half_star . str_repeat($no_star, "1");

        $four = str_repeat($star, "4") . $no_star;
        $four_half = str_repeat($star, "4") . $half_star;
        $five = str_repeat($star, "5");

        $price_info = ""; //html to display price information according to the choice of user

        //load products and put all in a single variable
        while ($row = $db->fetch_assoc()) {
            $id = "v" . $i; //first product id

            $default_price = $row["nairaPrice"]; //price in naira

            $curr = "₦"; //currency symbol

            $curr2 = "$"; //dollar currency symbol

            $curr3 = "£"; //pounds currency

            $product = $row["productName"]; //product name

            $pic = $row["pic1"]; //main picture

            $price = (int) $row["nairaPrice"]; //price in nigerian naira

            $discount = (int) $row["discount"]; // % discount

            $discount = $discount / 100;

            $old_price = number_format($discount * $price + $price); // price sold before in naira

            //set dollar price details
            $price2 = (int) $row["dollarPrice"]; //price in dollars

            $old_price2 = number_format($discount * $price2 + $price2, 1); //price sold before in dollars

            $price3 = (int) $row["poundsPrice"]; //price in pounds

            $old_price3 = number_format($discount * $price3 + $price3); //price sold before in pounds

            //Dont show old prices if no discount is on a product
            if ($discount == 0) {
                $old_price = $old_price2 = $old_price3 = "";
                $curr = $curr2 = $curr3 = "";
            }

            $product_id = $row["pid"];

            //rating calculation for a product.
            $rating = $row["rating"];

            $rating = round($rating / 0.5, 0) * 0.5; //round to nearest 0.5

            if ($rating == "0") {
                $r = $zero_star;
            } elseif ($rating == "0.5") {
                $r = $zero_half;
            } elseif ($rating == "1") {
                $r = $one;
            } elseif ($rating == "1.5") {
                $r = $one_half;
            } elseif ($rating == "2") {
                $r = $two;
            } elseif ($rating == "2.5") {
                $r = $two_half;
            } elseif ($rating == "3") {
                $r = $three;
            } elseif ($rating == "3.5") {
                $r = $three_half;
            } elseif ($rating == "4") {
                $r = $four;
            } elseif ($rating == "4.5") {
                $r = $four_half;
            } elseif ($rating == "5") {
                $r = $five;
            } else {
                $r = $zero;
            }

            //display price info based on the user currency choice to avoid all the prices of three currencies shown.

            if (
                !isset($_SESSION["currency"]) ||
                $_SESSION["currency"] == "naira"
            ) {
                //hide dollar and pounds price info

                $price_info =
                    "
     
     
     <!--price pounds-->
<div style='display:none;' id='price3' class='price  mt-2'>
     <span id='currency'>£</span>" .
                    $price3 .
                    "
</div> 


   <!--old price pounds-->
   <div style='display:none;' id='old_price3' class='price old_price'>
     <span id='dcurrency'>$curr3</span>" .
                    $old_price3 .
                    "
</div>  
   
   
   
     <!--price dollars-->
<div style='display:none;' id='price2' class='price  mt-2'>
     <span id='currency'>$</span>
     " .
                    $price2 .
                    "
</div> 


   <!--old price dollars-->
   <div style='display:none;' id='old_price2' class='price old_price'>
     <span id='dcurrency'>$curr2</span>" .
                    $old_price2 .
                    "
     </div>
     
     
<div id='price' class='price  mt-2'>
     <span id='currency'>₦</span>" .
                    $price .
                    "
</div> 


   <!--old price-->
   <div id='old_price' class='price old_price'>
     <span id='dcurrency'> " .
                    $curr .
                    "</span>" .
                    $old_price .
                    "
</div>";
            } elseif ($_SESSION["currency"] == "dollar") {
                //hide naira and pounds price infos

                $price_info =
                    "
 
     
<div style='display:none;' id='price' class='price  mt-2'>
     <span id='currency'>₦</span>" .
                    $price .
                    "
</div> 


   <!--old price-->
   <div style='display:none;' id='old_price' class='price old_price'>
     <span id='dcurrency'> " .
                    $curr .
                    "</span>" .
                    $old_price .
                    "
</div>
 
 
     
     <!--price pounds-->
<div style='display:none;' id='price3' class='price  mt-2'>
     <span id='currency'>£</span>" .
                    $price3 .
                    "
</div> 


   <!--old price pounds-->
   <div style='display:none;' id='old_price3' class='price old_price'>
     <span id='dcurrency'>$curr3</span>" .
                    $old_price3 .
                    "
</div>  
   
     <!--price dollars-->
<div id='price2' class='price  mt-2'>
     <span id='currency'>$</span>
     " .
                    $price2 .
                    "
</div> 


   <!--old price dollars-->
   <div id='old_price2' class='price old_price'>
     <span id='dcurrency'>$curr2</span>" .
                    $old_price2 .
                    "
     </div>
     ";
            } elseif ($_SESSION["currency"] == "pounds") {
                //hide naira and dollar prices info.

                $price_info =
                    "

     
<div style='display:none;' id='price' class='price  mt-2'>
     <span id='currency'>₦</span>" .
                    $price .
                    "
</div> 


   <!--old price-->
   <div style='display:none;' id='old_price' class='price old_price'>
     <span id='dcurrency'> " .
                    $curr .
                    "</span>" .
                    $old_price .
                    "
</div>
   
   
     <!--price dollars-->
<div style='display:none;' id='price2' class='price  mt-2'>
     <span id='currency'>$</span>
     " .
                    $price2 .
                    "
</div> 


   <!--old price dollars-->
   <div style='display:none;' id='old_price2' class='price old_price'>
     <span id='dcurrency'>$curr2</span>" .
                    $old_price2 .
                    "
     </div>
     
     
     <!--price pounds-->
<div id='price3' class='price  mt-2'>
     <span id='currency'>£</span>" .
                    $price3 .
                    "
</div> 


   <!--old price pounds-->
   <div id='old_price3' class='price old_price'>
     <span id='dcurrency'>$curr3</span>" .
                    $old_price3 .
                    "
</div>  
     
    ";
            } else {
                //no currency chosen
                exit();
            }

            $output .=
                "<div class='column is-half-mobile is-one-quarter-desktop is-one-third-tablet'>
   <div  class='box mb-2'>
      <div id='get_details'>
   <div style='font-size:9px; font-weight:bold; text-align:center; border-radius:50%;' class='button is-success is-light'><span style='display:none;' id='$id' class='fa fa-check'></span> </div>

 <p style='font-size:10px; font-weight:bold; text-transform:uppercase;' id='productName' class='block '>$product</p> 
 
 <input id='pid' type='text' value='" .
                $product_id .
                " ' hidden>
 
   <p style='display:none;' id='loading' class='tag is-link is-light'>
    Loading...
   <span class='button is-loading is-small is-outlined is-link'> </span> </p> 
   
   <figure class='image centerPix'>
       
   <img class='image is-64x64 ' src='../../../public/products/" .
                $pic .
                "'>   
  
   </figure> " .
                $price_info .
                "
   
   </div>  <!--get details-->
   
   
   
   <!--rating-->
 <div class='rating block'>


" .
                $r .
                "

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
";
            $i += 1;
        }

        exit($output);
    } //products category
} //class end
