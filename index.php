<?php
/*
   Kyuss - Das freie Manialink-CMS
   Copyright (C) 2011 ljfa-ag

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/
/**
 * Kyuss - Mächtig, aber simpel
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 * @version 1.1
 */

header('Content-Type: text/xml; charset: utf-8');

session_set_cookie_params(30*24*60*60);
session_start();

require('plugins.php');
require('connect.php');
/**
 * Das MySQLi-Objekt der Datenbank
 * @global mysqli $_db
 */
$_db = @new mysqli(HOST, USERNAME, PASSWORD, DB_NAME);
if($_db->connect_error)
	die('<?xml version="1.0" encoding="utf-8" ?>
<manialink>
<timeout>0</timeout>
<label halign="center">Error: '.$_db->connect_error.'</label>
</manialink>');
$_db->set_charset('utf-8');

/**
 * URL oder Maniacode zur index.php
 */
define('KYUSS', getGeneralConfig('kyuss_self'));
/**
 * Die URL zum Kyuss-Ordner
 */
define('DIR', 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/');
/**
 * Die Kyuss-Version
 */
define('VERSION', '1.1.1');
/**
 * Die Update-URL
 */
define('UPDATE_URL', 'http://ljfa-ag.tm-clan.de/kyuss_update.php');

if(isset($_GET['maniacode'])
&& strpos($_GET['maniacode'], '..') === false
&& (strpos($_GET['maniacode'], 'pages') === 0 || strpos($_GET['maniacode'], 'widgets') === 0)
)
{
	require("$_GET[maniacode].php");
	exit;
}

echo '<?xml version="1.0" encoding="utf-8" ?>
<!-- '.getGeneralConfig('manialink_id').' -->
<manialink>
<timeout>0</timeout>';

/**
 * Das Array der Sprach-IDs
 * @global array $_dico
 */
$_dico = Array(
    'en' => Array(),
    'cz' => Array(),
    'de' => Array(),
    'es' => Array(),
    'fr' => Array(),
    'hu' => Array(),
    'it' => Array(),
    'jp' => Array(),
    'kr' => Array(),
    'nl' => Array(),
    'pl' => Array(), 
    'pt' => Array(),
    'ru' => Array(),
    'sk' => Array(),
    'zh' => Array()
);

if(isset($_GET['settheme']))
    $_SESSION['user_theme'] = $theme_name = $_GET['settheme'];
elseif(isset($_SESSION['user_theme']) && file_exists("themes/$_SESSION[user_theme]/theme.php"))
    $theme_name = $_SESSION['user_theme'];
else
    $theme_name = $_db->query('SELECT * FROM `'.PREFIX.'_config` WHERE `property` = "standard_theme"')->fetch_object()->value;
    
/**
 * Das verwendete Theme
 * @global array $_theme
 */
$_theme = include("themes/$theme_name/theme.php");
$_theme['foldername'] = "./themes/$theme_name";

echo $_theme['custom_xml'];
if($_GET['action'] != 'general') //Um XML-Synaxfehler korrigieren zu können, wird in der Sektion "Allgemeine Einstellungen" des Adminpanels kein Custom-XML angezeigt.
    echo getGeneralConfig('xml');
if(!is_null($_theme['title_posn']))
    echo "<label posn='$_theme[title_posn] 1' textsize='$_theme[title_textsize]' textcolor='$_theme[title_textcolor]'>".getGeneralConfig('page_title').'</label>';
if(!is_null($_theme['header_posn']) && getGeneralConfig('show_header') != '0')
    echo "<quad posn='$_theme[header_posn] 1' sizen='$_theme[header_sizen]' image='./data/images/header.png'/>";

if(!is_null($_theme['content_bg']))
    echo "<quad posn=\"$_theme[content_bg_posn] 1\" sizen=\"$_theme[content_bg_sizen]\" image=\"$_theme[foldername]/$_theme[content_bg]\" />";
echo "<frame posn=\"$_theme[content_posn] 2\">
<format textcolor=\"$_theme[content_textcolor]\"/>";

if($_GET['p'] == 'admin')
    require('admin.php');
elseif(isset($_GET['p']))
{
    if(!file_exists("pages/$_GET[p]/page.php"))
    {
        $_dico['de']['err404'] = 'Fehler 404: Die angegebene Seite existiert nicht!';
		$_dico['en']['err404'] = 'Error 404: This page does not exist!';
        echo '<label style="TextCardRaceRank" textid="err404"/>';
    }
        else
        {
    	require_once("pages/$_GET[p]/page.php");
    	$page = new $_GET['p']($_GET['p']);
    	try
    	{
    		$page->show();
    	}
    	catch(exception $ex)
    	{
    		$_dico['de']['ex'] = "Die Seite '$_GET[p]' hat einen unbehandelten Ausnahmefehler verursacht:\n\$o".$ex->getMessage()."\$z\nStack-Trace:\n".$ex->getTraceAsString();
    		$_dico['en']['ex'] = "The page '$_GET[p]' has thrown an uncaught exception:\n\$o".$ex->getMessage()."\$z\nStack trace:\n".$ex->getTraceAsString();
    		echo '<label textid="ex"/>';
    	}
    }
}
else
    require('textpage.php');
echo "</frame>";

include('navi.php');
widget::includeWidgets(getGeneralConfig('global_widgets'));

echo "<quad posn=\"-64 48\" sizen=\"128 96\" image=\"$_theme[foldername]/$_theme[bgimage]\" />
<label posn=\"63 -47 1\" halign=\"right\" valign=\"bottom\" text=\"Kyuss ".VERSION."\" textsize=\"1\" />";

include('lang.php');
?>
</manialink>