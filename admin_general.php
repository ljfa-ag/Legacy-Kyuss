<?php
/**
 * Allgemeine Einstellungen
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 */
if(!$sec)
    exit;
$_dico['en'] += Array(
    'gens' => 'Miscellaneous settings',
    'title' => 'Page title:',
    'thisurl' => "URL/Code to\nthe Manialink:",
    'save' => '$fffSave',
    'customxml' => '$fffEdit additional custom XML',
    'chbanner' => '$fffChange banner',
    'upload' => '$fffUpload',
    'imginfo' => 'Supported filetypes: png',
    'delbanner' => '$fffReset banner to default',
    'showbanner' => '$fffShow banner',
    'hidebanner' => '$fffHide banner',
    'bannerinfo' => "On changes to the banner, the browser\nmust eventually be closed and\nreopened",
    'globalwv' => '$fffManage global widgets',
    'configure' => '$fffConfigure',
    'choose' => '$fffChoose',
    'rights' => 'Rights:',
    'endc' => '$000End config',
    'chwidget' => 'Choose widget',
    'rightsv' => '$fffManage rights',
    'users' => 'Manage users',
    'mysql' => '$fffEdit MySQL-Settings',
    'pages' => 'Install/delete pages',
    'widgets' => 'Manage global widgets',
    'themes' => 'Manage themes',
    'textpages' => 'Edit textpages',
    'navi' => 'Edit navigation',
    'rightsinfo' => 'The numbers that are set here and at the widgets and pages, tell, how big the rights number of the user maximally may be, so that he can reach the according menu point. This number goes from 0 to 255. The smaller the ights number is, the more rights the user has, the highest administrator has the rank 0.',
    'host' => 'Host name of the Server:',
    'dbname' => 'Name of the database:',
    'prefix' => 'Table name prefix:',
    'mysqlinfo' => 'If the settings made here are wrong, they can be altered by manually changing connect.php.',
    'chscf' => 'Change successfull',
    'plugins' => 'Manage plugins'
);
$_dico['de'] += Array(
    'gens' => 'Sonstige Einstellungen',
    'title' => 'Seitentitel:',
    'thisurl' => "URL/Code zum\nManialink:",
    'save' => '$fffSpeichern',
    'customxml' => '$fffZusätzliches benutzerdefiniertes XML bearbeiten',
    'chbanner' => '$fffBanner ändern',
    'upload' => '$fffHochladen',
    'imginfo' => 'Unterstützte Dateitypen: png',
    'delbanner' => '$fffBanner auf Standard zurücksetzen',
    'showbanner' => '$fffBanner anzeigen',
    'hidebanner' => '$fffBanner verstecken',
    'bannerinfo' => "Bei Änderungen am Banner muss der\nBrowser möglicherweise geschlossen\nund wieder geöffnet werden",
    'globalwv' => '$fffGlobale Widgets verwalten',
    'configure' => '$fffKonfigurieren',
    'choose' => '$fffWählen',
    'rights' => 'Rechte:',
    'endc' => '$000Konfig. beenden',
    'chwidget' => 'Widget auswählen',
    'rightsv' => '$fffRechte verwalten',
    'users' => 'Benutzer verwalten',
    'mysql' => '$fffMySQL-Zugangsdaten bearbeiten',
    'pages' => 'Seiten installieren/löschen',
    'widgets' => 'Globale Widgets verwalten',
    'themes' => 'Themes verwalten',
    'textpages' => 'Textseiten bearbeiten',
    'navi' => 'Navigation bearbeiten',
    'rightsinfo' => 'Die hier und bei den Widgets und Seiten eingestellten Werte geben an, wie groß die Rechtezahl eines Benutzers höchstens sein darf, damit er den entsprechenden Menüpunkt des Adminpanels erreichen kann. Diese Zahl liegt zwischen 0 und 255. Je kleiner die Rechtezahl eines Benutzers, desto mehr Rechte hat er, der höchste Administrator hat den Rang 0.',
    'host' => 'Hostname des Servers:',
    'dbname' => 'Name der Datenbank:',
    'prefix' => 'Tabellen-Namenspräfix:',
    'mysqlinfo' => 'Sollten die hier gemachten Einstellungen falsch sein, können sie auch durch manuelle Änderung der connect.php geändert werden.',
    'chscf' => 'Änderung erfolgreich',
    'plugins' => 'Plugins verwalten'
);

if(isset($_GET['title']))
{
    setGeneralConfig('page_title', $_GET['title']);
    setGeneralConfig('kyuss_self', $_GET['thisurl']);
}
elseif(isset($_GET['setcustomxml']))
    setGeneralConfig('xml', $_GET['setcustomxml']);
elseif(isset($_GET['delbanner']))
    copy('data/images/default_header.png', 'data/images/header.png');
elseif(isset($_GET['showbanner']))
    setGeneralConfig('show_header', $_GET['showbanner']);
elseif(isset($_GET['upbanner']))
    copy('php://input', 'data/images/header.png');

if(isset($_GET['customxml']))
{
    echo '<label posn="0 0 0" style="TextRankingsBig" textid="customxml"/>
    <entry posn="0 -5 0" sizen="100 43" default="'.htmlspecialchars(getGeneralConfig('xml')).'" autonewline="1" name="xml" textsize="2"/>
    <quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;setcustomxml=xml"/>
    <label posn="10 -52 0" valign="center" halign="center" text="Save" textcolor="000"/>
    <quad posn="32 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=general"/>
    <label posn="32 -52 0" valign="center" halign="center" text="Abort" textcolor="000"/>';
    return;
}
elseif(isset($_GET['chbanner']))
{
    echo '<label posn="0 0 0" style="TextRankingsBig" textid="chbanner"/>
    <fileentry posn="0 -5 0" sizen="30 2.5" name="image" default="Durchsuchen..." folder=""/>
    <label posn="0 -8.5 0" style="TextCardScores2" textid="upload" manialink="POST('.KYUSS.'?p=admin&amp;action=general&amp;upbanner,image)"/>
    <label posn="0 -13 0" textid="imginfo"/>';
    return;
}
/*elseif(isset($_GET['globalwv']))
{
    if(isset($_GET['chpos']))
    {
        $cpos = explode(' ', $_theme['content_posn']);
        for($x = -64-$cpos[0]; $x < 64-$cpos[0]; $x += 4)
            for($y = -48-$cpos[1]+4; $y <= 48-$cpos[1]; $y += 4)
                echo '<label posn="'.$x.' '.$y.' 5" sizen="4 4" text=" " focusareacolor1="0000" focusareacolor2="fff5" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;globalwv&amp;widget='.$_GET['widget'].'&amp;chx='.($x+$cpos[0]).'&amp;chy='.($y+$cpos[1]).'"/>';
    }
    if(isset($_GET['addwidget']) && $rank <= $perms['widgets'])
    {
        if($_GET['addwidget'] == '')
        {
            $y = 0;
            echo '<label posn="0 0 0" style="TextRankingsBig" textid="chwidget"/>';
            $query = $_db->query("SELECT * FROM `".PREFIX."_widgets`");
            while($row = $query->fetch_object())
            {
                echo '<label posn="0 '.(-5-3.5*$y).' 0" text="$fff'.$row->name.'" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;globalwv&amp;addwidget='.$row->folder_name.'"/>';
                $y++;
            } 
            return;
        }
        
        $_db->query("INSERT INTO `".PREFIX."_used_widgets` SET `name` = '".$_db->real_escape_string($_GET['addwidget'])."'");
        $wadded = $_db->insert_id;
        $widgets = getGeneralConfig('global_widgets');
        $widgets = (!$widgets) ? $wadded : $widgets.','.$wadded;
        setGeneralConfig('global_widgets', $widgets);
    }
    if(isset($_GET['widget']) OR isset($wadded))
    {
        $widget = isset($_GET['widget']) ? intval($_GET['widget']) : $wadded;
        $row = $_db->query("SELECT * FROM `".PREFIX."_used_widgets` WHERE `id` = $widget")->fetch_object();
        if(isset($_GET['x']) && $rank <= $row->rights)
        {
            $query = $_db->prepare("UPDATE `".PREFIX."_used_widgets` SET `x` = ?, `y` = ?, `z` = ?, `rights` = ? WHERE `id` = ?");
            $query->bind_param('dddii', $_GET['x'], $_GET['y'], $_GET['z'], $_GET['rechte'], $widget);
            $query->execute();
            $query->close();
        }
        elseif(isset($_GET['chx']) && $rank <= $row->rights)
        {
            $query = $_db->prepare("UPDATE `".PREFIX."_used_widgets` SET `x` = ?, `y` = ? WHERE `id` = ?");
            $query->bind_param('ddi', $_GET['chx'], $_GET['chy'], $widget);
            $query->execute();
            $query->close();
        }
        elseif(isset($_GET['configure']) && $rank <= $row->rights)
        {
            include_once("widgets/$row->name/widget.php");
            $widget = new $row->name($row->name, true);
            $widget->widgetid = $widgetid;
            try
            {
                $save = $widget->configure();
            }
            catch(exception $ex)
            {
                $_dico['de']['ex'] = "Das Konfigurationsseite von '$row->name' hat einen unbehandelten Ausnahmefehler verursacht:\n\$o".$ex->getMessage()."\$z\nStack-Trace:\n".$ex->getTraceAsString();
                $_dico['en']['ex'] = "The configuration page of '$row->name' has thrown an uncaught exception:\n\$o".$ex->getMessage()."\$z\nStack trace:\n".$ex->getTraceAsString();
                echo '<label textid="ex" textsize="2"/>';
            }
            echo '<quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;globalwv&amp;widget='.$_GET['widget'].'"/>
            <label posn="10 -52 0" valign="center" halign="center" textid="endc" textcolor="000"/>';
            return;
        }
        elseif(isset($_GET['askdelwidg']) && $rank <= $row->rights)
        {
            $row = $_db->query("SELECT `name`, `rights` FROM `".PREFIX."_used_widgets` WHERE `id` = ".intval($_GET['widget']))->fetch_object();
            $_dico['en']['widgdel'] = "Do you really want to remove the widget '$row->name'?\n\$h[".KYUSS."?p=admin&action=general&globalwv&delwidg=$_GET[widget]]Yes\$h   \$h[".KYUSS."?p=admin&action=general&globalwv&widget=$_GET[widget]]No";
            $_dico['de']['widgdel'] = "Möchten Sie das Widget '$row->name' wirklich entfernen?\n\$h[".KYUSS."?p=admin&action=general&globalwv&delwidg=$_GET[widget]]Ja\$h   \$h[".KYUSS."?p=admin&action=general&globalwv&widget=$_GET[widget]]Nein";
            echo '<label posn="0 0 0" textid="widgdel"/>';
            return;
        }
        $row = $_db->query("SELECT * FROM `".PREFIX."_used_widgets` WHERE `id` = $widget")->fetch_object();
        $row2 = $_db->query("SELECT * FROM `".PREFIX."_widgets` WHERE `folder_name` = '$row->name'")->fetch_object();
        echo '<label posn="0 0 0" style="TextRankingsBig" text="'.$row2->name.'"/>';
        if($rank <= $row->rights)
        {
            echo '<label posn="0 -5 0" text="Position:"/>
            <entry posn="10 -5 0" sizen="6 2.5" default="'.$row->x.'" name="x"/>
            <label posn="13 -8 0" sizen="6" halign="center" text="X"/>
            <entry posn="17 -5 0" sizen="6 2.5" default="'.$row->y.'" name="y"/>
            <label posn="20 -8 0" sizen="6" halign="center" text="Y"/>
            <entry posn="24 -5 0" sizen="6 2.5" default="'.$row->z.'" name="z"/>
            <label posn="27 -8 0" sizen="6" halign="center" text="Z"/>
            <label posn="35 -5 0" halign="center" textid="choose" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;globalwv&amp;widget='.$widget.'&amp;chpos"/>
            <label posn="0 -12 0" textid="rights"/>
            <entry posn="10 -12 0" sizen="6 2.5" default="'.$row->rights.'" name="rechte"/>
            <label posn="0 -16 0" textid="save" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;globalwv&amp;widget='.$widget.'&amp;x=x&amp;y=y&amp;z=z&amp;rechte=rechte"/>';
            if($row2->hasConfig != 0)
                echo '<label posn="0 -19.5 0" textid="configure" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;globalwv&amp;widget='.$widget.'&amp;configure"/>';
            echo '<label posn="0 -23 0" textid="delete" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;globalwv&amp;widget='.$widget.'&amp;askdelwidg"/>';
        }
        echo '<label posn="0 -26.5 0" textid="tcs2back" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;globalwv"/>';
        return;
    }
    elseif(isset($_GET['delwidg']))
    {
        $_db->query("DELETE FROM `".PREFIX."_used_widgets` WHERE `id` = ".intval($_GET['delwidg']));
        $_db->query("DELETE FROM `".PREFIX."_plugin_config` WHERE `widget_id` = ".intval($_GET['delwidg']));
        $widgets = explode(',', getGeneralConfig('global_widgets'));
        $id = array_search($_GET['delwidg'], $widgets);
        unset($widgets[$id]);
        $widgets = implode(',', $widgets);
        setGeneralConfig('global_widgets', $widgets);
    }
    echo '<label posn="0 0 0" style="TextRankingsBig" textid="globalwv"/>';
    $widgets = explode(',', getGeneralConfig('global_widgets'));
    foreach($widgets as $uwid)
    {
        $row2 = $_db->query("SELECT `name`, `id` FROM `".PREFIX."_used_widgets` WHERE `id` = $uwid")->fetch_object();
        $row3 = $_db->query("SELECT `name` FROM `".PREFIX."_widgets` WHERE `folder_name` = '$row2->name'")->fetch_object();
        $pos = -5-3.5*$y;
        echo '<label posn="0 '.$pos.' 0" text="$fff'.$row3->name.'" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;globalwv&amp;widget='.$row2->id.'"/>';
        $y++;
    } 
    $pos = -6.5-3.5*$y;
    
    echo '<label posn="0 '.$pos.' 0" textid="add" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;globalwv&amp;addwidget"/>
    <quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=general"/>
    <label posn="10 -52 0" valign="center" halign="center" text="Back" textcolor="000"/>';
    return;
}*/
elseif(isset($_GET['rightsv']) && $rank <= $perms['rights'])
{
    if(isset($_GET['users']))
    {
        $rights = array(
            'users' => abs(intval($_GET['users'])),
            'general' => abs(intval($_GET['general'])),
            'rights' => abs(intval($_GET['rights'])),
            'mysql' => abs(intval($_GET['mysql'])),
            'plugins' => abs(intval($_GET['plugins'])),
            'globalw' => abs(intval($_GET['globalw'])),
            'textpages' => abs(intval($_GET['textpages'])),
            'navi' => abs(intval($_GET['navi']))
        );
        setGeneralConfig('permissions', serialize($rights));
    }
    $rights = unserialize(getGeneralConfig('permissions'));
    echo '<label posn="0 0 0" style="TextRankingsBig" textid="rightsv"/>
    <label posn="0 -5 0" textid="users"/>
    <entry posn="35 -5 0" sizen="5 2.5" default="'.$rights['users'].'" name="users"/>
    <label posn="0 -8.5 0" textid="gens"/>
    <entry posn="35 -8.5 0" sizen="5 2.5" default="'.$rights['general'].'" name="general_"/>
    <label posn="0 -12 0" textid="rightsv"/>
    <entry posn="35 -12 0" sizen="5 2.5" default="'.$rights['rights'].'" name="rights"/>
    <label posn="0 -15.5 0" textid="mysql"/>
    <entry posn="35 -15.5 0" sizen="5 2.5" default="'.$rights['mysql'].'" name="mysql"/>
    <label posn="0 -19 0" textid="plugins"/>
    <entry posn="35 -19 0" sizen="5 2.5" default="'.$rights['plugins'].'" name="plugins"/>
    <label posn="0 -22.5 0" textid="widgets"/>
    <entry posn="35 -22.5 0" sizen="5 2.5" default="'.$rights['globalw'].'" name="widgets"/>
    <label posn="0 -26 0" textid="textpages"/>
    <entry posn="35 -26 0" sizen="5 2.5" default="'.$rights['textpages'].'" name="textpages"/>
    <label posn="0 -29.5 0" textid="navi"/>
    <entry posn="35 -29.5 0" sizen="5 2.5" default="'.$rights['navi'].'" name="navi"/>
    <label posn="0 -34 0" style="TextCardScores2" textid="save" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;rightsv&amp;users=users&amp;general=general_&amp;rights=rights&amp;mysql=mysql&amp;plugins=plugins&amp;globalw=globalw&amp;textpages=textpages&amp;navi=navi"/>
    <label posn="0 -37.5 0" style="TextCardScores2" textid="tcs2back" manialink="'.KYUSS.'?p=admin&amp;action=general"/>
    <label posn="0 -44.5 0" sizen="100" textid="rightsinfo" autonewline="1"/>';
    return;
}
elseif(isset($_GET['mysql']) && $rank <= $perms['mysql'])
{
    if(isset($_GET['host']))
    {
        $str = "<?php
define('HOST', '".addslashes($_GET['host'])."');
define('USERNAME', '".addslashes($_GET['username'])."');
define('PASSWORD', '".addslashes($_GET['password'])."');
define('DB_NAME', '".addslashes($_GET['dbname'])."');
define('PREFIX', '".addslashes($_GET['prefix'])."');";
        file_put_contents('connect.php', $str);
        echo '<label posn="0 0 0" style="TextRankingsBig" textid="chscf"/>
        <label posn="0 -5 0" style="TextCardScores2" textid="tcs2back" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;mysql"/>';
        return;
    }
    echo '<label posn="0 0 0" style="TextRankingsBig" textid="mysql"/>
    <label posn="0 -5 0" textid="host"/>
    <entry posn="25 -5 0" sizen="20 2.5" default="'.HOST.'" name="host"/>
    <label posn="0 -8.5 0" textid="username"/>
    <entry posn="25 -8.5 0" sizen="20 2.5" default="'.USERNAME.'" name="username"/>
    <label posn="0 -12 0" textid="password"/>
    <entry posn="25 -12 0" sizen="20 2.5" default="'.PASSWORD.'" name="password"/>
    <quad posn="25 -12 1" sizen="20 2.5" bgcolor="444"/>
    <label posn="0 -15.5 0" textid="dbname"/>
    <entry posn="25 -15.5 0" sizen="20 2.5" default="'.DB_NAME.'" name="dbname"/>
    <label posn="0 -19 0" textid="prefix"/>
    <entry posn="25 -19 0" sizen="20 2.5" default="'.PREFIX.'" name="prefix"/>
    <label posn="0 -22.5 0" style="TextCardScores2" textid="save" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;mysql&amp;host=host&amp;username=username&amp;password=password&amp;dbname=dbname&amp;prefix=prefix"/>
    <label posn="0 -26 0" style="TextCardScores2" textid="tcs2back" manialink="'.KYUSS.'?p=admin&amp;action=general"/>
    <label posn="0 -30.5 0" sizen="100" textid="mysqlinfo" autonewline="1"/>';
    return;    
}

echo '<label posn="0 0 0" style="TextRankingsBig" textid="gens"/>
<label posn="0 -5 0" textid="title"/>
<entry posn="17 -5 0" sizen="40 2.5" default="'.getGeneralConfig('page_title').'" name="title"/>
<label posn="0 -8.5 0" textid="thisurl"/>
<entry posn="17 -11.5 0" sizen="40 2.5" default="'.getGeneralConfig('kyuss_self').'" name="thisurl"/>
<label posn="0 -15.5 0" style="TextCardScores2" textid="save" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;title=title&amp;thisurl=thisurl"/>
<label posn="0 -19 0" textid="customxml" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;customxml"/>
<label posn="0 -22.5 0" textid="rightsv" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;rightsv"/>';
if($rank <= $perms['mysql'])
    echo '<label posn="0 -26 0" textid="mysql" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;mysql"/>';

echo '<label posn="0 -30.5 0" textid="tcs2back" style="TextCardScores2" manialink="'.KYUSS.'?p=admin"/>
<label posn="60 -5 0" textid="chbanner" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;chbanner"/>
<label posn="60 -8.5 0" textid="delbanner" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;delbanner"/>
<label posn="60 -16.5 0" sizen="100" textid="bannerinfo"/>';
if(getGeneralConfig('show_header') == 0)
    echo '<label posn="60 -12 0" textid="showbanner" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;showbanner=1"/>';
else
    echo '<label posn="60 -12 0" textid="hidebanner" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=general&amp;showbanner=0"/>';