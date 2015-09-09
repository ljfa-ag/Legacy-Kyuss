<?php
/**
 * Eine Seite, auf der Besucher Neuigkeiten eintragen können.
 * @package Kyuss
 * @subpackage news
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 * @version 1.0.1
 */
class news extends page
{
    public static $name = 'News';
    public static $desc = 'Eine Seite, auf der Besucher Neuigkeiten eintragen können.';
    public static $hasConfig = true;
    public static $shared_lists = Array('news');
    
    public function show()
    {
        addDico('de', array(
            'back' => '$000Zurück'
        ));
        addDico('en', array(
            'back' => '$000Back'
        ));
        
        if(isset($_GET['but']))
        {
            $key = intval($_GET['but']);
            $entry = self::getSharedListItem('news', $key, new newsentry(
                array(
                    'de' => 'Fehler 404',
                    'en' => 'Error 404'
                ),
                array(
                    'de' => 'Diese Neuigkeit existert nicht!',
                    'en' => 'This news does not exist!'
                ),
                time()
            ));
            foreach($entry->title as $lang => $title)
                addDico($lang, array('newstitle'.$key => '$fff'.$title));
            foreach($entry->text as $lang => $text)
                addDico($lang, array('newstext'.$key => '$fff'.$text));
            echo '<label posn="0 0 0" style="TextRankingsBig" textid="newstitle'.$key.'"/>
            <label posn="100 0 0" halign="right" text="'.$entry->getDateString().'"/>
            <label posn="0 -5 0" textid="newstext'.$key.'"/>
            <quad posn="10 -57 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.$this->encurl().'"/>
            <label posn="10 -57 0" valign="center" halign="center" textid="back" textcolor="000"/>';
            return;
        }
        
        echo '<label posn="0 0 0" style="TextRankingsBig" text="News"/>';
        $page = intval($_GET['pg']);
        $entrysa = self::getSharedListArray('news');
        ksort($entrysa);
        $entrys = array_reverse($entrysa, true);
        $entrys = array_slice($entrys, 14*$page, 14, true);
        $y = -5;
        foreach($entrys as $key => $entry)
        {
            foreach($entry->title as $lang => $title)
                addDico($lang, array('newstitle'.$key => '$fff'.$title));
            echo '<label posn="0 '.$y.' 0" style="TextCardRaceRank" manialink="'.$this->encurl().'&amp;but='.$key.'" textid="newstitle'.$key.'"/>';
            $y -= 3.5;
        }
        if($page == 0)
            echo '<quad posn="1 -57 1" sizen="3.5 3.5" valign="center" style="Icons64x64_1" substyle="StarGold"/>';
        else
            echo '<quad posn="1 -57 1" sizen="3.5 3.5" valign="center" style="Icons64x64_1" substyle="ArrowPrev" manialink="'.$this->encurl().'&amp;pg='.($page-1).'"/>';
        if($page >= count($entrysa)/14-1)
            echo '<quad posn="99 -57 1" sizen="3.5 3.5" halign="right" valign="center" style="Icons64x64_1" substyle="StarGold"/>';
        else
            echo '<quad posn="99 -57 1" sizen="3.5 3.5" halign="right" valign="center" style="Icons64x64_1" substyle="ArrowNext" manialink="'.$this->encurl().'&amp;pg='.($page+1).'"/>';
    }
    
    public function configure()
    {
        global $_dico, $_db;
        addDico('de', array(
            'newsv' => 'News verwalten',
            //'delold' => "Alle löschen außer\ndie neuesten",
            'back' => '$000Zurück',
            'date' => 'Datum:',
            'lang-en' => '$fffEnglisch',
            'lang-cz' => '$fffTschechisch',
            'lang-de' => '$fffDeutsch',
            'lang-es' => '$fffSpanisch',
            'lang-fr' => '$fffFranzösisch',
            'lang-hu' => '$fffUngarisch',
            'lang-it' => '$fffItalienisch',
            'lang-jp' => '$fffJapanisch',
            'lang-kr' => '$fffKroatisch',
            'lang-nl' => '$fffNiederländisch',
            'lang-pl' => '$fffPolnisch',
            'lang-pt' => '$fffPortugiesisch',
            'lang-ru' => '$fffRussisch',
            'lang-sk' => '$fffSlowakisch',
            'lang-zh' => '$fffChinesisch',
            'tlang' => 'Textsprache:',
            'title' => 'Titel:',
            'save' => 'Speichern',
            'b_delete' => 'Entfernen',
            'tcs2save' => '$fffSpeichern',
            'b_add' => 'Hinzufügen'
        ));
        addDico('en', array(
            'newsv' => 'Manage news',
            //'delold' => "Delete all except\nthe newest",
            'back' => '$000Back',
            'date' => 'Date:',
            'lang-en' => '$fffEnglish',
            'lang-cz' => '$fffCzech',
            'lang-de' => '$fffGerman',
            'lang-es' => '$fffSpanish',
            'lang-fr' => '$fffFrench',
            'lang-hu' => '$fffHungarian',
            'lang-it' => '$fffItalian',
            'lang-jp' => '$fffJapanese',
            'lang-kr' => '$fffCroatian',
            'lang-nl' => '$fffDutch',
            'lang-pl' => '$fffPolish',
            'lang-pt' => '$fffPortugese',
            'lang-ru' => '$fffRussian',
            'lang-sk' => '$fffSlowakian',
            'lang-zh' => '$fffChinese',
            'tlang' => 'Text language:',
            'title' => 'Title:',
            'save' => 'Save',
            'b_delete' => 'Delete',
            'tcs2save' => '$fffSave',
            'b_add' => 'Add'
        ));
        
        echo '<label posn="0 0 0" style="TextRankingsBig" textid="newsv"/>';
        
        if(isset($_GET['addbut']))
        {
            $added = max(array_keys(self::getSharedListArray('news'))) + 1;
            self::setSharedListItem('news', $added, new newsentry(array(), array(), time()));
        }
        if(isset($_GET['but']) || isset($added))
        {
            $key = isset($added) ? $added : intval($_GET['but']);
            $entry = self::getSharedListItem('news', $key);
            
            if(isset($_GET['addlang']))
            {
                $langs = array('en', 'cz', 'de', 'es', 'fr', 'hu', 'it', 'jp', 'kr', 'nl', 'pl', 'pt', 'ru', 'sk', 'zh');
                $y = 0;
                foreach($langs as $lang)
                {
                    $pos = -4-3*$y;
                    echo '<label posn="0 '.$pos.' 0" textid="lang-'.$lang.'" style="TextCardScores2" manialink="'.$this->encurl().'&amp;but='.$key.'&amp;lang='.$lang.'"/>';
                    $y++;
                }
                return;
            }
            elseif(isset($_GET['lang']))
            {
                $lang = $_GET['lang'];
                
                if(isset($_GET['title']))
                {
                    $entry->title[$lang] = stripslashes($_GET['title']);
                    $entry->text[$lang] = stripslashes($_GET['text']);
                    self::setSharedListItem('news', $key, $entry);
                }
                
                echo '<label posn="0 -5 0" textid="title"/>
                <entry posn="10 -5 0" sizen="30 2.5" default="'.$entry->title[$lang].'" name="title"/>
                <label posn="0 -8.5 0" text="Text:"/>
                <entry posn="10 -8.5 0" sizen="90 40" default="'.htmlspecialchars(utf8_decode($entry->text[$lang])).'" autonewline="1" name="text" textsize="2"/>';
                
                echo '<quad posn="31 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.$this->encurl().'&amp;but='.$key.'&amp;lang='.$lang.'&amp;title=title&amp;text=text"/>
                <label posn="31 -52 0" valign="center" halign="center" textid="save" textcolor="000"/>
                <quad posn="31 -57 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.$this->encurl().'&amp;but='.$key.'&amp;dellang='.$lang.'"/>
                <label posn="31 -57 0" valign="center" halign="center" textid="b_delete" textcolor="000"/>
                <quad posn="10 -57 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.$this->encurl().'&amp;but='.$key.'"/>
                <label posn="10 -57 0" valign="center" halign="center" textid="back" textcolor="000"/>';
                return;
            }
            elseif(isset($_GET['dellang']))
            {
                unset($entry->title[$_GET['dellang']]);
                unset($entry->text[$_GET['dellang']]);
                self::setSharedListItem('news', $key, $entry);
            }
            elseif(isset($_GET['date']))
            {
                $entry->date = strtotime($_GET['date']);
                self::setSharedListItem('news', $key, $entry);
            }
            
            foreach($entry->title as $lang => $title)
                addDico($lang, array('newstitle'.$key => '$fff'.$title));
            foreach($entry->text as $lang => $text)
                addDico($lang, array('newstext'.$key => '$fff'.$text));
            echo '<label posn="0 -5 0" textid="date"/>
            <entry posn="10 -5 0" sizen="12 2.5" default="'.$entry->getDateString().'" name="date"/>
            <label posn="0 -8 0" textid="tcs2save" style="TextCardScores2" manialink="'.$this->encurl().'&amp;but='.$key.'&amp;date=date"/>
            <label posn="0 -13 0" textid="tlang"/>';
            $y = 0;
            foreach(array_keys($entry->title) as $lang)
            {
                $pos = -16-3.5*$y;
                echo '<label posn="0 '.$pos.' 0" textid="lang-'.$lang.'" style="TextCardScores2" manialink="'.$this->encurl().'&amp;but='.$key.'&amp;lang='.$lang.'"/>';
                $y++;
            }
            $pos = -17.5-3.5*$y;
            echo '<label posn="0 '.$pos.' 0" textid="add" style="TextCardScores2" manialink="'.$this->encurl().'&amp;but='.$key.'&amp;addlang"/>';
            
            echo '<quad posn="10 -57 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.$this->encurl().'"/>
            <label posn="10 -57 0" valign="center" halign="center" textid="back" textcolor="000"/>
            <quad posn="31 -57 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.$this->encurl().'&amp;delbut='.$key.'"/>
            <label posn="31 -57 0" valign="center" halign="center" textid="b_delete" textcolor="000"/>';
            return;
        }
        elseif(isset($_GET['delbut']))
            self::deleteSharedListItem('news', $_GET['delbut']);
        /*elseif(isset($_GET['delold']))
        {
            $entrys = self::getSharedListArray('news');
            $entrys = array_keys($entrys);
            $entrys = array_reverse($entrys);
            if($_GET['delold'] == 0)
                $_db->query("DELETE FROM `".PREFIX."_shared_lists` WHERE `list` = 'news'");
            else
                $_db->query("DELETE FROM `".PREFIX."_shared_lists` WHERE `list` = 'news' AND `property` < ".($entrys[intval($_GET['delold'])-1]));
        } Scheint irgendwie nicht zu funktionieren */
        
        $page = intval($_GET['pg']);
        $entrysa = self::getSharedListArray('news');
        ksort($entrysa);
        $entrys = array_reverse($entrysa, true);
        $entrys = array_slice($entrys, 12*$page, 12, true);
        $y = -5;
        foreach($entrys as $key => $entry)
        {
            foreach($entry->title as $lang => $title)
                addDico($lang, array('newstitle'.$key => '$fff'.$title));
            echo '<label posn="0 '.$y.' 0" style="TextCardRaceRank" manialink="'.$this->encurl().'&amp;but='.$key.'" textid="newstitle'.$key.'"/>';
            $y -= 3.5;
        }
        if($page == 0)
            echo '<quad posn="1 -57 1" sizen="3.5 3.5" valign="center" style="Icons64x64_1" substyle="StarGold"/>';
        else
            echo '<quad posn="1 -57 1" sizen="3.5 3.5" valign="center" style="Icons64x64_1" substyle="ArrowPrev" manialink="'.$this->encurl().'&amp;pg='.($page-1).'"/>';
        if($page >= count($entrysa)/12-1)
            echo '<quad posn="99 -57 1" sizen="3.5 3.5" halign="right" valign="center" style="Icons64x64_1" substyle="StarGold"/>';
        else
            echo '<quad posn="99 -57 1" sizen="3.5 3.5" halign="right" valign="center" style="Icons64x64_1" substyle="ArrowNext" manialink="'.$this->encurl().'&amp;pg='.($page+1).'"/>';
        $_dico['de']['eintr'] = count($entrysa).' Einträge';
        $_dico['en']['eintr'] = count($entrysa).' entrys';
        echo '<label posn="6 -57 1" valign="center" textid="eintr"/>
        <label posn="62 -54.8 1" textid="delold" textsize="2"/>
        <!--<entry posn="72 -57.25 1" sizen="4 2" textsize="2" name="delold"/>
        <label posn="77 -57.25 1" textsize="2">$h['.$this->encurl().'&amp;delold=delold]OK$h</label>-->
        <quad posn="31 -57 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.$this->encurl().'&amp;addbut"/>
        <label posn="31 -57 0" valign="center" halign="center" textid="b_add" textcolor="000"/>';
    }
}

class newsentry
{
    public $title;
    public $text;
    public $date;
    
    public function __construct($title, $text, $date)
    {
        $this->title = is_array($title) ? $title : array('en' => $title);
        $this->text = is_array($text) ? $text : array('en' => $text);
        $this->date = $date;
    }
    
    public function getDateString() { return date('d.m.Y', $this->date); }
}