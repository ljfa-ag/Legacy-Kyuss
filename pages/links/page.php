<?php
/**
 * Eine Seite, auf die man Links zu anderen Manialinks setzen kann.
 * @package Kyuss
 * @subpackage links
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 * @version 1.0.1
 */
class links extends page
{
    public static $name = 'Links';
    public static $desc = 'Eine Seite, auf die man Links zu anderen Manialinks setzen kann.';
    public static $hasConfig = true;
    public static $shared_lists = Array('links');
    
    public function show()
    {
        echo '<label posn="0 0 0" style="TextRankingsBig" text="Links"/>';
        
        $links = self::getSharedListArray('links');
        ksort($links);
        $x = 13;
        $y = -7;
        foreach($links as $link)
        {
            echo '<quad posn="'.$x.' '.$y.' 0" sizen="27 5" style="BgsPlayerCard" substyle="BgCard" valign="center" halign="center" manialink="'.htmlspecialchars($link[0]).'"/>
            <label posn="'.$x.' '.$y.' 1" sizen="27" valign="center" halign="center">'.htmlspecialchars($link[1]).'</label>';
            if($y <= -52)
            {
                $y = -7;
                $x += 27;
            }
            else
                $y -= 5;
        }
    }
    
    public function configure()
    {
        global $_dico;
        $_dico['de'] += Array(
            'linksv' => 'Links verwalten',
            'save' => '$fffSpeichern',
            'delete' => '$fffEntfernen',
            'back' => '$fffZurück',
            'addl' => '$000Hinzufügen'
        );
        $_dico['en'] += Array(
            'linksv' => 'Manage links',
            'save' => '$fffSave',
            'delete' => '$fffDelete',
            'back' => '$fffBack',
            'addl' => '$000Add'
        );
        
        echo '<label posn="0 0 0" style="TextRankingsBig" textid="linksv"/>';
        
        if(isset($_GET['but']))
        {
            if(isset($_GET['code']))
                self::setSharedListItem('links', $_GET['but'], array($_GET['code'], stripslashes($_GET['text'])));
            $link = self::getSharedListItem('links', $_GET['but'], array('', ''));
            echo '<label posn="0 -5 0" text="Code:"/>
            <entry posn="7 -5 0" sizen="30 2.5" default="'.$link[0].'" name="code"/>
            <label posn="0 -8.5 0" text="Text:"/>
            <entry posn="7 -8.5 0" sizen="30 2.5" default="'.htmlspecialchars(utf8_decode($link[1])).'" name="text"/>
            <label posn="0 -12 0" style="TextCardRaceRank" textid="save" manialink="'.$this->encurl().'&amp;but='.$_GET['but'].'&amp;code=code&amp;text=text"/>
            <label posn="0 -15.5 0" style="TextCardRaceRank" textid="delete" manialink="'.$this->encurl().'&amp;delete='.$_GET['but'].'"/>
            <label posn="0 -19 0" style="TextCardRaceRank" textid="back" manialink="'.$this->encurl().'"/>';
            return;
        }
        elseif(isset($_GET['delete']))
            self::deleteSharedListItem('links', $_GET['delete']);
        
        $links = self::getSharedListArray('links');
        ksort($links);
        $x = 0;
        $y = -5;
        foreach($links as $key => $link)
        {
            echo '<label posn="'.$x.' '.$y.' 1" style="TextCardRaceRank" manialink="'.$this->encurl().'&amp;but='.$key.'">$fff'.htmlspecialchars($link[1]).'</label>';
            if($y <= -43.5)
            {
                $y = -5;
                $x += 27;
            }
            else
                $y -= 3.5;
        }
        $max = max(array_keys($links))+1;
        echo '<quad posn="10 -57 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.$this->encurl().'&amp;but='.$max.'"/>
        <label posn="10 -57 0" valign="center" halign="center" textid="addl" textcolor="000"/>';
    }
}