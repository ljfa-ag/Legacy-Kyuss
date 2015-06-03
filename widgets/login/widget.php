<?php
/**
 * Direkter Login ins Adminpanel
 * @package Kyuss
 * @subpackage login
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 * @version 1.0.1
 */
class login extends widget
{
    public static $name = 'Admin-Login';
	public static $desc = 'Zum direkten Login ins Adminpanel.';
    
    public function show()
    {
		global $_dico;
		
		$_dico['en'] += Array(
			'password' => 'Password:'
		);
		$_dico['de'] += Array(
			'password' => 'Passwort:'
		);
		
		echo '<format textsize="2"/>';
		if(isset($_SESSION['user_id']) && $_SESSION['manialink_id'] == getGeneralConfig('manialink_id'))
			echo '<quad posn="9.5 0 0" sizen="14 7" style="Bgs1" substyle="NavButton"/>
            <label posn="16.5 -2 1" text="$fffAdminpanel" halign="center" valign="center" style="TextButtonSmall" manialink="'.KYUSS.'?p=admin"/>
            <label posn="16.5 -5 1" text="$fffLogout" halign="center" valign="center" style="TextButtonSmall" manialink="'.KYUSS.'?p=admin&amp;action=logout"/>';
		else
			echo '<quad posn="0 0 0" sizen="24 6.5" style="Bgs1" substyle="NavButton"/>
            <label posn="1 -1 1" text="Login:"/>
            <entry posn="9 -1 1" sizen="10 2" name="wlg"/>
            <label posn="1 -3.5 1" textid="password"/>
            <quad posn="9 -3.5 2" sizen="10 2" bgcolor="444"/>
            <entry posn="9 -3.5 1" sizen="10 2" name="wpw"/>
            <quad posn="18.75 -3.25 1" sizen="5 5" valign="center" style="Icons64x64_1" substyle="Check" manialink="'.KYUSS.'?p=admin&amp;login=wlg&amp;pw=wpw"/>';
    }
}