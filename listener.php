<?php

require('./env.php');
require('PaypalIPN.php');
require('./db/Connection.php');

// Set this to true to use the sandbox endpoint during testing:
$enable_sandbox = true;
// Use this to specify all of the email addresses that you have attached to paypal:
$my_email_addresses = explode(", ", getenv("EMAILS_AVALAIBLES"));;
// Set this to true to send a confirmation email:
$send_confirmation_email = false;
$confirmation_email_address = "My Name <my_email_address@gmail.com>";
$from_email_address = "My Name <my_email_address@gmail.com>";
// Set this to true to save a log file:
$save_log_file = false;
$log_file_dir = __DIR__ . "/logs";
// Here is some information on how to configure sendmail:
// http://php.net/manual/en/function.mail.php#118210



$ipn = new PaypalIPN();
if ($enable_sandbox) {
    $ipn->useSandbox();
}

$ipn->usePHPCerts();

$verified = $ipn->verifyIPN();
$data_text = "";
foreach ($_POST as $key => $value) {
    $data_text .= $key . " = " . $value . "\r\n";
}
$test_text = "";
if ($_POST["test_ipn"] == 1) {
    $test_text = "Test ";
}
// Check the receiver email to see if it matches your list of paypal email addresses
$receiver_email_found = false;
foreach ($my_email_addresses as $a) {
    if (strtolower($_POST["receiver_email"]) == strtolower($a)) {
        $receiver_email_found = true;
        break;
    }
}
date_default_timezone_set("America/Santiago");
list($year, $month, $day, $hour, $minute, $second, $timezone) = explode(":", date("Y:m:d:H:i:s:T"));
$date = $year . "-" . $month . "-" . $day;
$timestamp = $date . " " . $hour . ":" . $minute . ":" . $second . " " . $timezone;
$dated_log_file_dir = $log_file_dir . "/" . $year . "/" . $month;
$paypal_ipn_status = "VERIFICATION FAILED";
if ($verified) {
    $paypal_ipn_status = "RECEIVER EMAIL MISMATCH";
    if ($receiver_email_found) {
        $paypal_ipn_status = "Completed Successfully";
        // Process IPN
        //saving the payment with the data of paypal

        //check if the payment was with the correct informations
        //session test
        session_start();

        //we get the id of the service or product that was payd from the session
        //for test just another id//
        $item_id_session = $_SESSION["item_id"];

        //we go to the db looking for the total amount that should be payd
        //just for test another amount
        $amount_session = $_SESSION["item_amount"];

        //we go to the db for the datails of the recent transaction
        $stmt = $con->prepare("SELECT * FROM paypal_payments WHERE item_no=? ORDER BY item_no ASC
        LIMIT 1");
        //execute query
        $stmt->execute(array($item_id_session));
        $data = $stmt->fetchAll();

        //compare data
        if(sizeof($data)!=0){
            foreach ($data as $value) {
                if($value["payment_status"]=="Completed"){
                    if($value["payment_amount"]==$amount){
                        //here we check that the payment was for the correct amount

                        //for change the state of the bil

                        $_SESSION["payment_status"] = $value["payment_status"];
                        $_SESSION["payment_amount"] = $value["payment_amount"];
                        $_SESSION["item_name"] = $value["item_name"];
                    }else{
                        $_SESSION['message_error']="Error: El monto cancelado no corresponde al monto real.";
                    }
                }else{
                    $_SESSION['message_error']="Error: La transaccion no se ha completado.";
                }
            }
        }else{
            $_SESSION['message_error']="Error: No se encontro registro de tal transaccion.";
        }
        //variables to add receiver_email ,contact_phone ,first_name ,last_name ,payment_date, mc_fee

        $conection = new Connection();
        $stmt = $conection->prepare("INSERT INTO paypal_payments(item_no , item_name, transaccion_id,
            payment_amount , currency_code, payment_status,payer_email) VALUES(?,?,?,?,?,?,?)");
        $stmt->execute(array($_POST["item_number"],$_POST["item_name"],$_POST["txn_id"],$_POST["mc_gross"],
        $_POST['mc_currency'] ,$_POST['payment_status'],$_POST['payer_email']));

        // This is an example for sending an automated email to the customer when they purchases an item for a specific amount:
        /*
        if ($_POST["item_name"] == "Example Item" && $_POST["mc_gross"] == 49.99 && $_POST["mc_currency"] == "USD" && $_POST["payment_status"] == "Completed") {
            $email_to = $_POST["first_name"] . " " . $_POST["last_name"] . " <" . $_POST["payer_email"] . ">";
            $email_subject = $test_text . "Completed order for: " . $_POST["item_name"];
            $email_body = "Thank you for purchasing " . $_POST["item_name"] . "." . "\r\n" . "\r\n" . "This is an example email only." . "\r\n" . "\r\n" . "Thank you.";
            mail($email_to, $email_subject, $email_body, "From: " . $from_email_address);
        }
        */
    }
} elseif ($enable_sandbox) {
    if ($_POST["test_ipn"] != 1) {
        $paypal_ipn_status = "RECEIVED FROM LIVE WHILE SANDBOXED";
    }
} elseif ($_POST["test_ipn"] == 1) {
    $paypal_ipn_status = "RECEIVED FROM SANDBOX WHILE LIVE";
}
if ($save_log_file) {
    // Create log file directory
    if (!is_dir($dated_log_file_dir)) {
        if (!file_exists($dated_log_file_dir)) {
            mkdir($dated_log_file_dir, 0777, true);
            if (!is_dir($dated_log_file_dir)) {
                $save_log_file = false;
            }
        } else {
            $save_log_file = false;
        }
    }
    // Restrict web access to files in the log file directory
    $htaccess_body = "RewriteEngine On" . "\r\n" . "RewriteRule .* - [L,R=404]";
    if ($save_log_file && (!is_file($log_file_dir . "/.htaccess") || file_get_contents($log_file_dir . "/.htaccess") !== $htaccess_body)) {
        if (!is_dir($log_file_dir . "/.htaccess")) {
            file_put_contents($log_file_dir . "/.htaccess", $htaccess_body);
            if (!is_file($log_file_dir . "/.htaccess") || file_get_contents($log_file_dir . "/.htaccess") !== $htaccess_body) {
                $save_log_file = false;
            }
        } else {
            $save_log_file = false;
        }
    }
    if ($save_log_file) {
        // Save data to text file
        file_put_contents($dated_log_file_dir . "/" . $test_text . "paypal_ipn_" . $date . ".txt", "paypal_ipn_status = " . $paypal_ipn_status . "\r\n" . "paypal_ipn_date = " . $timestamp . "\r\n" . $data_text . "\r\n", FILE_APPEND);
    }
}
if ($send_confirmation_email) {
    // Send confirmation email
    mail($confirmation_email_address, $test_text . "PayPal IPN : " . $paypal_ipn_status, "paypal_ipn_status = " . $paypal_ipn_status . "\r\n" . "paypal_ipn_date = " . $timestamp . "\r\n" . $data_text, "From: " . $from_email_address);
}
// Reply with an empty 200 response to indicate to paypal the IPN was received correctly
header("HTTP/1.1 200 OK");
