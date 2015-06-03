<?php
/**
 * Verwalten der Seiten
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 */
if(!$sec)
    exit;   

$_dico['en'] += Array(
            'pageinst' => 'Install page',
            'pagesv' => 'Manage pages',
            //'configb' => '$fffConfigure',
            'pagefile' => 'Page folder:',
            'pagephpnex' => "The page is corrupted: 'page.php' not found\n\$h[".KYUSS."?p=admin&action=pages&install]Back",
            'foldernex' => "The folder does not exist!\n\$h[".KYUSS."?p=admin&action=pages&install]Back",
            'rights' => 'Max. rights number to configure:',
            'endc' => '$000End config'
        );
        $_dico['de'] += Array(
            'pageinst' => 'Seite installieren',
            'pagesv' => 'Seiten verwalten',
            //'configb' => '$fffKonfigurieren',
            'pagefile' => 'Seiten-Ordner:',
            'pagephpnex' => "Die Seite ist beschädigt: 'page.php' nicht gefunden\n\$h[".KYUSS."?p=admin&action=pages&install]Zurück",
    'foldernex' => "Der Ordner existiert nicht!\n\$h[".KYUSS."?p=admin&action=pages&install]Zurück",
            'rights' => 'Max. Rechtezahl zum Konfigurieren:',
            'endc' => '$000Konfig. beenden'
        );
        if(isset($_GET['page']))
        {
            if(isset($_GET['rights']))
                $_db->query("UPDATE `".PREFIX."_pages` SET `rights` = ".intval($_GET['rights'])." WHERE `id` = ".intval($_GET['page']));
            $row = $_db->query("SELECT * FROM `".PREFIX."_pages` WHERE `id` = ".intval($_GET['page']))->fetch_object();
            echo '<label posn="0 0 0" style="TextRankingsBig" text="'.$row->name.'"/>
            <label posn="0 -5 0" sizen="100" text="'.$row->desc.'" autonewline="1"/>
            <label posn="0 -23 0" style="TextCardScores2" textid="tcs2back" manialink="'.KYUSS.'?p=admin&amp;action=pages"/>';
            if($row->folder_name != 'admin' && $row->folder_name != 'theme')
                echo '<label posn="0 -16 0" textid="rights"/>
                <entry posn="34.5 -16 0" sizen="5 2.5" default="'.$row->rights.'" name="rights"/>
                <label posn="40.5 -16 0" text="$fffOK" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=pages&amp;page='.$row->id.'&amp;rights=rights"/>
                <label posn="0 -19.5 0" style="TextCardScores2" textid="uninst" manialink="'.KYUSS.'?p=admin&amp;action=pages&amp;askuinst='.$row->folder_name.'"/>';
            //if($row->hasConfig == '1' && $rank <= $row->rights)
                //echo '<label posn="0 -23 0" style="TextCardScores2" textid="configb" manialink="'.KYUSS.'?p=admin&amp;action=pages&amp;config='.$row->folder_name.'"/>';
            
            if(isset($_GET['saveconfig']) && $rank <= $row->rights)
            {
                $foldername = $row->folder_name;
                include("pages/$foldername/page.php");
                $cfg = new $foldername($foldername, true);
                try
                {
                    $cfg->save();
            	}
            	catch(exception $ex)
            	{
            		$_dico['de']['ex'] = "Das Speichern der Konfiguration von '$foldername' hat einen unbehandelten Ausnahmefehler verursacht:\n\$o".$ex->getMessage()."\$z\nStack-Trace:\n".$ex->getTraceAsString();
            		$_dico['en']['ex'] = "The saving of the configuration of '$foldername' has thrown an uncaught exception:\n\$o".$ex->getMessage()."\$z\nStack trace:\n".$ex->getTraceAsString();
            		echo '<label posn="0 -32" textid="ex"/>';
            	}
            }
            
            return;
        }
        elseif(isset($_GET['askuinst']))
        {
            $row = $_db->query("SELECT * FROM `".PREFIX."_pages` WHERE `folder_name` = '".$_db->real_escape_string($_GET['askuinst'])."'")->fetch_object();
            $_dico['en']['pageuinst'] = "Do you really want to uninstall the page '$row->name' and delete all its files?\n\$h[".KYUSS."?p=admin&action=pages&uninstall=$_GET[askuinst]]Yes\$h   \$h[".KYUSS."?p=admin&action=pages]No";
            $_dico['de']['pageuinst'] = "Möchten Sie die Seite '$row->name' wirklich deinstallieren und all deren Dateien löschen?\n\$h[".KYUSS."?p=admin&action=pages&uninstall=$_GET[askuinst]]Ja\$h   \$h[".KYUSS."?p=admin&action=pages]Nein";
            echo '<label posn="0 0 0" textid="pageuinst"/>';
            return;
        }
        elseif(isset($_GET['uninstall']))
        {
            if(stristr($_GET['uninstall'], '..') !== false)
                return;
			include_once("pages/$_GET[uninstall]/page.php");
			$lists = unserialize(getGeneralConfig('shared_lists'));
			foreach(getStaticProperty($_GET['uninstall'], 'shared_lists') as $list)
			{
				if($lists[$list] == 1)
				{
					unset($lists[$list]);
					$_db->query("DELETE FROM `".PREFIX."_shared_lists` WHERE `list` = '$list'");
				}
				else
					$lists[$list]--;
			}
			setGeneralConfig('shared_lists', serialize($lists));
			
            require_once('delete_folder.php');
            delete_folder('pages/'.$_GET['uninstall']);
            $_db->query("DELETE FROM `".PREFIX."_pages` WHERE `folder_name` = '".$_db->real_escape_string($_GET['uninstall'])."'");
			$_db->query("DELETE FROM `".PREFIX."_plugin_config` WHERE `page` = '".$_db->real_escape_string($_GET['uninstall'])."'");
        }
        
        if(isset($_GET['install']))
        {
            if($_GET['install'] == '')
            {
                echo '<label posn="0 0 0" style="TextRankingsBig" textid="pageinst"/>
                <label posn="0 -5 0" textid="pagefile"/>
                <entry posn="17 -5 0" sizen="20 2.5" name="folder"/>
                <quad posn="10 -12 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=pages&amp;install=folder"/>
                <label posn="10 -12 0" valign="center" halign="center" text="OK" textcolor="000"/>
                <label posn="0 -16 0" sizen="100" textid="thinstinf" autonewline="1"/>';
                return;
            }
            else
            {
                if(!file_exists("pages/$_GET[install]"))
                {
                    echo '<label posn="0 0 0" style="TextRankingsBig" textid="foldernex"/>';
                    return;
                }
                elseif(!file_exists("pages/$_GET[install]/page.php"))
                {
                    echo '<label posn="0 0 0" style="TextRankingsBig" textid="pagephpnex"/>';
                    return;
                }
                $foldername = $_GET['install'];
                include_once("pages/$foldername/page.php");
                $stmt = $_db->prepare("INSERT INTO `".PREFIX."_pages` (`folder_name`, `name`, `desc`, `hasConfig`) VALUES (?, ?, ?, ?)");
				$hasConfig = getStaticProperty($foldername, 'hasConfig') ? 1 : 0;
                $stmt->bind_param('sssi', $foldername, getStaticProperty($foldername, 'name'), getStaticProperty($foldername, 'desc'), $hasConfig);
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
        
        /*if(isset($_GET['config']))
        {
            $row = $_db->query("SELECT `rights` FROM `".PREFIX."_pages` WHERE `name` = '".$_db->real_escape_string($_GET['config'])."'")->fetch_object();
            if($rank > $row->rights)
                return;
            $foldername = $_GET['config'];
            if(stristr($_GET['config'], '..') !== false)
                return;
            include("pages/$foldername/page.php");
            $cfg = new $foldername($foldername, true);
            try
            {
                $save = $cfg->configure();
        	}
        	catch(exception $ex)
        	{
        		$_dico['de']['ex'] = "Die Konfigurationsseite von '$foldername' hat einen unbehandelten Ausnahmefehler verursacht:\n\$o".$ex->getMessage()."\$z\nStack-Trace:\n".$ex->getTraceAsString();
        		$_dico['en']['ex'] = "The configuration page of '$foldername' has thrown an uncaught exception:\n\$o".$ex->getMessage()."\$z\nStack trace:\n".$ex->getTraceAsString();
        		echo '<label textid="ex"/>';
        	}
            $foldername = $_db->real_escape_string($foldername);
            $id = $_db->query("SELECT `id` FROM `".PREFIX."_pages` WHERE `folder_name` = '$foldername' LIMIT 1")->fetch_object()->id;
            echo '
<quad posn="'.$cfg->endpos.'" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=pages&amp;page='.$id.(is_null($save) ? '' : '&amp;saveconfig&amp;'.$save).'"/>
<label posn="'.$cfg->endpos.'" valign="center" halign="center" textid="endc" textcolor="000"/>';
            return;
        }*/
        
        $y = 0;
        echo '<label posn="0 0 0" style="TextRankingsBig" textid="pagesv"/>';
        $query = $_db->query("SELECT * FROM `".PREFIX."_pages`");
        while($row = $query->fetch_object())
        {
            echo '<label posn="0 '.(-5-3.5*$y).' 0" text="$fff'.$row->name.'" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=pages&amp;page='.$row->id.'"/>';
            $y++;
        }
            echo '<quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=pages&amp;install"/>
            <label posn="10 -52 0" valign="center" halign="center" textid="pageinst" textcolor="000"/>
            <quad posn="10 -57 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=plugins"/>
            <label posn="10 -57 0" valign="center" halign="center" text="Back" textcolor="000"/>';
