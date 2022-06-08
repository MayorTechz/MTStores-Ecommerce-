<?php
//error_reporting(E_ALL);

class User extends Trongate
{
    function logout()
    {
        session_destroy();
        echo "1";

        exit();
    } //logout

    function login()
    {
        $token = $_SESSION["form_token"];

        $form_auth = $_POST["auth"];

        $new_token = bin2hex(random_bytes(20));

        //CSRF check
        if ($token !== $form_auth) {
            $_SESSION["form_token"] = $new_token;
            $msg = "access denied";

            $reply = ["msg" => $msg, "status" => "0", "token" => $new_token]; //populate token for use in a new request.

            exit(json_encode($reply));
        }

        $email = $this->Functions->clean("", "", $_POST["email"]);

        $password = $this->Functions->clean($_POST["password"], "", "");

        $params = ["s", [$email]];

        $res = $this->CustomDB->select("user", "WHERE email=? ", $params);

        if ($res->num_rows >= 1) {
            $row = $res->fetch_assoc();

            $db_pass = $row["password"];

            $verification = password_verify($password, $db_pass);

            if ($verification == false) {
                $_SESSION["form_token"] = $new_token;
                $msg = "Invalid Login details";

                $reply = [
                    "msg" => $msg,
                    "status" => "0",
                    "token" => $new_token,
                ]; //populate token for use in a new request.

                exit(json_encode($reply));
            } else {
                //to identify and set login status at the front end
                $_SESSION["login_status"] = "button is-success online-badge";

                $_SESSION["user"] = $email;

                $_SESSION["time"] = time();

                $_SESSION["form_token"] = $new_token; //repopulate form token

                $r = ["status" => "1", "token" => $token];

                exit(json_encode($r));
            } //verification true
        }

        //user with provided email exists
        else {
            //user with email doesn't exist.

            $_SESSION["form_token"] = $new_token;

            $msg = "Invalid Login details";

            $reply = ["msg" => $msg, "status" => "0", "token" => $new_token]; //populate token for use in a new request.

            exit(json_encode($reply));
        }
    } //login

    function register()
    {
        $token = $_SESSION["form_token"];

        $form_auth = $_POST["auth"];

        $new_token = bin2hex(random_bytes(20));

        //CSRF check
        if ($token !== $form_auth) {
            $_SESSION["form_token"] = $new_token;

            $msg = "access denied";

            $reply = ["msg" => $msg, "status" => "0", "token" => $new_token]; //populate token for use in a new request.

            exit(json_encode($reply));
        }

        $email = $_POST["email"];

        $email = $this->Functions->clean("", "", $email);

        $password = $_POST["password"];

        $password = $this->Functions->clean($password, "", "");

        $msg = ""; //error msg

        if (strlen($password) < 6) {
            $msg = "Password must be up to six(6) characters!!";

            $_SESSION["form_token"] = $new_token;

            $reply = ["msg" => $msg, "status" => "0", "token" => $new_token]; //populate token for use in a new request.

            exit(json_encode($reply));
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        $fname = $_POST["fname"];

        $fname = $this->Functions->clean($fname, "", "");

        $oname = $_POST["oname"];

        $oname = $this->Functions->clean($oname, "", "");

        $phone = (int) $_POST["phone"];

        $phone = $this->Functions->clean("", $phone, "");

        $country = $_POST["country"];

        $country = $this->Functions->clean($country, "", "");

        //check if email exists.
        $params = ["s", [$email]];

        $res = $this->CustomDB->select("user", "WHERE email=?", $params);
        if ($res->num_rows >= 1) {
            $msg =
                "A user with this email already exists!! Click on forgot password instead!!";

            $_SESSION["form_token"] = $new_token;

            $reply = ["msg" => $msg, "status" => "0", "token" => $new_token]; //populate token for use in a new request.

            exit(json_encode($reply));
        }

        $datas = [$fname, $oname, $country, $phone, $email];

        foreach ($datas as $d) {
            if (strlen($d) < 1) {
                $msg = "All fields must be filled!!!";

                $_SESSION["form_token"] = $new_token;

                $reply = [
                    "msg" => $msg,
                    "status" => "0",
                    "token" => $new_token,
                ]; //populate token for use in a new request.

                exit(json_encode($reply));
            } else {
                $test = "passed";
            }
        } //foreach

        $phone = $country . $phone; //prepend d leading zero

        if ($test == "passed") {
            //insert user into db
            $params = [
                "ssssss",
                "?,?,?,?,?,?",
                [$email, $password, $fname, $oname, $phone, $country],
            ];

            //insert  registration details.

            $s = $this->CustomDB->insert(
                "user",
                "email,password,surname,other_names,phoneNo,country",
                $params
            );

            //send user email

            $recipient = $email;

            $subject = "MTStores  Registration";

            $sender = "store@mtstorez.000webhostapp.com";

            // To send HTML mail, the Content-type header must be set

            $recipient_name = "user";

            $msg = '
  
  <table style="border-solid 2px blue;"> 
    <tr>
       <td style="background-color:#EBEBEB;
         color:black;
  box-shadow: 10px 10px 5px #EBEBEB;
 margin:auto; text-align:center;">
 
 <img src"https://mtstorez.000webhostapp.com/public/logo/mts.png">
      <h1>
        Welcome | MTStores Registration
      </h1>
      <p>
        
 <h3>You are welcome to our online store. please keep your password safe. If at any time you need to ask us question feel free to contact us. Happy shopping!!</h3> <br>  
   
      </p>
    </td>
  </tr></table>
  ';

            $this->Functions->mailer($subject, $msg, $recipient, $sender);

            //to identify and set login status at the front end
            $_SESSION["login_status"] = "button is-success online-badge";

            //create a usser session
            $_SESSION["user"] = $email;

            $_SESSION["time"] = time();

            $_SESSION["form_token"] = $new_token;

            $reply = ["status" => "1", "token" => $new_token]; //populate token for use in a new request.

            exit(json_encode($reply));
        }
    } //register

    function profile()
    {
        //handles profile page

        if (!isset($_SESSION["user"]) || time() - $_SESSION["time"] > 1800) {
            //user not logged in or time of inactivity reached

            session_destroy();

            header("location:../../../store/home");

            exit();
        } //isset

        if (isset($_POST["more"])) {
            $token = $_SESSION["csrf"];
            $request_auth = $_POST["auth"];

            //CSRF check
            if ($token !== $request_auth) {
                echo "access denied!!!";
                exit();
            }

            $current_page = $this->Functions->clean($_POST["page"], "", "");

            $limit = 5;

            $sn = $current_page * 5 + 1;

            $offset = $current_page * $limit;

            $output = "";

            $params = ["ss", [$_SESSION["user"], $offset]];

            $res = $this->CustomDB->select(
                "transactions",
                "WHERE user=? ORDER BY id DESC LIMIT 5 OFFSET ?",
                $params
            );

            if ($res->num_rows < 1) {
                exit("0");
            }

            while ($row = $res->fetch_assoc()) {
                $ref = $row["reference"];

                $amount = $row["totalPaid"];

                $amount = number_format($amount);

                $date = $row["date"];

                $receipt = $row["receipt"];

                $receipt = explode("*", $receipt);

                if ($receipt[2] == "nairaPrice") {
                    $currency = "₦";
                }

                if ($receipt[2] == "dollarPrice") {
                    $currency = "$";
                }

                if ($receipt[2] == "poundsPrice") {
                    $currency = "£";
                }

                $status = $row["status"];

                if ($status == "check") {
                    $css = "success";
                } else {
                    $css = "danger";
                }

                $output .=
                    '
        
   <tr>
       
 <td>' .
                    $sn .
                    '</td>     
 <td>' .
                    $ref .
                    '</td>
 <td>' .
                    $currency .
                    $amount .
                    '</td>
 <td>' .
                    $date .
                    '</td>
 <td class="details"><span class="tag is-' .
                    $css .
                    " fa fa-" .
                    $status .
                    '"></span></td>
   </tr>';

                $sn++;
            }

            echo $output;

            exit();
        } //load more page end

        //load transactions history

        $params = ["s", [$_SESSION["user"]]];

        $res = $this->CustomDB->select("user", "WHERE email=?", $params);

        $row = $res->fetch_assoc();

        $first_name = $row["surname"];

        $others = $row["other_names"];

        $phone = $row["phoneNo"];

        $country = $row["country"];

        $data["fname"] = $first_name;

        $data["oname"] = $others;

        $data["phone"] = $phone;

        $data["country"] = $country;

        $params = ["s", [$_SESSION["user"]]];

        $res = $this->CustomDB->select(
            "transactions",
            "WHERE user=? ORDER BY id DESC LIMIT 5",
            $params
        );

        $data["d"] = $res;

        $this->view("profile", $data);
    } //profiles

    function reset_password()
    {
        $auth = bin2hex(random_bytes(6)); //generate 12strings

        $_SESSION["reset"] = $auth;

        $data = []; //data container.

        //Collects data from reset link and redirrcts user to anothher page where they enter new password
        if (isset($_GET["reset_code"])) {
            $code = $_GET["reset_code"];

            $code = $this->Functions->clean($_GET["reset_code"], "", "");

            $data["code"] = $code;

            $data["auth"] = $auth; //data to be sent along to page

            $this->view("reset_auth", $data); //load page

            exit();
        }

        /* Handles sending of reset token to email*/
        if (isset($_POST["reset"])) {
            $token = $auth;

            if ($token !== $_SESSION["reset"]) {
                echo "access denied!!";

                exit();
            }

            $reset_code = bin2hex(random_bytes(3)) . uniqid();

            $link =
                "https://mtstorez.000webhostapp.com/user/reset_password?reset_code=" .
                $reset_code;

            $sender = "store@mtstorez.000webhostapp.com";

            $phone = $this->Functions->clean("", intval($_POST["phone"]), "");

            $phone = "0" . $phone; //append back the leading zero

            $recipient = $this->Functions->clean("", "", $_POST["email"]);

            $params = ["ss", [$recipient, $phone]];

            $res = $this->CustomDB->select(
                "user",
                "WHERE email=? AND phoneNo=?",
                $params
            );

            if ($res->num_rows < 1) {
                $msg = "Account with the details you provided does not exist.";

                $r = ["msg" => $msg];

                exit(json_encode($r));
            }

            $subject = "MTStores- Password Reset";

            $msg =
                '
  
  <table style="border-solid 2px blue;"> 
    <tr>
       <td style="background-color:#EBEBEB;
         color:black;
  box-shadow: 10px 10px 5px #EBEBEB;
 margin:auto; text-align:center;">
 
 <img src"https://mtstorez.000webhostapp.com/public/logo/mts.png">
      <h1>
        MTStores | Password reset
      </h1>
      <p>
        
 <h3>You requested for a password reset. Your password reset link is ' .
                $link .
                ' Click the link or copy the link to your browser.Please ignore, if you did not initiate the request. </h3> <br>  
   
      </p>
    </td>
  </tr></table>
  ';

            $this->Functions->mailer($subject, $msg, $recipient, $sender); //send mail

            //insert reset code to db for verifications later

            $params = ["ss", [$reset_code, $recipient]];

            $this->CustomDB->update(
                "user",
                "SET reset_token = ? WHERE email=?",
                $params
            );

            $msg = "Reset link sent";

            $r = ["msg" => $msg];

            exit(json_encode($r));
        } //reset

        //Load page where reset link will be sent to user email.

        $data["auth"] = $auth;

        $this->view("reset", $data);
    } //function reset

    /*Last Reset Process i.e Handles change of password*/
    function change_password()
    {
        $auth = $this->Functions->clean($_POST["auth"], "", "");

        if ($_SESSION["reset"] !== $auth) {
            exit("Access denied!!!");
        }

        $email = $this->Functions->clean("", "", $_POST["email"]);

        $reset_code = $_POST["code"];

        $reset_code = $this->Functions->clean($reset_code);

        $password = $_POST["password"];

        $password = $this->Functions->clean($password, "", "");

        if ($password < 6) {
            exit("Password must be up to six(6) characters!!");
        }

        $password_confirm = $_POST["p_confirm"];

        $password_confirm = $this->Functions->clean($password_confirm, "", "");

        $new_token = "";

        if ($password !== $password_confirm) {
            exit("Password does not match!!");
        }

        //compare reset token sent to email and the one provided

        $params = ["ss", [$reset_code, $email]];

        $res = $this->CustomDB->select(
            "user",
            "WHERE reset_token=? AND email=?",
            $params
        );

        if ($res->num_rows == 1) {
            $row = $res->fetch_assoc();

            $db_token = $row["reset_token"];

            if ($db_token == $reset_code) {
                $password = password_hash($password, PASSWORD_DEFAULT);

                //uppdate new pass in db
                $params = ["sss", [$password, $new_token, $email]];

                $this->CustomDB->update(
                    "user",
                    "SET password=?,reset_token=? WHERE email=?",
                    $params
                );

                echo "1";

                exit();
            }
        }
    } //change password.

    /*Handles load of more details about a transaction*/

    function details()
    {
        $token = $_SESSION["csrf"];

        $request_auth = $_POST["auth"];

        //CSRF check
        if ($token !== $request_auth) {
            echo "access denied!!!";
            exit();
        }

        $ref = $this->Functions->clean($_POST["reference"], "", "");

        $html;

        $output = "";

        //get details from db

        $params = ["ss", [$ref, $_SESSION["user"]]];

        $res = $this->CustomDB->select(
            "transactions",
            "WHERE reference=? AND user=?",
            $params
        );
        $row = $res->fetch_assoc();

        $total = intval($row["totalPaid"]);

        $rec = $row["receipt"];

        $delivery_type = $row["delivery_type"];

        $curr;

        $rec = substr_replace($rec, "", -1);

        $rec = explode(",", $rec);

        $amount = 0;

        for ($i = 0; $i < count($rec); $i++) {
            $e = explode("*", $rec[$i]);

            $name = $e[0];
            $qty = $e[1];
            $currency = $e[2];
            $price = intval($e[3]);
            $amount += $price;

            if ($currency == "nairaPrice") {
                $curr = "₦";
            }

            if ($currency == "dollarPrice") {
                $curr = "$";
            }

            if ($currency == "poundsPrice") {
                $curr = "£";
            }

            $html =
                '<tr>
  <td>' .
                $name .
                '</td>
  <td>' .
                $qty .
                '</td>
  <td>' .
                $curr .
                number_format($price) .
                '</td>
  </tr>
  ';

            $output .= $html;
        }

        $delivery = $total - $amount;

        $output .=
            '  <tr>
  <td></td>
  <td>Delivery Fee</td>
  <td>' .
            $curr .
            number_format($delivery) .
            " -(" .
            $delivery_type .
            ')</td>
  <tr>
  
    <tr>
  <td></td>
  <td>Total Paid</td>
  <td><span class="button is-success is-rounded is-small">' .
            $curr .
            number_format($total) .
            '</span></td>
  <tr>';

        $o = ["currency" => $curr, "html" => $output];
        echo json_encode($o);
        exit();
    } //func details
} //class user

?>
