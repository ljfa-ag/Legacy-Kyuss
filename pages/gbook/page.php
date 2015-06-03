<?php
/**
 * Das Gästebuch
 * @package Kyuss
 * @subpackage gbook
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 * @version 1.0.1
 */
class gbook extends page
{
    public static $name = 'Gästebuch';
	public static $hasConfig = true;
    public static $shared_lists = Array('gbook');
	
    public function show()
    {
        global $_dico;
		$_dico['de'] += Array(
			'gbook' => 'Gästebuch',
            'adde' => 'Eintrag hinzufügen',
            'deny' => 'Sie haben vor kurzem schon einen Eintrag hinzugefügt!'
		);
		$_dico['en'] += Array(
			'gbook' => 'Guestbook',
            'adde' => 'Add entry',
            'deny' => 'You recently already added an entry!'
		);
        
        if(isset($_GET['add']))
        {
            $gbook = self::getSharedListArray('gbook');
            $gbook = array_reverse($gbook, false);
            $gbook = explode('&#0;', $gbook[0], 2);
            if(!isset($_GET['playerlogin']) OR $gbook[0] == $_GET['playerlogin'])
            {
                echo '<label posn="0 0 0" style="TextRankingsBig" textid="deny"/>';
                return;
            }
            echo '<label posn="0 0 0" style="TextRankingsBig" textid="adde"/>
            <frame posn="-1 -4 0">
                <quad posn="0 0 0" sizen="100 26" style="Bgs1" substyle="BgList"/>
                <label posn="1 -1 1" text="Name:"/>
                <label posn="12 -1 1" text="'.htmlspecialchars($_GET['nickname']).'"/>
                <label posn="1 -4.5 1" text="Manialink:"/>
                <entry posn="12 -4.5 1" sizen="20 3" name="manialink"/>
                <label posn="1 -8 1" text="Text:"/>
                <entry posn="12 -8 1" sizen="87 13" name="text" autonewline="1"/>
                <label posn="50 -21.5 1" halign="center" textid="adde" style="CardButtonMedium" manialink="'.$this->encurl().'&amp;text=text&amp;manialink=manialink" addplayerid="1"/>
            </frame>';
            return;
        }
        elseif(isset($_GET['text']))
        {
            $gbooka = self::getSharedListArray('gbook');
            $gbook = array_reverse($gbooka, false);
            $gbook = explode('&#0;', $gbook[0], 2);
            if(!isset($_GET['playerlogin']) OR $gbook[0] == $_GET['playerlogin'])
                return;
            $index = max(array_keys($gbooka))+1;
            $text = ($_GET['manialink'] == '') ? 'schrieb am '.date('d.m:')."\n" : '($h'.$_GET['manialink'].'$h$z) schrieb am '.date('d.m:')."\n";
            self::setSharedListItem('gbook', $index, stripslashes($_GET['playerlogin'].'&#0;'.$_GET['nickname'].'$z '.$text.substr($_GET['text'], 0, 500)));
        }
        
        $gbooka = self::getSharedListArray('gbook');
        $page = intval($_GET['page']);
		if(count($gbooka) == 0)
            $gbook = Array('$iKein Eintrag vorhanden.');
		else
		{
			ksort($gbooka);
		    $gbook = array_reverse($gbooka, false);
    		$gbook = array_slice($gbook, 3*$page, 3);
            
            function exploder($x)
            {
                $ret = explode("&#0;", $x, 2);
                return $ret[1];
            }
    		$gbook = array_map('exploder', $gbook);
		}
        
        echo '<label posn="0 0 0" style="TextRankingsBig" textid="gbook"/>
        <frame posn="-1 -4 0">
            <quad posn="0 0 0" sizen="100 17" style="Bgs1" substyle="BgList"/>
            <label posn="1 -1 1" sizen="100" autonewline="1">'.$gbook[0].'</label>
        </frame>
        <frame posn="-1 -21 0">
            <quad posn="0 0 0" sizen="100 17" style="Bgs1" substyle="BgList"/>
            <label posn="1 -1 1" sizen="100" autonewline="1">'.$gbook[1].'</label>
        </frame>
        <frame posn="-1 -38 0">
            <quad posn="0 0 0" sizen="100 17" style="Bgs1" substyle="BgList"/>
            <label posn="1 -1 1" sizen="100" autonewline="1">'.$gbook[2].'</label>
        </frame>
        <frame posn="-1 -55 0">
            <quad posn="0 0 0" sizen="100 5" style="Bgs1" substyle="BgList"/>
        <label posn="50 -2.5 1" halign="center" valign="center" textid="adde" style="CardButtonMedium" manialink="'.$this->encurl().'&amp;add" addplayerid="1"/>';
        if($page == 0)
            echo '<quad posn="1 -2.5 1" sizen="3.5 3.5" valign="center" style="Icons64x64_1" substyle="StarGold"/>';
        else
            echo '<quad posn="1 -2.5 1" sizen="3.5 3.5" valign="center" style="Icons64x64_1" substyle="ArrowPrev" manialink="'.$this->encurl().'&amp;page='.($page-1).'"/>';
        if($page >= count($gbooka)/3-1)
            echo '<quad posn="99 -2.5 1" sizen="3.5 3.5" halign="right" valign="center" style="Icons64x64_1" substyle="StarGold"/>';
        else
            echo '<quad posn="99 -2.5 1" sizen="3.5 3.5" halign="right" valign="center" style="Icons64x64_1" substyle="ArrowNext" manialink="'.$this->encurl().'&amp;page='.($page+1).'"/>';
        echo '</frame>';
    }
    
    public function configure()
    {
        global $_dico, $_db;
		$_dico['de'] += Array(
			'edit' => '$fffBearbeiten',
            'delete' => '$fffEntfernen',
            'apply' => 'Übernehmen',
            'delold' => "Alle löschen außer\ndie neuesten"
		);
		$_dico['en'] += Array(
			'edit' => '$fffEdit',
            'delete' => '$fffDelete',
            'apply' => 'Apply',
            'delold' => "Delete all except\nthe newest"
		);
        $this->endpos = '50 -54 1';
        
        if(isset($_GET['edit']))
        {
            if($_GET['text'] == '')
            {
                $entry = self::getSharedListItem('gbook', $_GET['edit']);
                $entry = explode('&#0;', $entry, 2);
                echo '<label posn="0 0 0" style="TextRankingsBig" textid="adde"/>
                <frame posn="-1 -4 0">
                    <quad posn="0 0 0" sizen="100 26" style="Bgs1" substyle="BgList"/>
                    <label posn="1 -1 1" text="Login:"/>
                    <entry posn="12 -1 1" sizen="20 3" default="'.$entry[0].'" name="login"/>
                    <label posn="1 -4.5 1" text="Text:"/>
                    <entry posn="12 -4.5 1" sizen="87 16.5" default="'.htmlspecialchars(utf8_decode($entry[1])).'" name="text" autonewline="1"/>
                    <label posn="50 -21.5 1" halign="center" textid="apply" style="CardButtonMedium" manialink="'.$this->encurl().'&amp;edit='.$_GET['edit'].'&amp;text=text&amp;login=login"/>
                </frame>';
                return;
            }
            else
                self::setSharedListItem('gbook', $_GET['edit'], $_GET['login'].'&#0;'.stripslashes($_GET['text']));
        }
        elseif(isset($_GET['delete']))
            self::deleteSharedListItem('gbook', $_GET['delete']);
        elseif(isset($_GET['delold']))
        {
            $entrys = self::getSharedListArray('gbook');
            $entrys = array_keys($entrys);
            $entrys = array_reverse($entrys);
            if($_GET['delold'] == 0)
                $_db->query("DELETE FROM `".PREFIX."_shared_lists` WHERE `list` = 'gbook'");
            else
                $_db->query("DELETE FROM `".PREFIX."_shared_lists` WHERE `list` = 'gbook' AND `property` < ".($entrys[intval($_GET['delold'])-1]));
        }
        
        $gbooka = self::getSharedListArray('gbook');
        $page = intval($_GET['epg']);
        function _walker(&$value, $key)
        {
            $value = array('text' => $value, 'id' => $key);
        }
        array_walk($gbooka, '_walker');
        ksort($gbooka);
        $gbook = array_reverse($gbooka, false);
		$gbook = array_slice($gbook, 3*$page);
        function _exploder_g($x)
        {
            $ret = explode("&#0;", $x['text'], 2);
            return array('text' => $ret[1], 'id' => $x['id']);
        }
		$gbook = array_map('_exploder_g', $gbook);
        echo '<frame posn="-1 0 0">
            <quad posn="0 0 0" sizen="100 17" style="Bgs1" substyle="BgList"/>
            <label posn="1 -1 1" sizen="100" autonewline="1">'.$gbook[0]['text'].'</label>';
        if(isset($gbook[0]))
            echo '<label posn="99 -1 1" halign="right" style="TextCardScores2" textid="edit" manialink="'.$this->encurl().'&amp;edit='.$gbook[0]['id'].'"/>
            <label posn="99 -4.5 1" halign="right" style="TextCardScores2" textid="delete" manialink="'.$this->encurl().'&amp;delete='.$gbook[0]['id'].'"/>';
        echo '</frame>
        <frame posn="-1 -17 0">
            <quad posn="0 0 0" sizen="100 17" style="Bgs1" substyle="BgList"/>
            <label posn="1 -1 1" sizen="100" autonewline="1">'.$gbook[1]['text'].'</label>';
        if(isset($gbook[1]))
            echo '<label posn="99 -1 1" halign="right" style="TextCardScores2" textid="edit" manialink="'.$this->encurl().'&amp;edit='.$gbook[1]['id'].'"/>
            <label posn="99 -4.5 1" halign="right" style="TextCardScores2" textid="delete" manialink="'.$this->encurl().'&amp;delete='.$gbook[1]['id'].'"/>';
        echo '</frame>
        <frame posn="-1 -34 0">
            <quad posn="0 0 0" sizen="100 17" style="Bgs1" substyle="BgList"/>
            <label posn="1 -1 1" sizen="100" autonewline="1">'.$gbook[2]['text'].'</label>';
        if(isset($gbook[2]))
            echo '<label posn="99 -1 1" halign="right" style="TextCardScores2" textid="edit" manialink="'.$this->encurl().'&amp;edit='.$gbook[2]['id'].'"/>
            <label posn="99 -4.5 1" halign="right" style="TextCardScores2" textid="delete" manialink="'.$this->encurl().'&amp;delete='.$gbook[2]['id'].'"/>';
        echo '
        </frame>
        <frame posn="-1 -51 0">
            <quad posn="0 0 0" sizen="100 6" style="Bgs1" substyle="BgList"/>';
        if($page == 0)
            echo '<quad posn="1 -3 1" sizen="3.5 3.5" valign="center" style="Icons64x64_1" substyle="StarGold"/>';
        else
            echo '<quad posn="1 -3 1" sizen="3.5 3.5" valign="center" style="Icons64x64_1" substyle="ArrowPrev" manialink="'.$this->encurl().'&amp;epg='.($page-1).'"/>';
        if($page >= count($gbooka)/3-1)
            echo '<quad posn="99 -3 1" sizen="3.5 3.5" halign="right" valign="center" style="Icons64x64_1" substyle="StarGold"/>';
        else
            echo '<quad posn="99 -3 1" sizen="3.5 3.5" halign="right" valign="center" style="Icons64x64_1" substyle="ArrowNext" manialink="'.$this->encurl().'&amp;epg='.($page+1).'"/>';
        $_dico['de']['eintr'] = count($gbooka).' Einträge';
        $_dico['en']['eintr'] = count($gbooka).' entrys';
        echo '<label posn="6 -3 1" valign="center" textid="eintr"/>
        <label posn="62 -0.8 1" textid="delold" textsize="2"/>
        <entry posn="72 -3.25 1" sizen="4 2" textsize="2" name="delold"/>
        <label posn="77 -3.25 1" textsize="2">$h['.$this->encurl().'&amp;delold=delold]OK$h</label>
        </frame>';
    }
}