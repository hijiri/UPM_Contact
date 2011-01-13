<?php
/**
 * Loggix_Module_Contact - UPM Contact Module for Loggix
 *
 * Module File
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

/**
 * Include Module class
 */
require_once $pathToIndex . '/lib/Loggix/Module.php';

/**
 * @package   Contact
 */
class UPM_Contact extends Loggix_Module
{
    const MODULE_DIR         = 'modules/contact';
    const CONTACT_THEME_PATH = '/modules/contact/theme/';

    /**
     * Get Host Name
     *
     * @return string
     */
    public function getHostName()
    {
        $host = isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : '';
        $addr = $_SERVER['REMOTE_ADDR'];
        if (($host == '') || ($host == $addr)) {$host = gethostbyaddr($addr);}
        if ($host == '') {$host = $addr;}
        return $host;
    }

    /**
     * Get Mail Header
     *
     * @param  string $name
     * @param  string $email
     * @return string
     */
    public function getMailHeader($name, $email)
    {
        $header     = 'From: ' . mb_encode_mimeheader(html_entity_decode($name), 'UTF-8', 'B') . '<' . $email . '>' . "\n";
        $header    .= 'X-Mailer: PHP Version ' . phpversion() . '/UPM Contact Module for Loggix' . "\n";
        $header    .= 'Content-Type: text/plain; charset=utf-8' . "\n";
        $header    .= 'Content-Transfer-Encoding: 8bit' . "\n";
        return $header;
    }

    /**
     * Get Mail Subject
     *
     * @param  string $subject
     * @return string
     */
    public function getMailSubject($subject)
    {
        return mb_encode_mimeheader(html_entity_decode($subject), 'UTF-8', 'B');
    }

    /**
     * Get Mail Body
     *
     * @param  string $name
     * @param  string $email
     * @param  string $message
     * @return string
     */
    public function getMailBody($name, $email, $message)
    {
        $body       = 'Date       : ' . date(DATE_W3C) . "\n";
        $body      .= 'Name       : ' . $name . "\n";
        $body      .= 'E-Mail     : ' . $email . "\n";
        $body      .= 'Host       : ' . $this->getHostName() . "\n";
        $body      .= 'User Agent : ' . $_SERVER['HTTP_USER_AGENT'] . "\n";
        $body      .= 'Message    : ' . "\n" . $message;
        return mb_convert_encoding(html_entity_decode($body), 'UTF-8', 'auto');
    }

    /**
     * Show Information
     *
     * @param  string $title
     * @param  string $class
     * @param  string $information
     * @return string
     */
    public function showInformation($title, $class, $information)
    {
        global $pathToIndex, $item;

        $item['contact']['title']       = $title;
        $item['contact']['class']       = $class;
        $item['contact']['information'] = $information;

        $contactFormTheme = $pathToIndex . self::CONTACT_THEME_PATH . 'default.html';
        $contents = new Loggix_View($contactFormTheme);
        $contents->assign('session_state', $sessionState);
        $contents->assign('item', $item);
        $contents->assign('lang', $lang);
        
        return $contents->render();
    }

    /**
     * Show Form
     *
     * @param  string $name
     * @param  string $email
     * @param  string $subject
     * @param  string $message
     * @return string
     */
    public function showContactForm($mode, $name, $email, $subject, $message)
    {
        global $pathToIndex, $lang, $item, $module;

        $item['cd']                 = $pathToIndex;
        $item['contact']['name']    = $name;
        $item['contact']['email']   = $email;
        $item['contact']['subject'] = $subject;
        $item['contact']['message'] = $message;

        if ($mode == 'pre') {
            $contactFormTheme = $pathToIndex . self::CONTACT_THEME_PATH . 'preview-form.html';
        } else {
            $contactFormTheme = $pathToIndex . self::CONTACT_THEME_PATH . 'post-form.html';
        }
        $contents = new Loggix_View($contactFormTheme);
        $contents->assign('session_state', $sessionState);
        $contents->assign('item', $item);
        $contents->assign('lang', $lang);
        $contents->assign('module', $module);
        
        return $contents->render();
    }
    
}

// Create a "Powerd by ..." link
$module['UPM']['contact']['powerd_by'] = '<p>Powerd by <a href="http://tkns.homelinux.net/" title="UPM Contact Module">UPM Contact</a></p>';
