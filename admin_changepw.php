<?php
/**
 * Ändern des eigenen Passworts
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.gnu.org/licenses/gpl.html> GNU Gerneral Public License
 */
if(!$sec)
    exit;
$_dico['en'] += Array(
            'oldpw' => 'Old password:',
            'newpw' => 'New password:',
            'repeatpw' => 'Repeat password:',
            'wrongoldpw' => "Wrong old password!\n\$h[".KYUSS."?p=admin&action=changepw]Back",
            'pwdontmatch' => "New and repeated password do not match!\n\$h[".KYUSS."?p=admin&action=changepw]Back",
            'changedsuc' => "Password changed sucessfully.\n\$h[".KYUSS."?p=admin]Back"
        );
        $_dico['de'] += Array(
            'oldpw' => 'Altes Passwort:',
            'newpw' => 'Neues Passwort:',
            'repeatpw' => 'Passwort wiederholen:',
            'wrongoldpw' => "Falsches altes Passwort!\n\$h[".KYUSS."?p=admin&action=changepw]Zurück",
            'pwdontmatch' => "Altes und neues Passwort stimmen nicht überein!\n\$h[".KYUSS."?p=admin&action=changepw]Zurück",
            'changedsuc' => "Passwort erfolgreich geändert.\n\$h[".KYUSS."?p=admin]Zurück"
        );
        if(isset($_GET['oldpw']))
        {
            $query = $_db->prepare('SELECT `password` FROM `'.PREFIX.'_users` WHERE `id` = ?');
            $query->bind_param('i', $_SESSION['user_id']);
            $query->execute();
            $query->bind_result($pw);
            $query->fetch();
            $query->close();
            if($pw != crypt($_GET['oldpw'], $pw))
                echo "<label posn='0 0 0' textid='wrongoldpw'/>";
            elseif($_GET['newpw'] != $_GET['repeatpw'])
                echo "<label posn='0 0 0' textid='pwdontmatch'/>";
            else
            {
                $query = $_db->prepare('UPDATE `'.PREFIX.'_users` SET `password` = ? WHERE `id` = ?');
                $query->bind_param('si', crypt($_GET['newpw']), $_SESSION['user_id']);
                $query->execute();
                $query->close();
                echo "<label posn='0 0 0' textid='changedsuc'/>";
            }
        }
        else
            echo '<label posn="0 0 0" textid="oldpw"/>
            <label posn="0 -4 0" textid="newpw"/>
            <label posn="0 -8 0" textid="repeatpw"/>
            <quad posn="22 -0.25 1" sizen="20 2.5" bgcolor="444"/>
            <entry posn="22 -0.25 0" sizen="20 2.5" name="oldpw"/>
            <quad posn="22 -4.25 1" sizen="20 2.5" bgcolor="444"/>
            <entry posn="22 -4.25 0" sizen="20 2.5" name="newpw"/>
            <quad posn="22 -8.25 1" sizen="20 2.5" bgcolor="444"/>
            <entry posn="22 -8.25 0" sizen="20 2.5" name="repeatpw"/>
            <quad posn="10 -14 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=changepw&amp;oldpw=oldpw&amp;newpw=newpw&amp;repeatpw=repeatpw"/>
            <label posn="10 -14 0" valign="center" halign="center" text="OK" textcolor="000"/>
            <quad posn="10 -19 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin"/>
            <label posn="10 -19 0" valign="center" halign="center" text="Back" textcolor="000"/>';
