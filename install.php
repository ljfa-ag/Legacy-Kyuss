<?php
/**
 * Der Installationsassistent von Kyuss
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 */

header('Content-Type: text/xml; charset: utf-8');

echo '<?xml version="1.0" encoding="utf-8" ?>';

if($_GET['step'] == 3)
{
    define('HOST', $_GET['host']);
    define('USERNAME', $_GET['username']);
    define('PASSWORD', $_GET['password']);
    define('DB_NAME', $_GET['dbname']);
    define('PREFIX', $_GET['prefix']);
    $db = @new mysqli(HOST, USERNAME, PASSWORD, DB_NAME);
    if($db->connect_error)
        die('<maniacode noconfirmation="1">
<show_message>
    <message>Fehler beim Versuch der Verbindung zur Datenbank:
'.$db->connect_error.'</message>
</show_message>
</maniacode>');

    $str = "<?php
define('HOST', '".addslashes(HOST)."');
define('USERNAME', '".addslashes(USERNAME)."');
define('PASSWORD', '".addslashes(PASSWORD)."');
define('DB_NAME', '".addslashes(DB_NAME)."');
define('PREFIX', '".addslashes(PREFIX)."');";
    file_put_contents('connect.php', $str);
    
    $epr = PREFIX;
    $db->multi_query("SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";

CREATE TABLE IF NOT EXISTS `{$epr}_config` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `property` varchar(63) CHARACTER SET latin1 NOT NULL,
  `value` text CHARACTER SET latin1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `property` (`property`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `{$epr}_dico` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(63) COLLATE utf8_unicode_ci NOT NULL,
  `langcode` enum('en','cz','de','es','fr','hu','it','jp','kr','nl','pl','pt','ru','sk','zh') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`langcode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `{$epr}_navigation` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `order` smallint(5) unsigned NOT NULL,
  `imageid` varchar(63) COLLATE utf8_unicode_ci DEFAULT NULL,
  `submenu` smallint(6) DEFAULT NULL,
  `type` enum('url','manialink','textpage','page','submenu') COLLATE utf8_unicode_ci DEFAULT NULL,
  `target` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  `params` varchar(127) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `{$epr}_pages` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `folder_name` varchar(63) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(63) COLLATE utf8_unicode_ci NOT NULL,
  `desc` text COLLATE utf8_unicode_ci,
  `hasConfig` tinyint(1) NOT NULL DEFAULT '0',
  `rights` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `folder_name` (`folder_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `{$epr}_plugin_config` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `widget_id` smallint(5) unsigned DEFAULT NULL,
  `page` varchar(63) COLLATE utf8_unicode_ci DEFAULT NULL,
  `property` varchar(63) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `widget_id` (`widget_id`,`property`),
  UNIQUE KEY `page` (`page`,`property`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `{$epr}_shared_lists` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `list` varchar(63) COLLATE utf8_unicode_ci NOT NULL,
  `property` varchar(63) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `list` (`list`,`property`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `{$epr}_textpages` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(63) COLLATE utf8_unicode_ci NOT NULL,
  `textsize` tinyint(3) unsigned NOT NULL DEFAULT '3',
  `xml` text COLLATE utf8_unicode_ci,
  `widgets` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `{$epr}_themes` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `folder_name` varchar(63) CHARACTER SET latin1 NOT NULL,
  `name` varchar(63) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `folder_name` (`folder_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `{$epr}_used_widgets` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(63) COLLATE utf8_unicode_ci NOT NULL,
  `x` decimal(4,2) NOT NULL DEFAULT '0.00',
  `y` decimal(4,2) NOT NULL DEFAULT '0.00',
  `z` decimal(4,2) NOT NULL DEFAULT '0.00',
  `rights` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `{$epr}_users` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `password` varchar(34) COLLATE utf8_unicode_ci NOT NULL,
  `rank` tinyint(3) unsigned NOT NULL DEFAULT '255',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `{$epr}_widgets` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `folder_name` varchar(63) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(63) COLLATE utf8_unicode_ci NOT NULL,
  `desc` text COLLATE utf8_unicode_ci,
  `hasConfig` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `folder_name` (`folder_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
}
elseif($_GET['step'] == 4)
{
    require('connect.php');
    $db = @new mysqli(HOST, USERNAME, PASSWORD, DB_NAME);
    if($db->connect_error)
        die('<maniacode noconfirmation="1">
<show_message>
    <message>Fehler beim Versuch der Verbindung zur Datenbank:
'.$db->connect_error.'</message>
</show_message>
</maniacode>');
    $epr = PREFIX;
    $stmt = $db->prepare("INSERT INTO `{$epr}_config` (`property`, `value`) VALUES
('standard_theme', 'island_sunrise'),
('xml', ''),
('page_title', ?),
('global_widgets', '1,4'),
('kyuss_self', ?),
('shared_lists', ?),
('show_header', 1),
('permissions', ?),
('manialink_id', ?)");
    $lists = serialize(array('skins' => 2, 'tracks' => 2, 'gbook' => 1, 'links' => 1, 'news' => 1));
    $perms = serialize(array('users' => 0, 'general' => 0, 'rights' => 0, 'mysql' => 0, 'pages' => 0, 'widgets' => 0, 'themes' => 0, 'textpages' => 0, 'navi' => 0));
    $stmt->bind_param('sssss', $_GET['title'], $_GET['self'], $lists, $perms, uniqid());
    $stmt->execute();
    $stmt->close();
    
    $stmt = $db->prepare("INSERT INTO `{$epr}_users` SET `name` = ?, `password` = ?, `rank` = 0");
    $passwd = crypt($_GET['password']);
    $stmt->bind_param('ss', $_GET['username'], $passwd);
    $stmt->execute();
    $stmt->close();
    
    $db->multi_query("INSERT INTO `{$epr}_widgets` (`id`, `folder_name`, `name`, `desc`, `hasConfig`) VALUES
(1, 'login', 'Admin-Login', 'Zum direkten Login ins Adminpanel.', 0),
(2, 'shoutbox', 'Shoutbox', 'Eine Shoutbox', 0),
(3, 'showcase', 'Showcase', 'Zum Vorstellen der neuesten Strecke und des neuesten Skins', 1),
(4, 'donate', 'Donate', 'Zum Spenden', 1);

INSERT INTO `{$epr}_used_widgets` (`id`, `name`, `x`, `y`, `z`, `rights`) VALUES
(1, 'login', '39.50', '37.00', '2.00', 0),
(2, 'shoutbox', '61.00', '-17.00', '2.00', 0),
(3, 'showcase', '61.00', '-6.00', '0.00', 0),
(4, 'donate', '32.00', '-44.00', '1.00', 0);


INSERT INTO `{$epr}_pages` (`id`, `folder_name`, `name`, `desc`, `hasConfig`, `rights`) VALUES
(1, 'admin', 'Adminpanel', 'Die Konfigurationszentrale fur den gesamten Manialink', 0, 0),
(2, 'gbook', 'Gästebuch', 'Ein Gästebuch, in das Benutzer ihre Einträge hinienschreiben können.', 1, 0),
(3, 'theme', 'Theme-Auswahl', 'Eine Seite, in der die Benutzer ihr favorisiertes Theme auswählen können.', 0, 0),
(4, 'tracks', 'Strecken', 'Eine Seite, von der Besucher Strecken herunterladen können.', 1, 0),
(5, 'skins', 'Skins', 'Eine Seite, von der Besucher Fahrzeuge herunterladen können.', 1, 0),
(6, 'links', 'Links', 'Eine Seite, auf die man Links zu anderen Manialinks setzen kann.', 1, 0),
(7, 'news', 'News', 'Eine Seite, auf der Besucher Neuigkeiten eintragen können.', 1, 0);

INSERT INTO `{$epr}_themes` (`id`, `folder_name`, `name`) VALUES
(1, 'island_sunrise', 'Island Sunrise'),
(2, 'blau_h', 'Blau horizontal');

INSERT INTO `{$epr}_textpages` (`id`, `name`, `textsize`, `xml`, `widgets`) VALUES
(1, 'Startseite', 3, NULL, '2,3');

INSERT INTO `{$epr}_navigation` (`id`, `order`, `imageid`, `submenu`, `type`, `target`, `params`) VALUES
(1, 0, 'home', NULL, 'textpage', '1', NULL),
(2, 0, 'news', NULL, 'page', 'news', NULL),
(3, 1, 'tracks', NULL, 'submenu', '0', ''),
(4, 2, 'skins', NULL, 'page', 'skins', NULL),
(5, 3, 'gbook', NULL, 'page', 'gbook', ''),
(6, 4, 'links', NULL, 'page', 'links', NULL),
(7, 0, 'desert', 0, 'page', 'tracks', 'envi=desert'),
(8, 1, 'rally', 0, 'page', 'tracks', 'envi=rally'),
(9, 2, 'snow', 0, 'page', 'tracks', 'envi=snow'),
(10, 3, 'island', 0, 'page', 'tracks', 'envi=island'),
(11, 4, 'coast', 0, 'page', 'tracks', 'envi=coast'),
(12, 5, 'bay', 0, 'page', 'tracks', 'envi=bay'),
(13, 6, 'stadium', 0, 'page', 'tracks', 'envi=stadium');

INSERT INTO `{$epr}_dico` (`id`, `name`, `langcode`, `text`) VALUES
(1, 'nav-1', 'en', 'Home'),
(2, 'nav-2', 'en', 'News'),
(3, 'nav-3', 'en', 'Tracks'),
(4, 'nav-4', 'en', 'Skins'),
(5, 'nav-5', 'en', 'Guestbook'),
(6, 'nav-6', 'en', 'Links'),
(7, 'nav-7', 'en', 'Desert'),
(8, 'nav-8', 'en', 'Rally'),
(9, 'nav-9', 'en', 'Snow'),
(10, 'nav-10', 'en', 'Island'),
(11, 'nav-11', 'en', 'Coast'),
(12, 'nav-12', 'en', 'Bay'),
(13, 'nav-13', 'en', 'Stadium'),
(14, 'nav-1', 'de', 'Startseite'),
(15, 'nav-5', 'de', 'Gästebuch'),
(16, 'text-1', 'de', '\$oHerzlichen Glückwunsch!\$o\\n\\nKyuss ist nun eingerichtet. Über das Adminpanel können Sie nun weitere Einstellungen tätigen.'),
(17, 'text-1', 'en', '\$oCongratulations!\$o\\n\\nKyuss is now set up. You can do futher settings now over the admin panel.');");
    echo '<maniacode noconfirmation="1">';
    if(!unlink('install.php'))
        echo '<show_message>
        <message>Die Installation von Kyuss ist abgeschlossen. Bitte löschen Sie aus Sicherheitsgründen die install.php.</message>
        </show_message>';
    echo '<goto>
    <link>'.$_GET['self'].'</link>
    </goto>
    </maniacode>';
    exit;
}

echo '<manialink>
<timeout>0</timeout>
<quad posn="-64 48" sizen="128 96" image="./themes/island_sunrise/background.png"/>
<quad posn="-42 30 1" sizen="106 70" image="./themes/island_sunrise/content_bg.png"/>
<dico>
    <language id="de">
        <welcome>Willkommen zu Kyuss!</welcome>
        <welctext>Vielen Dank, dass Sie sich für Kyuss entschieden haben.
Dieser Assistent wird Sie durch die nötigen Schritte führen, um Kyuss einzurichten.

Kyuss ist freie Software, das heißt, Sie dürfen Kyuss frei benutzen, verändern und veröffentlich, solange Sie die Lizenz (GNU General Public License) beibehalten.</welctext>
        <step1>Schritt 1 von 3: Überprüfung</step1>
        <step2>Schritt 2 von 3: MySQL-Zugangsdaten</step2>
        <step3>Schritt 3 von 3: Allgemeine Einstellungen</step3>
        <check>Der Assistent prüft, ob Ihr Hoster die nötigen Voraussetzungen erfüllt, um Kyuss betreiben zu können.</check>
        <existant>$0f0Vorhanden</existant>
        <notexistant>$f00Nicht vorhanden</notexistant>
        <nojpeg>$f00Keine JPEG-Unterstützung</nojpeg>
        <activated>$0f0Aktiviert</activated>
        <notactivated>$ff0Nicht aktiviert</notactivated>
        <isok>Ihr Hoster erfüllt offenbar die nötigen Voraussetzungen für Kyuss.
Um Strecken von TMX hochladen zu können (optional), wird außerdem folgendes benötigt:</isok>
        <mysql>Kyuss benötigt für den Betrieb eine MySQL-Datenbank. Bitte geben Sie die Zugangsdaten für die Datenbank an:</mysql>
        <password>Passwort:</password>
        <dbname>Name der Datenbank:</dbname>
        <prefixinfo>Der Tabellennamenspräfix ist eine Zeichenkette, die dem Namen jeder Tabelle vorangestellt wird. Sollen mehrere Manialinks auf der selben Datenbank betrieben werden, muss der Präfix für jeden Manialink unterschiedlich sein.</prefixinfo>
        <prefix>Tabellennamenspräfix:</prefix>
        <access>Diese Zugangsdaten werden für den Login im Adminpanel benötigt. Sie können später geändert werden.</access>
        <self>URL oder Code zur index.php:</self>
        <title>Titel des Manialinks:</title>
    </language>
    <language id="en">
        <welcome>Welcome to Kyuss!</welcome>
        <welctext>Thank you for choosing Kyuss.
This wizard will guide you through the necessary steps to establish Kyuss.

Kyuss is free software, that means you are free to use, modify and publish Kyuss as long as you keep the license (GNU General Public License).</welctext>
        <step1>Step 1 of 3: Inspection</step1>
        <step2>Step 2 of 3: MySQL access</step2>
        <step3>Step 3 of 3: General configuration</step3>
        <check>The wizards checks whether your hoster meets the requirements to run Kyuss.
For uploading tracks from TMX (optional) the following is needed as well:</check>
        <existant>$0f0Available</existant>
        <notexistant>$f00Not available</notexistant>
        <nojpeg>$f00No JPEG support</nojpeg>
        <activated>$0f0Activated</activated>
        <notactivated>$ff0Not activated</notactivated>
        <isok>Your hoster apparently meets the requirements for Kyuss.</isok>
        <mysql>Kyuss needs a MySQL database to work. Please type in the access data for the database:</mysql>
        <password>Password:</password>
        <dbname>Database name:</dbname>
        <prefixinfo>The table name prefix is a string the name of every table is preceded by. If multiple manialinks are operated on the same database, the prefix must be different for each manialink.</prefixinfo>
        <prefix>Table name prefix:</prefix>
        <access>This access data is needed to log in into the admin panel. It can be changed later.</access>
        <self>URL or code to index.php:</self>
        <title>Title of the manialink:</title>
    </language>
</dico>
<frame posn="-38 26 2">';

$step = isset($_GET['step']) ? $_GET['step'] : 0;
$self = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/install.php';

switch($step)
{
case 0:
    echo '<label posn="0 0 0" style="TextRankingsBig" textid="welcome"/>
    <label posn="0 -5 0" textid="welctext" sizen="100" autonewline="1"/>
    <label posn="75 -57 0" text="Next" style="CardButtonMedium" manialink="'.$self.'?step='.($step+1).'"/>';
    break;
    
case 1:
    echo '<label posn="0 0 0" style="TextRankingsBig" textid="step1"/>
    <label posn="0 -5 0" textid="check" sizen="100" autonewline="1"/>
    <label posn="0 -12 0" text="PHP 5.2:" sizen="100" textsize="4"/>
    <label posn="0 -16 0" text="MySQLi:" sizen="100" textsize="4"/>
    <label posn="0 -20 0" text="GD:" sizen="100" textsize="4"/>
    <label posn="0 -34 0" text="allow_url_fopen:" sizen="100" textsize="4"/>';
    $isok = true;
    
    $phpversion = phpversion();
    if(version_compare($phpversion, '5.2') >= 0)
        echo '<label posn="21 -12 0" text="$0f0'.$phpversion.'" textsize="4"/>';
    else
    {
        echo '<label posn="21 -12 0" text="$f00'.$phpversion.'" textsize="4"/>';
        $isok = false;
    }
    if(file_exists('plugins_php5.3.php'))
    {
        if(version_compare($phpversion, '5.3') >= 0)
        {
            unlink('plugins.php');
            rename('plugins_php5.3.php', 'plugins.php');
        }
        else
            unlink('plugins_php5.3.php');
    }
    
    if(class_exists('mysqli'))
        echo '<label posn="21 -16 0" textid="existant" textsize="4"/>';
    else
    {
        echo '<label posn="21 -16 0" textid="notexistant" textsize="4"/>';
        $isok = false;
    }
    
    if(function_exists('gd_info'))
    {
        $gdinfo = gd_info();
        if($gdinfo['JPEG Support'] || $gdinfo['JPG Support'])
            echo '<label posn="21 -20 0" textid="existant" textsize="4"/>';
        else
        {
            echo '<label posn="21 -20 0" textid="nojpeg" textsize="4"/>';
            $isok = false;
        }
    }
    else
    {
        echo '<label posn="21 -20 0" textid="notexistant" textsize="4"/>';
        $isok = false;
    }
    
    if(@ini_get('allow_url_fopen'))
    {
        echo '<label posn="21 -34 0" textid="activated" textsize="4"/>';
    }
    else
    {
        echo '<label posn="21 -34 0" textid="notactivated" textsize="4"/>';
    }
    
    if($isok)
        echo '<label posn="0 -27 0" textid="isok" sizen="100" autonewline="1"/>
        <label posn="75 -57 0" text="Next" style="CardButtonMedium" manialink="'.$self.'?step='.($step+1).'"/>';
    break;
    
case 2:
    echo '<label posn="0 0 0" style="TextRankingsBig" textid="step2"/>
    <label posn="0 -5 0" textid="mysql" sizen="100" autonewline="1"/>
    <label posn="0 -13 0" text="Host:"/>
    <entry posn="22 -13 0" sizen="20 2.5" default="localhost" name="host"/>
    <label posn="0 -16.5 0" text="Username:"/>
    <entry posn="22 -16.5 0" sizen="20 2.5" name="username"/>
    <label posn="0 -20 0" textid="password"/>
    <entry posn="22 -20 0" sizen="20 2.5" name="password"/>
    <quad posn="22 -20 1" sizen="20 2.5" bgcolor="444"/>
    <label posn="0 -23.5 0" textid="dbname"/>
    <entry posn="22 -23.5 0" sizen="20 2.5" name="dbname"/>
    <label posn="0 -28 0" textid="prefixinfo" sizen="100" autonewline="1"/>
    <label posn="0 -37.5 0" textid="prefix"/>
    <entry posn="22 -37.5 0" sizen="20 2.5" default="kyuss" name="prefix"/>
    <label posn="75 -57 0" text="Next" style="CardButtonMedium" manialink="'.$self.'?step='.($step+1).'&amp;host=host&amp;username=username&amp;password=password&amp;dbname=dbname&amp;prefix=prefix" addplayerid="1"/>';
    break;

case 3:
    echo '<label posn="0 0 0" style="TextRankingsBig" textid="step3"/>
    <label posn="0 -5 0" textid="access" sizen="100" autonewline="1"/>
    <label posn="0 -9 0" text="Username:"/>
    <entry posn="12 -9 0" sizen="20 2.5" name="username" default="'.$_GET['playerlogin'].'"/>
    <label posn="0 -12.5 0" textid="password"/>
    <entry posn="12 -12.5 0" sizen="20 2.5" name="password"/>
    <quad posn="12 -12.5 1" sizen="20 2.5" bgcolor="444"/>
    
    <label posn="0 -17 0" textid="self"/>
    <entry posn="0 -20.5 0" sizen="40 2.5" name="self" default="http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/index.php"/>
    <label posn="0 -25 0" textid="title"/>
    <entry posn="0 -28.5 0" sizen="40 2.5" name="title"/>
    <label posn="75 -57 0" text="Next" style="CardButtonMedium" manialink="'.$self.'?step='.($step+1).'&amp;username=username&amp;password=password&amp;self=self&amp;title=title"/>';
    break;
}

echo '</frame>
</manialink>';