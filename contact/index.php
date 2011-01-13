<?php
/**
 * Loggix_Module_Contact - UPM Contact Module for Loggix
 *
 * Main File
 *
 * @package      Contact
 * @copyright    Copyright (C) UP!
 * @creator-code UPM
 * @author       hijiri
 * @link         http://tkns.homelinux.net/
 * @license      http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @since        2010.05.10
 * @version      10.5.13
 */

// SETTING BEGIN
// Send to
$sendto = 'example@example.com';
// Max length of a String
$strmax = 3000;
// SETTING END

/**
 * Include Contact Module class
 */
$pathToIndex = '../..';
require_once $pathToIndex . '/modules/contact/lib/UPM_Contact.php';

$app = new UPM_Contact;
$config       = $app->getConfigArray();
$sessionState = $app->getSessionState();
$app->getModuleLanguage('contact');

try {

    $app->insertSafe();

    // On submit
    if (isset($_POST['preview']) || isset($_POST['send'])) {
        // Pickup data from $_POST
        $name    = (isset($_POST['name']))    ? htmlspecialchars($_POST['name'])    : $lang['contact']['default_name'];
        $email   = (isset($_POST['email']))   ? htmlspecialchars($_POST['email'])   : $lang['contact']['default_email'];
        $subject = (isset($_POST['subject'])) ? htmlspecialchars($_POST['subject']) : $lang['contact']['default_subject'];
        $message = (isset($_POST['message'])) ? htmlspecialchars($_POST['message']) : $lang['contact']['default_message'];

        // Errors
        if ($name == '') {
            $contactTitle = 'No Name';
            $content      = $app->showInformation($contactTitle, 'warning', $lang['contact']['error_no_name'])
                          . $app->showContactForm('post', $name, $email, $subject, $message);
        } elseif ($name == $lang['contact']['default_name']) {
            $contactTitle = 'Not modified Name';
            $content      = $app->showInformation($contactTitle, 'warning', $lang['contact']['error_not_modified_name'])
                          . $app->showContactForm('post', $name, $email, $subject, $message);
        } elseif ($email == '') {
            $contactTitle = 'No E-Mail Address';
            $content      = $app->showInformation($contactTitle, 'warning', $lang['contact']['error_no_email'])
                          . $app->showContactForm('post', $name, $email, $subject, $message);
        } elseif (($email != '') && ($email == $lang['contact']['default_email'])) {
            $contactTitle = 'Not modified E-Mail';
            $content      = $app->showInformation($contactTitle, 'warning', $lang['contact']['error_not_modified_email'])
                          . $app->showContactForm('post', $name, $email, $subject, $message);
        } elseif (($email != '') && (!ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email))) {
            $contactTitle = 'Invalid E-Mail Address';
            $content      = $app->showInformation($contactTitle, 'warning', $lang['contact']['error_invalid_email'])
                          . $app->showContactForm('post', $name, $email, $subject, $message);
        } elseif ($subject == '') {
            $contactTitle = 'No Subject';
            $content      = $app->showInformation($contactTitle, 'warning', $lang['contact']['error_no_subject'])
                          . $app->showContactForm('post', $name, $email, $subject, $message);
        } elseif ($subject == $lang['contact']['default_subject']) {
            $contactTitle = 'Not modified Subject';
            $content      = $app->showInformation($contactTitle, 'warning', $lang['contact']['error_not_modified_subject'])
                          . $app->showContactForm('post', $name, $email, $subject, $message);
        } elseif ($message == '') {
            $contactTitle = 'No Message';
            $content      = $app->showInformation($contactTitle, 'warning', $lang['contact']['error_no_message'])
                          . $app->showContactForm('post', $name, $email, $subject, $message);
        } elseif ($message == $lang['contact']['default_message']) {
            $contactTitle = 'Not modified Message';
            $content      = $app->showInformation($contactTitle, 'warning', $lang['contact']['error_not_modified_message'])
                          . $app->showContactForm('post', $name, $email, $subject, $message);
        } elseif ($strmax < mb_strlen($name.$email.$subject.$message)) {
            $contactTitle = 'Too many data';
            $content      = $app->showInformation($contactTitle, 'warning', $lang['contact']['error_too_many_data'])
                          . $app->showContactForm('post', $name, $email, $subject, $message);
        // Preview or Send
        } else {
            // Preview
            if ($_POST['preview'] == $lang['contact']['preview']) {
                $contactTitle = 'Preview';
                $content      = $app->showInformation($contactTitle, 'info', $lang['contact']['preview_message'])
                              . $app->showContactForm('pre', $name, $email, $subject, $message);

             // Send
            } elseif ($_POST['send'] == $lang['contact']['send']) {
                // Change internal encoding
                ini_set("mbstring.internal_encoding","UTF-8");
            
                // Success
                if (mail($sendto, $app->getMailSubject($subject), $app->getMailBody($name, $email, $message), $app->getMailHeader($name, $email), "-f $sendto")) {
                    $contactTitle = 'Success!';
                    $content      = $app->showInformation($contactTitle, 'info', $lang['contact']['send_success']);
                // Failure
                } else {
                    $contactTitle = 'Failure!';
                    $content      = $app->showInformation($contactTitle, 'critical', $lang['contact']['send_failure']);
                }

                // Display form
                $content .= $app->showContactForm('post', $lang['contact']['default_name'],
                                            $lang['contact']['default_email'],
                                            $lang['contact']['default_subject'],
                                            $lang['contact']['default_message']);
            } else {
                throw new Exception();
            }
        }

    // Display form
    } else {
        $contactTitle = 'Contact';
        $content      = $app->showInformation($contactTitle, 'info', $lang['contact']['announce'])
                      . $app->showContactForm('post', $lang['contact']['default_name'], 
                                              $lang['contact']['default_email'],
                                              $lang['contact']['default_subject'],
                                              $lang['contact']['default_message']);
    }

} catch (Exception $e) {
    $contactTitle = '404 Not Found';
    $templateFile = $pathToIndex . '/theme/errors/data-not-found.html';
    $contentsView = new Loggix_View($templateFile);
    $content      = $contentsView->render();
}

$item = array(
    'title'    => $app->setTitle($contactTitle),
    'contents' => $content,
    'result'   => '',
    'pager'    => ''
);

$app->display($item, $sessionState);

