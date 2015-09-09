<?php
/**
 * Zum Vorstellen der neuesten Strecke und des neuesten Skins
 * @package Kyuss
 * @subpackage showcase
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 * @version 1.0
 */
class showcase extends widget
{
    public static $name = 'Showcase';
    public static $desc = 'Zum Vorstellen der neuesten Strecke und des neuesten Skins';
    public static $shared_lists = array('tracks', 'skins');
    public static $hasConfig = true;
    
    public function show()
    {   
        $mode = self::getConfig('useSkins', false) ? 'skins' : 'tracks';
        $newest = self::getConfig('useNewest', false);
        $tracks = self::getSharedListArray($mode);
        if(count($tracks) == 0)
            return;
        elseif($newest)
            $number = count($tracks)-1;
        else
        {
            $tracks = array_values($tracks);
            $number = mt_rand(0, count($tracks)-1);
        }
        $track = $tracks[$number];
        
        echo '<quad posn="0 0 0" sizen="40 11" style="BgsPlayerCard" substyle="BgCard" manialink="'.KYUSS.'?p='.$mode.'&amp;info='.$track->id.'" addplayerid="1"/>
        <quad posn="1 -1 1" sizen="12 9" image="./'.$track->getImage().'"/>
        <label posn="14 -1 0" sizen="26" textsize="4" text="'.htmlspecialchars($track->name).'"/>
        <label posn="14 -4.5 0">'.$track->author."\nDownloads: ".$track->downloads.'</label>';
    }
    
    public function configure()
    {
        global $_dico;
        
        $_dico['de'] += Array(
            'type' => 'Typ:',
            'mode' => 'Modus:',
            'newest' => 'Neueste',
            'random' => 'Zufällig',
        );
        $_dico['en'] += Array(
            'type' => 'Type:',
            'mode' => 'Mode:',
            'newest' => 'Newest',
            'random' => 'Random',
        );
        
        if(isset($_GET['skins']))
            self::setConfig('useSkins', $_GET['skins'] != '0');
        if(isset($_GET['newest']))
            self::setConfig('useNewest', $_GET['newest'] != '0');
        
        echo '<label posn="0 -2 0" textid="type" valign="center"/>';
        if(self::getConfig('useSkins', false))
            echo '<quad posn="0 -6 0" sizen="4 4" image="./data/images/button.png" valign="center"/>
            <quad posn="0 -9 0" sizen="4 4" style="Icons64x64_1" substyle="Check" valign="center" manialink="'.$this->encurl().'&amp;skins=0"/>';
        else
            echo '<quad posn="0 -6 0" sizen="4 4" style="Icons64x64_1" substyle="Check" valign="center" manialink="'.$this->encurl().'&amp;skins=1"/>
            <quad posn="0 -9 0" sizen="4 4" image="./data/images/button.png" valign="center"/>';
        echo '<label posn="4 -6 0" text="Skin" valign="center"/>
        <label posn="4 -9 0" text="Track " valign="center"/>';
        
        echo '<label posn="0 -14 0" textid="mode" valign="center"/>';
        if(self::getConfig('useNewest', false))
            echo '<quad posn="0 -18 0" sizen="4 4" image="./data/images/button.png" valign="center"/>
            <quad posn="0 -21 0" sizen="4 4" style="Icons64x64_1" substyle="Check" valign="center" manialink="'.$this->encurl().'&amp;newest=0"/>';
        else
            echo '<quad posn="0 -18 0" sizen="4 4" style="Icons64x64_1" substyle="Check" valign="center" manialink="'.$this->encurl().'&amp;newest=1"/>
            <quad posn="0 -21 0" sizen="4 4" image="./data/images/button.png" valign="center"/>';
        echo '<label posn="4 -18 0" textid="newest" valign="center"/>
        <label posn="4 -21 0" textid="random" valign="center"/>';
    }
}

require_once('pages/tracks/track.class.php');
require_once('pages/skins/skin.class.php');