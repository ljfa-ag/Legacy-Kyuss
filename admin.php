<?php
/**
 * Das Adminpanel
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 */
 
$_dico['en'] += Array(
    'username' => 'Username:',
    'password' => 'Password:',
    'login' => 'Login',
    'wrongpw' => "Wrong username/password\n\$h[".KYUSS."?p=admin]Back",
    'welcome' => 'Welcome to the adminpanel!',
    'loginscf' => "Logged in successfully!\n\$h[".KYUSS."?p=admin]Forward",
    'loggedout' => "Logged out successfully.\n\$h[".KYUSS."]Homepage",
    'uninstall' => '$fffUninstall',
    'thinstinf' => 'To install a theme, widget or page, the plugin folder must be extracted and uploaded into the appropriate folder. Subsequerntly the plugin folder name is entered here.',
    'uninst' => '$fffUninstall',
    'tcs2back' => '$fffBack',
    'delete' => '$fffDelete',
    'add' => '$fffAdd',
    'change' => '$fffChange'
);
$_dico['de'] += Array(
    'username' => 'Benutzername:',
    'password' => 'Passwort:',
    'login' => 'Anmelden',
    'welcome' => 'Willkommen zum Adminpanel!',
    'wrongpw' => "Falscher Benutzername/Passwort\n\$h[".KYUSS."?p=admin]Zurück",
    'loginscf' => "Erfolgreich eingeloggt!\n\$h[".KYUSS."?p=admin]Weiter",
    'loggedout' => "Erfolgreich ausgeloggt.\n\$h[".KYUSS."]Zur Startseite",
    'uninstall' => '$fffDeinstallieren',
    'thinstinf' => 'Um ein Theme, ein Widget oder eine Seite zu installieren, muss der Plugin-Ordner entpackt und in den entsprechenden Ordner hochgeladen werden. Anschließend wird der Name des Plugin-Ordners hier eingetragen.',
    'uninst' => '$fffDeinstallieren',
    'tcs2back' => '$fffZurück',
    'delete' => '$fffEntfernen',
    'add' => '$fffHinzufügen',
    'change' => '$fffÄndern'
);
if(!isset($_SESSION['user_id']) || $_SESSION['manialink_id'] != getGeneralConfig('manialink_id'))
{
    if(isset($_GET['login']))
    {
        $query = $_db->prepare('SELECT * FROM `'.PREFIX.'_users` WHERE `name` = ? LIMIT 1');
        $query->bind_param('s', $_GET['login']);
        $query->execute();
        $query->bind_result($id, $name, $pw, $rank);
        $query->fetch();
        $query->close();
        if($pw == crypt($_GET['pw'], $pw))
        {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_rank'] = $rank;
            $_SESSION['manialink_id'] = getGeneralConfig('manialink_id');
            echo "<label posn='0 0 0' textid='loginscf'/>";
        }
        else
            echo "<label posn='0 0 0' textid='wrongpw'/>";
    }
    else
    {
        echo '<label posn="0 0 0" textid="username"/>
        <label posn="0 -4 0" textid="password"/>
        <entry posn="17 -0.25 0" sizen="20 2.5" name="login"/>
        <quad posn="17 -4.25 1" sizen="20 2.5" bgcolor="444"/>
        <entry posn="17 -4.25 0" sizen="20 2.5" name="pw"/>
        <quad posn="10 -11 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;login=login&amp;pw=pw"/>
        <label posn="10 -11 0" valign="center" halign="center" textid="login" textcolor="000"/>';
    }
}
else
{
    $perms = unserialize(getGeneralConfig('permissions'));
    $rank = $_SESSION['user_rank'];
    switch($_GET['action'])
    {
    case 'changepw':
        $sec = true;
        include("admin_changepw.php");
        break;
    case 'plugins':
        $sec = $rank <= $perms['plugins'];
        include("admin_plugins.php");
        break;
    case 'themes':
        $sec = $rank <= $perms['plugins'];
        include("admin_themes.php");
        break;
    case 'widgets':
        $sec = $rank <= $perms['plugins'];
        include("admin_widgets.php");
        break;
    case 'pages':
        $sec = $rank <= $perms['plugins'];
        include("admin_pages.php");
        break;
    case 'globalw':
        $sec = $rank <= $perms['globalw'];
        include("admin_globalw.php");
        break;
    case 'navigation':
        $sec = $rank <= $perms['navi'];
        include("admin_navigation.php");
        break;
    case 'pgconf':
        $sec = true;
        include("admin_pgconf.php");
        break;
    case 'textpages':
        $sec = $rank <= $perms['textpages'];
        include("admin_textpages.php");
        break;
    case 'users':
        $sec = $rank <= $perms['users'];
        include("admin_users.php");
        break;
    case 'general':
        $sec = $rank <= $perms['general'];
        include("admin_general.php");
        break;
    case 'logout':
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_rank']);
        echo "<label posn='0 0 0' textid='loggedout'/>";
        break;
    default:
        echo '<label posn="0 0 0" textid="welcome"/>
        <label posn="0 -5 0" textid="actions"/>
        <include url="'.UPDATE_URL.'?v='.VERSION.'"/>
        <quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=logout"/>
        <label posn="10 -52 0" valign="center" halign="center" text="Logout" textcolor="000"/>';
        $ena = "\$h[".KYUSS."?p=admin&nb=$_GET[nb]&action=changepw]Change own password\$h";
        $dea = "\$h[".KYUSS."?p=admin&bn=$_GET[nb]&action=changepw]Eigenes Passwort ändern\$h";
        if($rank <= $perms['users'])
        {
            $ena .= "\n\$h[".KYUSS."?p=admin&action=users]Manage users\$h";
            $dea .= "\n\$h[".KYUSS."?p=admin&action=users]Benutzer verwalten\$h";
        }
        if($rank <= $perms['plugins'])
        {
            $ena .= "\n\$h[".KYUSS."?p=admin&action=plugins]Manage plugins\$h";
            $dea .= "\n\$h[".KYUSS."?p=admin&action=plugins]Plugins verwalten\$h";
        }
        if($rank <= $perms['globalw'])
        {
            $ena .= "\n\$h[".KYUSS."?p=admin&action=globalw]Edit global widgets\$h";
            $dea .= "\n\$h[".KYUSS."?p=admin&action=globalw]Globale Widgets bearbeiten\$h";
        }
        $ena .= "\n\$h[".KYUSS."?p=admin&action=pgconf]Configure pages\$h";
        $dea .= "\n\$h[".KYUSS."?p=admin&action=pgconf]Seiten konfigurieren\$h";
        if($rank <= $perms['textpages'])
        {
            $ena .= "\n\$h[".KYUSS."?p=admin&action=textpages]Manage textpages\$h";
            $dea .= "\n\$h[".KYUSS."?p=admin&action=textpages]Textseiten verwalten\$h";
        }
        if($rank <= $perms['navi'])
        {
            $ena .= "\n\$h[".KYUSS."?p=admin&action=navigation]Edit navigation\$h";
            $dea .= "\n\$h[".KYUSS."?p=admin&action=navigation]Navigation bearbeiten\$h";
        }
        if($rank <= $perms['general'])
        {
            $ena .= "\n\$h[".KYUSS."?p=admin&action=general]Miscellaneous settings\$h";
            $dea .= "\n\$h[".KYUSS."?p=admin&action=general]Sonstige Einstellungen\$h";
        }
        
        $_dico['en']['actions'] = $ena;
        $_dico['de']['actions'] = $dea;
    }
}