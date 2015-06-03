<?php
/**
 * Verwalten der Plugins
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.gnu.org/licenses/gpl.html> GNU Gerneral Public License
 */
if(!$sec)
    exit;

$_dico['en'] += Array(
    'pluginsv' => 'Manage plugins',
    'pages' => '$fffManage pages',
    'widgets' => '$fffManage widgets',
    'themes' => '$fffManage themes'
);
$_dico['de'] += Array(
    'pluginsv' => 'Plugins verwalten',
    'pages' => '$fffSeiten verwalten',
    'widgets' => '$fffWidgets verwalten',
    'themes' => '$fffThemes verwalten'
);

echo '<label posn="0 0 0" style="TextRankingsBig" textid="pluginsv"/>
<label posn="0 -5 0" style="TextCardScores2" textid="pages" manialink="'.KYUSS.'?p=admin&amp;action=pages"/>
<label posn="0 -8.5 0" style="TextCardScores2" textid="widgets" manialink="'.KYUSS.'?p=admin&amp;action=widgets"/>
<label posn="0 -12 0" style="TextCardScores2" textid="themes" manialink="'.KYUSS.'?p=admin&amp;action=themes"/>
<label posn="0 -16.5 0" style="TextCardScores2" textid="tcs2back" manialink="'.KYUSS.'?p=admin"/>';