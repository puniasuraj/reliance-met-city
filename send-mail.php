<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Honeypot spam protection
    if (!empty($_POST['_honey'])) {
        header("Location: thank-you");
        exit;
    }

    $to = "puniasuraj150@gmail.com";
    $subject = isset($_POST['_subject']) ? strip_tags($_POST['_subject']) : "New Inquiry - MET City Jhajjar";
    
    // 2. Build email body dynamically from all POST fields
    $email_body = "New Inquiry Details:\n";
    $email_body .= "----------------------------------------\n\n";
    
    foreach ($_POST as $key => $value) {
        // Skip hidden settings fields (starting with '_')
        if (strpos($key, '_') === 0) {
            continue;
        }
        
        $label = ucfirst(str_replace(['_', '-'], ' ', $key));
        $val = strip_tags(trim($value));
        $email_body .= "$label: $val\n";
    }
    
    $email_body .= "\n----------------------------------------\n";
    $email_body .= "Submitted from: " . $_SERVER['HTTP_REFERER'] . "\n";
    $email_body .= "Sender IP: " . $_SERVER['REMOTE_ADDR'] . "\n";

    // 3. Email headers
    $domain = $_SERVER['HTTP_HOST'];
    $from_email = "no-reply@" . $domain;
    
    $headers = "From: MET City Info <" . $from_email . ">\r\n";
    $headers .= "Reply-To: " . $from_email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // 4. Send the email
    $success = mail($to, $subject, $email_body, $headers);
    
    // 5. Redirect to thank-you page
    header("Location: thank-you");
    exit;
} else {
    // If not POST, redirect to homepage
    header("Location: /");
    exit;
}
?>
