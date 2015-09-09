<?php
/**
 * Die Theme-Auswahl
 * @package Kyuss
 * @subpackage theme
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 * @version 1.0
 */
class theme extends page
{
    public static $name = 'Theme-Auswahl';
    public static $desc = 'Eine Seite, in der die Benutzer ihr favorisiertes Theme auswählen können.';
    
    public function show()
    {
        global $_db, $_dico;
        $_dico['en'] += Array(
            'accept' => 'Accept',
            'decline' => 'Decline'
        );
        $_dico['de'] += Array(
            'accept' => 'Übernehmen',
            'decline' => 'Abbrechen'
        );
        
        echo '<label posn="0 0 0" style="TextRankingsBig" text="Themes"/>';
        
        $standard = isset($_SESSION['user_theme']) ? $_SESSION['user_theme'] : getGeneralConfig('standard_theme');
        $y = 0;
        
        $query = $_db->query("SELECT * FROM `kyuss_themes`");
        while($row = $query->fetch_object())
        {
            echo '<label posn="0 '.(-5-3.5*$y++).' 0"
                         text="$fff'.$row->name.(($row->folder_name == $standard) ? ' (Ausgewählt)' : '').'"
                         style="TextCardScores2"
                         manialink="'.$this->url.'&amp;settheme='.$row->folder_name.'"/>';
        }
    }
}