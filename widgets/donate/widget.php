<?php
/**
 * Zum Spenden
 * @package Kyuss
 * @subpackage donate
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.gnu.org/licenses/gpl.html> GNU Gerneral Public License
 * @version 1.1
 */
class donate extends widget
{
    public static $name = 'Donate';
	public static $desc = 'Zum Spenden';
    public static $hasConfig = true;
    
    public function show()
    {	
        $codes = self::getConfig('codes', array('', '', '', '', '', ''));
        $amounts = self::getConfig('amounts', array(10, 20, 50, 100, 250));
        echo '<quad posn="0 0 0" sizen="29.5 4" valign="center" style="Bgs1" substyle="NavButton"/>
        <quad posn="1 0 1" sizen="2.5 2.5" valign="center" style="Icons128x128_1" substyle="Coppers"/>
        <label posn="4 0 1" valign="center" text="$fffDonate" style="TextButtonSmall"/>
        <label posn="11.8 0 1" valign="center" text="$fff'.$amounts[0].'" style="TextButtonSmall" manialink="'.$codes[0].'"/>
        <label posn="15 0 1" valign="center" text="$fff'.$amounts[1].'" style="TextButtonSmall" manialink="'.$codes[1].'"/>
        <label posn="18 0 1" valign="center" text="$fff'.$amounts[2].'" style="TextButtonSmall" manialink="'.$codes[2].'"/>
        <label posn="20.75 0 1" valign="center" text="$fff'.$amounts[3].'" style="TextButtonSmall" manialink="'.$codes[3].'"/>
        <label posn="24.9 0 1" valign="center" text="$fff'.$amounts[4].'" style="TextButtonSmall" manialink="'.$codes[4].'"/>';
    }
    
    public function configure()
    {
  		global $_dico;
		
		$_dico['de'] += Array(
            'save' => '$fffSpeichern',
            'maniacode' => "Die URL für den Code lautet: \"".DIR."index.php?maniacode=widgets/donate/thanks\"",
            'amounts' => 'Beträge:'
		);
		$_dico['en'] += Array(
            'save' => '$fffSave',
            'maniacode' => "The URL for the code is: \"".DIR."index.php?maniacode=widgets/donate/thanks\"",
            'amounts' => 'Amounts:'
		);
        
        if(isset($_GET['code10']))
        {
            self::setConfig('codes', array(
                $_GET['code10'],
                $_GET['code20'],
                $_GET['code50'],
                $_GET['code100'],
                $_GET['code250']
            ));
            self::setConfig('amounts', array(
                intval($_GET['amount10']),
                intval($_GET['amount20']),
                intval($_GET['amount50']),
                intval($_GET['amount100']),
                intval($_GET['amount250'])
            ));
        }
        
        $codes = self::getConfig('codes', array('', '', '', '', ''));
        $amounts = self::getConfig('amounts', array(10, 20, 50, 100, 250));
        echo '<label posn="0 0 0" style="TextRankingsBig" text="Donatepanel"/>
        <label posn="0 -5 0" textid="amounts"/>
        <label posn="10 -5 0" text="Maniacodes:"/>
        <entry posn="0 -8.5 0" sizen="8 2.5" default="'.$amounts[0].'" name="amount10"/>
        <entry posn="10 -8.5 0" sizen="20 2.5" default="'.$codes[0].'" name="code10"/>
        <entry posn="0 -12 0" sizen="8 2.5" default="'.$amounts[1].'" name="amount20"/>
        <entry posn="10 -12 0" sizen="20 2.5" default="'.$codes[1].'" name="code20"/>
        <entry posn="0 -15.5 0" sizen="8 2.5" default="'.$amounts[2].'" name="amount50"/>
        <entry posn="10 -15.5 0" sizen="20 2.5" default="'.$codes[2].'" name="code50"/>
        <entry posn="0 -19 0" sizen="8 2.5" default="'.$amounts[3].'" name="amount100"/>
        <entry posn="10 -19 0" sizen="20 2.5" default="'.$codes[3].'" name="code100"/>
        <entry posn="0 -22.5 0" sizen="8 2.5" default="'.$amounts[4].'" name="amount250"/>
        <entry posn="10 -22.5 0" sizen="20 2.5" default="'.$codes[4].'" name="code250"/>
        <label posn="0 -29.5 0" style="TextCardRaceRank" textid="save" manialink="'.$this->encurl().'&amp;code10=code10&amp;code20=code20&amp;code50=code50&amp;code100=code100&amp;code250=code250&amp;code500=code500&amp;amount10=amount10&amp;amount20=amount20&amp;amount50=amount50&amp;amount100=amount100&amp;amount250=amount250"/>
        <label posn="0 -35.5 0" sizen="100" textid="maniacode"/>';
    }
}