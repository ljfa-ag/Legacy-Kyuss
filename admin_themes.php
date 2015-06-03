<?php
/**
 * Verwalten der Themes
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.gnu.org/licenses/gpl.html> GNU Gerneral Public License
 */
if(!$sec)
    exit;
$_dico['en'] += Array(
            'themesv' => 'Manage themes',
            'standard' => '$fffSet as standard',
            'themeinst' => 'Install theme',
            'file' => 'Theme folder:',
            'themephpnex' => "The theme is corrupted: 'theme.php' not found\n\$h[".KYUSS."?p=admin&action=themes&install]Back",
            'foldernex' => "The folder does not exist!\n\$h[".KYUSS."?p=admin&action=themes&install]Back"
        );
        $_dico['de'] += Array(
            'themesv' => 'Themes verwalten',
            'standard' => '$fffAls Standard',
            'themeinst' => 'Theme installieren',
            'file' => 'Theme-Ordner:',
            'themephpnex' => "Das Theme ist beschädigt: 'theme.php' nicht gefunden\n\$h[".KYUSS."?p=admin&action=themes&install]Zurück",
            'foldernex' => "Der Ordner existiert nicht!\n\$h[".KYUSS."?p=admin&action=themes&install]Zurück"
        );
        if(isset($_GET['askuinst']))
        {
            $row = $_db->query("SELECT * FROM `".PREFIX."_themes` WHERE `folder_name` = '".$_db->real_escape_string($_GET['askuinst'])."'")->fetch_object();
            $_dico['en']['themeuinst'] = "Do you really want to uninstall the theme '$row->name' and delete all its files?\n\$h[".KYUSS."?p=admin&action=themes&uninstall=$_GET[askuinst]]Yes\$h   \$h[".KYUSS."?p=admin&action=themes]No";
            $_dico['de']['themeuinst'] = "Möchten Sie das Theme '$row->name' wirklich deinstallieren und all dessen Dateien löschen?\n\$h[".KYUSS."?p=admin&action=themes&uninstall=$_GET[askuinst]]Ja\$h   \$h[".KYUSS."?p=admin&action=themes]Nein";
            echo '<label posn="0 0 0" textid="themeuinst"/>';
            return;
        }
        elseif(isset($_GET['uninstall']))
        {
            require('delete_folder.php');
            delete_folder('themes/'.$_GET['uninstall']);
            $_db->query("DELETE FROM `".PREFIX."_themes` WHERE `folder_name` = '".$_db->real_escape_string($_GET['uninstall'])."'");
            if(getGeneralConfig('standard_theme') == $_GET['uninstall'])
            {
                $row = $_db->query("SELECT * FROM `".PREFIX."_themes` LIMIT 1")->fetch_object();
                setGeneralConfig('standard_theme', $row->folder_name);
            }
        }
        if(isset($_GET['standard']))
            setGeneralConfig('standard_theme', $_GET['standard']);
            
        if(isset($_GET['install']))
        {
            if($_GET['install'] == '')
            {
                echo '<label posn="0 0 0" style="TextRankingsBig" textid="themeinst"/>
                <label posn="0 -5 0" textid="file"/>
                <entry posn="17 -5 0" sizen="20 2.5" name="folder"/>
                <quad posn="10 -12 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=themes&amp;install=folder"/>
                <label posn="10 -12 0" valign="center" halign="center" text="OK" textcolor="000"/>
                <label posn="0 -16 0" sizen="100" textid="thinstinf" autonewline="1"/>';
                return;
            }
            else
            {
                if(!file_exists("themes/$_GET[install]"))
                {
                    echo '<label posn="0 0 0" style="TextRankingsBig" textid="foldernex"/>';
                    return;
                }
                elseif(!file_exists("themes/$_GET[install]/theme.php"))
                {
                    echo '<label posn="0 0 0" style="TextRankingsBig" textid="themephpnex"/>';
                    return;
                }
                if(stristr($_GET['install'], '..') !== false)
                    return;
                include("themes/$_GET[install]/theme.php");
                $foldername = $_GET['install'];
                $theme = $$foldername;
                $foldername = $_db->real_escape_string($foldername);
                $_db->query("INSERT INTO `".PREFIX."_themes` (`folder_name`, `name`) VALUES ('$foldername', '$theme[name]')");
            }
        }
        
        $standard = getGeneralConfig('standard_theme');
        $y = 0;
        echo '<label posn="0 0 0" style="TextRankingsBig" textid="themesv"/>';
        $query = $_db->query("SELECT * FROM `".PREFIX."_themes`");
        while($row = $query->fetch_object())
        {
            echo '<label posn="0 '.(-5-3.5*$y).' 0" text="$fff'.$row->name.(($row->folder_name == $standard) ? ' (Standard)' : '').'" style="TextCardScores2"/>
            <label posn="35 '.(-5-3.5*$y).' 0" textid="uninstall" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=themes&amp;askuinst='.$row->folder_name.'"/>
            <label posn="55 '.(-5-3.5*$y).' 0" textid="standard" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=themes&amp;standard='.$row->folder_name.'"/>';
            $y++;
        }
        echo '<quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=themes&amp;install"/>
        <label posn="10 -52 0" valign="center" halign="center" textid="themeinst" textcolor="000"/>
        <quad posn="10 -57 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=plugins"/>
            <label posn="10 -57 0" valign="center" halign="center" text="Back" textcolor="000"/>';
