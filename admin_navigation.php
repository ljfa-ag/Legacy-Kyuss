<?php
/**
 * Ändern der Navigation
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.gnu.org/licenses/gpl.html> GNU Gerneral Public License
 */
if(!$sec)
    exit;
$_dico['en'] += Array(
            'naviv' => 'Edit navigation',
            'langabr' => 'Lang.',
            'imageid' => 'Image-ID:',
            'target' => 'Target:',
            'textpage' => 'Textpage',
            'page' => 'Page',
            'change' => '$fffChange',
            'chpage' => 'Choose page',
            'chtpage' => 'Choose textpage',
            'addsub' => 'Add submenu',
            'addbut' => 'Add button'
        );
        $_dico['de'] += Array(
            'naviv' => 'Navigation bearbeiten',
            'langabr' => 'Spr.',
            'imageid' => 'Bild-ID:',
            'target' => 'Ziel:',
            'textpage' => 'Textseite',
            'page' => 'Seite',
            'change' => '$fffÄndern',
            'chpage' => 'Seite auswählen',
            'chtpage' => 'Textseite auswählen',
            'addsub' => 'Neues Untermenü',
            'addbut' => 'Neuer Menüpunkt'
        );
        if(isset($_GET['delete']))
        {
            $row = $_db->query("SELECT `type`, `target` FROM `".PREFIX."_navigation` WHERE `id` = ".intval($_GET['delete']." LIMIT 1"))->fetch_object();
            if($row->type == 'submenu')
            {
                $query = $_db->query("SELECT `id` FROM `".PREFIX."_navigation` WHERE `submenu` = $row->target");
                while($row = $query->fetch_object())
                {
                    $_db->query("DELETE FROM `".PREFIX."_navigation` WHERE `id` = $row->id");
                    $_db->query("DELETE FROM `".PREFIX."_dico` WHERE `name` = 'nav-$row->id'");
                }
            }
            $_db->query("DELETE FROM `".PREFIX."_navigation` WHERE `id` = ".intval($_GET['delete']));
            $_db->query("DELETE FROM `".PREFIX."_dico` WHERE `name` = 'nav-".intval($_GET['delete'])."'");
        }
        elseif(isset($_GET['down']))
        {
            $query = $_db->query("SELECT `id`, `order` FROM `".PREFIX."_navigation` ORDER BY `submenu`, `order`");
            while($row = $query->fetch_object())
            {
                if($row->id == $_GET['down'])
                {
                    $next = $query->fetch_object();
                    break;
                }
            }
            $current = $_db->query("SELECT `id`, `order` FROM `".PREFIX."_navigation` WHERE `id` = ".intval($_GET['down']))->fetch_object();
            
            $_db->query("UPDATE `".PREFIX."_navigation` SET `order` = $next->order WHERE `id` = $current->id");
            $_db->query("UPDATE `".PREFIX."_navigation` SET `order` = $current->order WHERE `id` = $next->id");
        }
        elseif(isset($_GET['add']))
        {
            $max = $_db->query("SELECT MAX(`order`) AS `maxorder` FROM `".PREFIX."_navigation`")->fetch_object()->maxorder;
            $_db->query("INSERT INTO `".PREFIX."_navigation` SET `order` = ".($max+1));
            $added = $_db->insert_id;
        }
        elseif(isset($_GET['addToSub']))
        {
            $max = $_db->query("SELECT MAX(`order`) AS `maxorder` FROM `".PREFIX."_navigation` WHERE `submenu` = ".intval($_GET['addToSub']))->fetch_object()->maxorder;
            $_db->query("INSERT INTO `".PREFIX."_navigation` SET `order` = ".($max+1).", `submenu` = ".intval($_GET['addToSub']));
            $added = $_db->insert_id;
        }
        elseif(isset($_GET['addSub']))
        {
            $max = $_db->query("SELECT MAX(`order`) AS `maxorder`, MAX(`submenu`) AS `maxsub` FROM `".PREFIX."_navigation`")->fetch_object();
            $_db->query("INSERT INTO `".PREFIX."_navigation` SET `order` = ".($max->maxorder+1).", `type` = 'submenu', `target` = ".($max->maxsub+1));
            $added = $_db->insert_id;
        }
        if(isset($_GET['but']) || isset($added))
        {
            $but = isset($added) ? $added : $_GET['but'];
            if(isset($_GET['code']))
            {
                $query = $_db->prepare("UPDATE `".PREFIX."_dico` SET `langcode` = ?, `text` = ? WHERE `name` = ? AND `langcode` = ?");
                $name = "nav-$but";
                $query->bind_param('ssss', $_GET['nc'], $_GET['text'], $name, $_GET['code']);
                $query->execute();
                $query->close();
            }
            elseif(isset($_GET['delcode']))
            {
                $query = $_db->prepare("DELETE FROM `".PREFIX."_dico` WHERE `name` = ? AND `langcode` = ?");
                $name = "nav-$but";
                $query->bind_param('ss', $name, $_GET['delcode']);
                $query->execute();
                $query->close();
                
            }
            elseif(isset($_GET['newcode']))
            {
                $query = $_db->prepare("INSERT INTO `".PREFIX."_dico` SET `langcode` = ?, `text` = ?, `name` = ?");
                $name = "nav-$but";
                $query->bind_param('sss', $_GET['newcode'], $_GET['newtext'], $name);
                $query->execute();
                $query->close();
            }
            elseif(isset($_GET['newimgid']))
            {
                $query = $_db->prepare("UPDATE `".PREFIX."_navigation` SET `imageid` = ? WHERE `id` = ?");
                $query->bind_param('ss', $_GET['newimgid'], $but);
                $query->execute();
                $query->close();
            }
            elseif(isset($_GET['setTarget']))
            {
                $query = $_db->prepare("UPDATE `".PREFIX."_navigation` SET `target` = ? WHERE `id` = ?");
                $query->bind_param('ss', $_GET['setTarget'], $but);
                $query->execute();
                $query->close();
            }
            elseif(isset($_GET['setType']))
            {
                $query = $_db->prepare("UPDATE `".PREFIX."_navigation` SET `type` = ? WHERE `id` = ?");
                $query->bind_param('ss', $_GET['setType'], $but);
                $query->execute();
                $query->close();
                $_db->query("UPDATE `".PREFIX."_navigation` SET `target` = NULL WHERE `id` = $but");
            }
            elseif(isset($_GET['params']))
            {
                $query = $_db->prepare("UPDATE `".PREFIX."_navigation` SET `params` = ? WHERE `id` = ?");
                $query->bind_param('ss', $_GET['params'], $but);
                $query->execute();
                $query->close();
            }
            elseif(isset($_GET['selectPage']))
            {
                if($_GET['selectPage'] == '')
                {
                    $y = 0;
                    echo '<label posn="0 0 0" style="TextRankingsBig" textid="chpage"/>';
                    $query = $_db->query("SELECT * FROM `".PREFIX."_pages`");
                    while($row = $query->fetch_object())
                    {
                        echo '<label posn="0 '.(-5-3.5*$y).' 0" text="$fff'.$row->name.'" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$but.'&amp;selectPage='.$row->folder_name.'"/>';
                        $y++;
                    } 
                    return;
                }
                else
                {
                    $query = $_db->prepare("UPDATE `".PREFIX."_navigation` SET `target` = ? WHERE `id` = ?");
                    $query->bind_param('ss', $_GET['selectPage'], $but);
                    $query->execute();
                    $query->close();
                    $_db->query("UPDATE `".PREFIX."_navigation` SET `type` = 'page' WHERE `id` = $but");
                }
            }
            elseif(isset($_GET['selectTextpage']))
            {
                if($_GET['selectTextpage'] == '')
                {
                    $y = 0;
                    echo '<label posn="0 0 0" style="TextRankingsBig" textid="chtpage"/>';
                    $query = $_db->query("SELECT * FROM `".PREFIX."_textpages`");
                    while($row = $query->fetch_object())
                    {
                        echo '<label posn="0 '.(-5-3.5*$y).' 0" text="$fff'.$row->name.'" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$but.'&amp;selectTextpage='.$row->id.'"/>';
                        $y++;
                    } 
                    return;
                }
                else
                {
                    $query = $_db->prepare("UPDATE `".PREFIX."_navigation` SET `target` = ? WHERE `id` = ?");
                    $query->bind_param('ss', $_GET['selectTextpage'], $but);
                    $query->execute();
                    $query->close();
                    $_db->query("UPDATE `".PREFIX."_navigation` SET `type` = 'textpage' WHERE `id` = $but");
                }
            }
            
            echo '<label posn="0 0 0" style="TextRankingsBig" textid="naviv"/>
            <label posn="3.7 -5 0" halign="right" textid="langabr"/>
            <label posn="5 -5 0" text="Text"/>';
            $query = $_db->query("SELECT * FROM `".PREFIX."_dico` WHERE `name` = 'nav-$but'");
            $y = 0;
            while($row = $query->fetch_object())
            {
                $pos = -8.5-3.5*$y;
                echo '<entry posn="0 '.$pos.' 0" sizen="4 2.5" default="'.$row->langcode.'" name="code-'.$y.'"/>
                <entry posn="5 '.$pos.' 0" sizen="15 2.5" default="'.utf8_decode($row->text).'" name="text-'.$y.'"/>
                <label posn="21 '.$pos.' 0" text="$fffOK" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$but.'&amp;code='.$row->langcode.'&amp;nc=code-'.$y.'&amp;text=text-'.$y.'"/>
                <label posn="26 '.$pos.' 0" textid="delete" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$but.'&amp;delcode='.$row->langcode.'"/>';
                $y++;
            }
            $pos = -10-3.5*$y;
            echo '<entry posn="0 '.$pos.' 0" sizen="4 2.5" name="newcode"/>
            <entry posn="5 '.$pos.' 0" sizen="15 2.5" name="newtext"/>
            <label posn="21 '.$pos.' 0" textid="add" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$but.'&amp;newcode=newcode&amp;newtext=newtext"/>
            <label posn="45 -5 0" textid="imageid"/>';
            $row = $_db->query("SELECT * FROM `".PREFIX."_navigation` WHERE `id` = '$but'")->fetch_object();
            echo '<entry posn="53 -5 0" sizen="12 2.5" name="imageid" default="'.$row->imageid.'"/>
            <label posn="66 -5 0" textid="change" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$but.'&amp;newimgid=imageid"/>';
            
            if(is_null($row->submenu))
                echo '<quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=navigation"/>
                <label posn="10 -52 0" valign="center" halign="center" text="Back" textcolor="000"/>';
            else
            {
                $id = $_db->query("SELECT `id` FROM `".PREFIX."_navigation` WHERE `type` = 'submenu' AND `target` = $row->submenu ORDER BY `order`")->fetch_object()->id;
                echo '<quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$id.'"/>
                <label posn="10 -52 0" valign="center" halign="center" text="Back" textcolor="000"/>';
            }
            
            if($row->type == 'submenu')
            {
                $query = $_db->query("SELECT * FROM `".PREFIX."_navigation` WHERE `submenu` = ".$row->target." ORDER BY `order`");
                $y = 0;
                while($row2 = $query->fetch_object())
                {
                    $pos = -12-3.5*$y;
                    echo '<label posn="54.7 '.$pos.' 0" valign="center" textid="nav-'.$row2->id.'" style="TextCardRaceRank" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$row2->id.'"/>
                    <quad posn="51.5 '.($pos-0.3).' 0" sizen="3 3" valign="center" style="Icons64x64_1" substyle="Close" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;delete='.$row2->id.'&amp;but='.$row->id.'"/>';
                    if($y != 0)
                        echo '<quad posn="50.2 '.($pos-0.3).' 0" sizen="1.5 1.5" valign="bottom" style="Icons64x64_1" substyle="ArrowUp" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;down='.$oldid.'&amp;but='.$row->id.'"/>';
                    if($y != $query->num_rows-1)
                        echo '<quad posn="50.2 '.($pos-0.3).' 0" sizen="1.5 1.5" style="Icons64x64_1" substyle="ArrowDown" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;down='.$row2->id.'&amp;but='.$row->id.'"/>';
                    
                    $y++;
                    $oldid = $row2->id;
                }
                echo '<quad posn="60 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;addToSub='.$row->target.'"/>
                <label posn="60 -52 0" valign="center" halign="center" text="Add" textcolor="000"/>';
                return;
            }
            
            echo '<label posn="45 -12 0" textid="target" valign="center"/>
            <label posn="55.5 -12 0" textid="textpage" valign="center"/>
            <label posn="55.5 -15 0" textid="page" valign="center"/>
            <label posn="55.5 -18 0" text="URL" valign="center"/>
            <label posn="55.5 -21 0" text="Manialink" valign="center"/>';
            
            if($row->type == 'textpage')
            {
                $row2 = $_db->query("SELECT `name` FROM `".PREFIX."_textpages` WHERE `id` = $row->target")->fetch_object();
                echo '<quad posn="51.5 -12 0" sizen="4 4" image="./data/images/button.png" valign="center"/>
                <label posn="67 -12 0" text="$fff'.$row2->name.'" valign="center" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$but.'&amp;selectTextpage"/>';
            }
            else echo '<quad posn="51.5 -12 0" sizen="4 4" style="Icons64x64_1" substyle="Check" valign="center" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$but.'&amp;selectTextpage"/>';
            if($row->type == 'page')
            {
                $row2 = $_db->query("SELECT `name` FROM `".PREFIX."_pages` WHERE `folder_name` = '$row->target'")->fetch_object();
                echo '<quad posn="51.5 -15 0" sizen="4 4" image="./data/images/button.png" valign="center"/>
                <label posn="67 -15 0" text="$fff'.$row2->name.'" valign="center" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$but.'&amp;selectPage"/>
                <label posn="67 -18.5 0" text="GET-Parameter:" valign="center"/>
                <entry posn="67 -21.5 0" sizen="30 2.5" valign="center" default="'.$row->params.'" name="params"/>
                <label posn="97.5 -21.5 0" text="$fffOK" valign="center" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$but.'&amp;params=params"/>';
            }
            else echo '<quad posn="51.5 -15 0" sizen="4 4" style="Icons64x64_1" substyle="Check" valign="center" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$but.'&amp;selectPage"/>';
            if($row->type == 'url')
                echo '<quad posn="51.5 -18 0" sizen="4 4" image="./data/images/button.png" valign="center"/>
                <entry posn="67 -18 0" sizen="30 3" default="'.$row->target.'" valign="center" name="target"/>
                <label posn="67 -21.5 0" sizen="30 3" textid="change" valign="center" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$but.'&amp;setTarget=target"/>';
            else echo '<quad posn="51.5 -18 0" sizen="4 4" style="Icons64x64_1" substyle="Check" valign="center" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$but.'&amp;setType=url"/>';
            if($row->type == 'manialink')
                echo '<quad posn="51.5 -21 0" sizen="4 4" image="./data/images/button.png" valign="center"/>
                <entry posn="67 -21 0" sizen="30 3" default="'.$row->target.'" valign="center" name="target"/>
                <label posn="67 -24.5 0" sizen="30 3" textid="change" valign="center" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$but.'&amp;setTarget=target"/>';
            else echo '<quad posn="51.5 -21 0" sizen="4 4" style="Icons64x64_1" substyle="Check" valign="center" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$but.'&amp;setType=manialink"/>';
            
            return;
        }
        
        $y = 0;
        echo '<label posn="0 0 0" style="TextRankingsBig" textid="naviv"/>';
        $query = $_db->query("SELECT * FROM `".PREFIX."_navigation` WHERE ISNULL(`submenu`) ORDER BY `order`");
        while($row = $query->fetch_object())
        {
            $pos = -6-3.5*$y;
            echo '<label posn="3.2 '.$pos.' 0" valign="center" textid="nav-'.$row->id.'" style="TextCardRaceRank" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;but='.$row->id.'"/>
            <quad posn="0 '.($pos-0.3).' 0" sizen="3 3" valign="center" style="Icons64x64_1" substyle="Close" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;delete='.$row->id.'"/>';
            if($y != 0)
                echo '<quad posn="-1.3 '.($pos-0.3).' 0" sizen="1.5 1.5" valign="bottom" style="Icons64x64_1" substyle="ArrowUp" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;down='.$oldid.'"/>';
            if($y != $query->num_rows-1)
                echo '<quad posn="-1.3 '.($pos-0.3).' 0" sizen="1.5 1.5" style="Icons64x64_1" substyle="ArrowDown" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;down='.$row->id.'"/>';
            
            $y++;
            $oldid = $row->id;
        }
        echo '<quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;add"/>
<label posn="10 -52 0" valign="center" halign="center" textid="addbut" textcolor="000"/>
<quad posn="10 -47 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=navigation&amp;addSub"/>
<label posn="10 -47 0" valign="center" halign="center" textid="addsub" textcolor="000"/>
<quad posn="10 -57 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin"/>
<label posn="10 -57 0" valign="center" halign="center" text="Back" textcolor="000"/>';
