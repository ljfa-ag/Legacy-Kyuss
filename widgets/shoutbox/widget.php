<?php
/**
 * Eine Shoutbox
 * @package Kyuss
 * @subpackage shoutbox
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 * @version 1.0
 */
class shoutbox extends widget
{
    public static $name = 'Shoutbox';
    public static $desc = 'Eine Shoutbox';
    
    public function show()
    {
        global $_dico;
        
        $_dico['de'] += Array(
            'deny' => 'Sie haben vor kurzem schon einen Eintrag hinzugefügt!'
        );
        $_dico['en'] += Array(
            'deny' => 'You recently already added an entry!'
        );
        
        $deny = false;
        $eintraege = $this->getConfig('eintraege', Array());
        if($_GET['eintr'] != '')
        {
            $deny = explode('&#0;', $eintraege[0], 2);
            $deny = $deny[0] == $_SERVER['REMOTE_ADDR'] OR $_GET['playerlogin'] == '' OR $_GET['nickname'] == '' OR $_GET['lang'] == '' OR $_GET['path'] == '';
            if(!$deny)
            {
                $eintr = preg_replace("/[\\x00-\\x1F]/", '', trim($_GET['eintr']));
                array_unshift($eintraege, $_SERVER['REMOTE_ADDR'].'&#0;['.stripslashes($_GET['nickname'].'$z] '.$eintr));
                $eintraege = array_slice($eintraege, 0, 12);
                $this->setConfig('eintraege', $eintraege);
            }
        }
        
        echo '<quad posn="0 0 0" sizen="40 41" style="Bgs1" substyle="NavButton"/>';
        $y = -1;
        foreach($eintraege as $eintr)
        {
            $eintr = explode('&#0;', $eintr, 2);
            $eintr = htmlspecialchars($eintr[1]);
            echo '<label posn="1 '.$y.' 1" sizen="38">'.$eintr.'</label>';
            $y -= 3;
        }
        if($deny)
            echo '<label posn="1 -37.5 1" sizen="38" textid="deny"/>';
        else
            echo '<entry posn="1 -37.5 1" sizen="34.5 2.8" name="eintr"/>
        <quad posn="39.35 -37.15 1" halign="right" sizen="3.5 3.5" style="Icons64x64_1" substyle="Check" manialink="'.$this->encurl().'&amp;eintr=eintr" addplayerid="1"/>';
    }
}