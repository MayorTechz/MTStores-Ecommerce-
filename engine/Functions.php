<?php

/*This contains custom functions like setting of timezone,sanitizing data and others. You can edit or add yours*/

class Functions
{
    private $string;

    private $int;

    private $email;

    function clean($string = "", $int = "", $email = "")
    {
        $this->string = trim($string);

        $this->int = trim($int);

        $this->email = trim($email);

        if ($this->string) {
            $this->string = filter_var($this->string, FILTER_SANITIZE_STRING);

            if ($this->string == true) {
                return $this->string;
            } else {
                exit("Only alphanumerics  allowed!! Check your fields");
            }
        }

        if ($this->int) {
            $this->int = filter_var($this->int, FILTER_VALIDATE_INT);

            if ($this->int == true) {
                return $this->int;
            } else {
                exit("Only valid numbers allowed!! Check fields");
            }
        } //int clean

        if ($this->email) {
            $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);

            $this->email = filter_var($this->email, FILTER_VALIDATE_EMAIL);

            if ($this->email == true) {
                return $this->email;
            } else {
                exit(json_encode("Please fill email correctly"));
                
               
            }
        } // email clean
    } //clean

    function set_tz($ip)
    {
        $this->ip = $ip;

        // Initiate curl session to get timezone of users
        $ch = curl_init();

        $url =
            "http://ip-api.com/php/" .
            $this->ip .
            "?fields=status,message,timezone,query";

        // Set the curl URL option
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Execute curl & store data in a variable
        $curl_data = curl_exec($ch);

        $rsp = unserialize($curl_data);

        $d = $rsp["timezone"]; //timezone gotten

        curl_close($ch);

        $tz = date_default_timezone_set($d);

        return $d;
    }

    function seconds_converter($secs)
    {
        $s = $secs % 60;
        $m = floor(($secs % 3600) / 60);
        $h = floor(($secs % 86400) / 3600);
        $d = floor(($secs % 2592000) / 86400);
        $M = floor($secs / 2592000);

        $data = [
            "months" => $M,
            "days" => $d,
            "hours" => $h,
            "minutes" => $m,
            "seconds" => $s,
        ];

        return $data;
    }

    function mailer($subject, $message, $recipient, $sender)
    {
        $this->subject = $subject;

        $this->message = $message;

        $this->recipient = $recipient;

        $this->sender = $sender;

        $organization = explode("@", $this->recipient);

        $organization = $organization[0];

        $headers = "";

        $headers .=
            "From: " .
            $this->sender .
            "\r\n" .
            "Reply-To: " .
            $this->sender .
            "\r\n";

        $headers .= "Return-Path: " . $this->sender . "\r\n";

        $headers .= "MIME-Version: 1.0" . "\r\n";


        $headers .= "Content-Type: text/html; charset=utf-8" . "\r\n";


        if (mail($recipient, $subject, $this->message, $headers)) {
            return 1;
        }
    } //mailer
    
    
    
} // class
