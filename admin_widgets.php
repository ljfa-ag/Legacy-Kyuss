<?php
/**
 * Verwalten der Widgets
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 */
if(!$sec)
    exit;
$_dico['en'] += Array(
            'widgetsv' => 'Manage widgets',
            'widginst' => 'Install widget',
            'widgfile' => 'Widget folder:',
            'widgetphpnex' => "The widget is corrupted: 'widget.php' not found\n\$h[".KYUSS."?p=admin&action=widgets&install]Back",
            'foldernex' => "The folder does not exist!\n\$h[".KYUSS."?p=admin&action=widgets&install]Back"
        );
        $_dico['de'] += Array(
            'widgetsv' => 'Widgets verwalten',
            'widginst' => 'Widget installieren',
            'widgfile' => 'Widget-Ordner:',
            'widgetphpnex' => "Das Widget ist beschädigt: 'widget.php' nicht gefunden\n\$h[".KYUSS."?p=admin&action=widgets&install]Zurück",
            'foldernex' => "Der Ordner existiert nicht!\n\$h[".KYUSS."?p=admin&action=widgets&install]Zurück"
        );
        if(isset($_GET['widget']))
        {
            $row = $_db->query("SELECT * FROM `".PREFIX."_widgets` WHERE `id` = ".intval($_GET[widget]))->fetch_object();
            echo '<label posn="0 0 0" style="TextRankingsBig" text="'.$row->name.'"/>
            <label posn="0 -5 0" sizen="100" text="'.$row->desc.'" autonewline="1"/>
            <label posn="0 -16 0" style="TextCardScores2" textid="uninst" manialink="'.KYUSS.'?p=admin&amp;action=widgets&amp;askuinst='.$row->folder_name.'"/>
            <label posn="0 -19.5 0" style="TextCardScores2" textid="tcs2back" manialink="'.KYUSS.'?p=admin&amp;action=widgets"/>';
            return;
        }
        elseif(isset($_GET['askuinst']))
        {
            $query = $_db->query("SELECT * FROM `".PREFIX."_used_widgets` WHERE `name` = '".$_db->real_escape_string($_GET['askuinst'])."'");
            if($query->num_rows != 0)
            {
                echo '<label posn="0 0 0" textid="widgiu"/>';
                $_dico['en']['widgiu'] = "This widget cannot be uninstalled because it is still in use!\n\$h[".KYUSS."?p=admin&action=widgets]Back";
                $_dico['de']['widgiu'] = "Dieses Widget kann nicht deinstalliert werden, da es noch in Benutzung ist!\n\$h[".KYUSS."?p=admin&action=widgets]Zurück";
                return;
            }
            
            $row = $_db->query("SELECT * FROM `".PREFIX."_widgets` WHERE `folder_name` = '".$_db->real_escape_string($_GET['askuinst'])."'")->fetch_object();
            $_dico['en']['widgetuinst'] = "Do you really want to uninstall the widget '$row->name' and delete all its files?\n\$h[".KYUSS."?p=admin&action=widgets&uninstall=$_GET[askuinst]]Yes\$h   \$h[".KYUSS."?p=admin&action=widgets]No";
            $_dico['de']['widgetuinst'] = "Möchten Sie das Widget '$row->name' wirklich deinstallieren und all dessen Dateien löschen?\n\$h[".KYUSS."?p=admin&action=widgets&uninstall=$_GET[askuinst]]Ja\$h   \$h[".KYUSS."?p=admin&action=widgets]Nein";
            echo '<label posn="0 0 0" textid="widgetuinst"/>';
            return;
        }
        elseif(isset($_GET['uninstall']))
        {
            require('delete_folder.php');
            delete_folder('widgets/'.$_GET['uninstall']);
            $_db->query("DELETE FROM `".PREFIX."_widgets` WHERE `folder_name` = '".$_db->real_escape_string($_GET['uninstall'])."'");
        }
        
        if(isset($_GET['install']))
        {
            if($_GET['install'] == '')
            {
                echo '<label posn="0 0 0" style="TextRankingsBig" textid="widginst"/>
                <label posn="0 -5 0" textid="widgfile"/>
                <entry posn="17 -5 0" sizen="20 2.5" name="folder"/>
                <quad posn="10 -12 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=widgets&amp;install=folder"/>
                <label posn="10 -12 0" valign="center" halign="center" text="OK" textcolor="000"/>
                <label posn="0 -16 0" sizen="100" textid="thinstinf" autonewline="1"/>';
                return;
            }
            else
            {
                if(!file_exists("widgets/$_GET[install]"))
                {
                    echo '<label posn="0 0 0" style="TextRankingsBig" textid="foldernex"/>';
                    return;
                }
                elseif(!file_exists("widgets/$_GET[install]/widget.php"))
                {
                    echo '<label posn="0 0 0" style="TextRankingsBig" textid="widgetphpnex"/>';
                    return;
                }
                $foldername = $_GET['install'];
                include("widgets/$foldername/widget.php");
                $stmt = $_db->prepare("INSERT INTO `".PREFIX."_widgets` (`folder_name`, `name`, `desc`, `hasConfig`) VALUES (?, ?, ?, ?)");
                $hc = getStaticProperty($foldername, 'hasConfig') ? 1 : 0;
                $stmt->bind_param('sssi', $foldername, getStaticProperty($foldername, 'name'), getStaticProperty($foldername, 'desc'), $hc);
                $stmt->execute();
                $stmt->close();
                $lists = unserialize(getGeneralConfig('shared_lists'));
                foreach(getStaticProperty($foldername, 'shared_lists') as $list)
                {
                    if(array_key_exists($list, $lists))
                        $lists[$list]++;
                    else
                        $lists[$list] = 1;
                }
                setGeneralConfig('shared_lists', serialize($lists));
            }
        }
    
        $y = 0;
        echo '<label posn="0 0 0" style="TextRankingsBig" textid="widgetsv"/>';
        $query = $_db->query("SELECT * FROM `".PREFIX."_widgets`");
        while($row = $query->fetch_object())
        {
            echo '<label posn="0 '.(-5-3.5*$y).' 0" text="$fff'.$row->name.'" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=widgets&amp;widget='.$row->id.'"/>';
            $y++;
        }
        echo '<quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=widgets&amp;install"/>
<label posn="10 -52 0" valign="center" halign="center" textid="widginst" textcolor="000"/>
<quad posn="10 -57 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=plugins"/>
            <label posn="10 -57 0" valign="center" halign="center" text="Back" textcolor="000"/>';
