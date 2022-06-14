<?php

require "../modules/stripe/assets/init.php";

class Stripe extends Trongate
{
    function pay()
    {
        //secrete key
        define(
            "SK",
           "Your stripe secret key"
        );

        //public key
        define(
            "PK",
            "Your stripe public key"
        );

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $amount = $_POST["amount"] * 100;

            $amount = $this->Functions->clean($amount, "", "");

            $reference = $_POST["reference"];

            $reference = $this->Functions->clean($reference, "", "");

            $email = $_POST["email"];

            $email = $this->Functions->clean("", "", $email);

            $phone = $_POST["phone"];

            $phone = $this->Functions->clean("", $phone, "");

            $currency = $_POST["currency"];

            $currency = $this->Functions->clean($currency, "", "");

            $base_url = "https://mtstorez.000webhostapp.com";

            $success = $base_url . "/store/stripe_pay";

            $cancel = $base_url . "/store/stripe_cancel";

            $session = new \Stripe\StripeClient(SK);

            $session = $session->checkout->sessions->create([
                "success_url" => $success,
                "cancel_url" => $cancel,
                "line_items" => [
                    [
                        "currency" => $currency,
                        "name" =>
                            "MTStores Payment with reference id:  " .
                            $reference,
                        "amount" => $amount,
                        "quantity" => 1,
                    ],
                ],
                "mode" => "payment",
            ]);

            exit(json_encode($session));

            /*
// Charge the user's card:
$charge = \Stripe\Charge::create(array(
"amount" => $amount,
"currency" => $currency,
"description" => "Payment for MT_reference: ".$reference,
"source" => $stripe['id'],

 'metadata' => array(
            'customer' => $email,'phone'=>$phone)
));

$data = array('success' => true, 'data'=> $charge);

echo json_encode($data); 

exit;

*/
        }

        //post
        else {
            exit(0);
        }
        exit();
    } //pay
} //class

?>
