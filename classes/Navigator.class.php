<?php
class Navigator {

    public static function submitInput($_email_message) {
 
        $email_to = "letsplayglobalgames@gmail.com";
        $email_subject = "LetsPlayGlobalGames Feedback";
        $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
 
        mail($email_to, $email_subject, $_email_message);
    }

}