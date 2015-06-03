<?php
/**
 * Erstellen der Textseiten
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 */

$tpage = isset($_GET['t']) ? $_GET['t'] : 1;
$query = $_db->prepare('SELECT * FROM `'.PREFIX.'_textpages` WHERE `id` = ?');
$query->bind_param('i', $tpage);
$query->execute();
$query->bind_result($id, $name, $textsize, $xml, $widgets);
$query->fetch();
$query->close();

echo $xml;
echo "<label posn=\"0 0 0\" textid=\"text-$id\" sizen=\"100\" autonewline=\"1\" textsize=\"$textsize\" textcolor=\"$_theme[content_textcolor]\"/>";

if(isset($_GET['chpos']))
{
    $widgets = explode(',', $widgets);
    $id = array_search($_GET['widget'], $widgets);
    unset($widgets[$id]);
    $widgets = implode(',', $widgets);
    $cpos = explode(' ', $_theme['content_posn']);
    for($x = -64-$cpos[0]; $x < 64-$cpos[0]; $x += 4)
        for($y = -48-$cpos[1]+4; $y <= 48-$cpos[1]; $y += 4)
            echo '<label posn="'.$x.' '.$y.' 5" sizen="4 4" text=" " focusareacolor1="0000" focusareacolor2="fff5" manialink="'.KYUSS.'?p=admin&amp;action=textpages&amp;but='.$tpage.'&amp;widget='.$_GET['widget'].'&amp;chx='.$x.'&amp;chy='.$y.'"/>';
}

widget::includeWidgets($widgets);

if($tpage == 1)
	$_SESSION['nb'] = $_db->query("SELECT `id` FROM `".PREFIX."_navigation` WHERE `type` = 'textpage' AND `target` = 1")->fetch_object()->id;
?>