<?php
/**
 * Erstellen der Navigation
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.gnu.org/licenses/gpl.html> GNU Gerneral Public License
 */

echo "<frame posn=\"{$_theme[nav_posn]} 1\">
<format textsize=\"$_theme[navbutton_textsize]\" textcolor=\"$_theme[navbutton_textcolor]\" />";
$query = $_db->query('SELECT * FROM `'.PREFIX.'_navigation` WHERE `submenu` IS NULL ORDER BY `order`');
$y = 0;
$x = 0;

if(!(isset($_GET['p']) || isset($_GET['t'])))
{
    $_SESSION['nb'] = $_db->query("SELECT `id` FROM `".PREFIX."_navigation` WHERE `type` = 'textpage' AND `target` = 1 LIMIT 1")->fetch_object()->id;
    unset($_SESSION['sm']);
    unset($_SESSION['sb']);
}
if(isset($_GET['sm']) || isset($_GET['sb']))
{
	if(isset($_GET['sm']))
		$_SESSION['sm'] = $_GET['sm'];
	if(isset($_GET['sb']))
		$_SESSION['sb'] = $_GET['sb'];
	unset($_SESSION['nb']);
}
elseif(isset($_GET['nb']))
{
    $_SESSION['nb'] = $_GET['nb'];
    unset($_SESSION['sm']);
    unset($_SESSION['sb']);
}

$nb = &$_SESSION['nb'];
$sm = &$_SESSION['sm'];
$sb = &$_SESSION['sb'];

$spos = array();
while($row = $query->fetch_object())
{   
    if($y >= $_theme['nav_column_width'])
    {   //$x und $y sind vertauscht!?
        $y = 0;
        $x++;
    }
    echo '<frame posn="'.($_theme['navbutton_distance_x']*$y).' '.(-$_theme['navbutton_distance_y']*$x).'">';
    if(!in_array($row->imageid, $_theme['custom_buttons']))
        echo '<label posn="'.$_theme['navbutton_textpos'].' 1"
                     textid="nav-'.$row->id.'"
                     halign="center"
                     valign="center"/>';
    switch($row->type)
    {
    case 'textpage':
        $navi = "manialink=\"".KYUSS."?t={$row->target}&amp;nb=$row->id\"";
        break;
    case 'page':
        if($row->params == '')
            $navi = "manialink=\"".KYUSS."?p={$row->target}&amp;nb=$row->id\"";
        else
            $navi = "manialink=\"".KYUSS."?p={$row->target}&amp;nb=$row->id&amp;{$row->params}\"";
        break;
    case 'url':
        $navi = "url=\"{$row->target}\"";
        break;
    case 'submenu':
        if(isset($_GET['p']))
            $navi = "manialink=\"".KYUSS."?p=$_GET[p]&amp;sm=$row->id";
        elseif(isset($_GET['t']))
            $navi = "manialink=\"".KYUSS."?t=$_GET[t]&amp;sm=$row->id";
        else
            $navi = "manialink=\"".KYUSS."?sm=$row->id";
        if(isset($_SESSION['sm']))
        {
            if($_theme['sub_posn_relative'])
                $spos[$row->id] = ($_theme['sub_posn_x']+$_theme['navbutton_distance_x']*$y).' '.($_theme['sub_posn_y']-$_theme['navbutton_distance_y']*$x);
            $scount = $_db->query('SELECT COUNT(`id`) AS `count` FROM `'.PREFIX.'_navigation` WHERE `submenu` = (SELECT `target` FROM '.PREFIX.'_navigation WHERE `type` = "submenu" AND `id` = '.intval($_SESSION['sm']).')')->fetch_object()->count;
            $y += (double)$_theme['sub_move_aside_x']/$_theme['navbutton_distance_x']*$scount;
            $x += (double)$_theme['sub_move_aside_y']/$_theme['navbutton_distance_y']*$scount;
        }
        
        $navi .= '"';
        break;
    default:
        $navi = "manialink=\"{$row->target}\"";
    }
    if(!in_array($row->imageid, $_theme['custom_buttons']))
    {
        if(isset($_SESSION['sm']))
            $img = ($row->id == $sm) ? $_theme['navbutton_subselected'] : $_theme['navbutton'];
        else
            $img = ($row->id == $nb) ? $_theme['navbutton_selected'] : $_theme['navbutton'];
        echo '<quad posn="0 0 0" sizen="'.$_theme['navbutton_sizen'].'" image="'.$_theme['foldername'].'/'.$img.'"';
        if($row->id != $nb || $_theme['hover_selected'])
            echo ' imagefocus="'.$_theme['foldername'].'/'.$_theme['navbutton_hover'].'"';
        echo ' '.$navi.'/>';
    }
    else
    {
        if(isset($_SESSION['sm']))
            $img = ($row->id == $sm) ? $row->imageid.'_selected.png' : $row->imageid.'.png';
        else
            $img = ($row->id == $nb) ? $row->imageid.'_selected.png' : $row->imageid.'.png';
        echo '<quad posn="0 0 0" sizen="'.$_theme['navbutton_sizen'].'" image="'.$_theme['foldername'].'/'.$img.'"';
        if($row->id != $nb || $_theme['hover_selected'])
            echo ' imagefocus="'.$_theme['foldername'].'/'.$row->imageid.'_hover.png"';
        echo ' '.$navi.'/>';
    }
    $y++;
    echo '</frame>';
}

if(isset($_SESSION['sm']))
{
    $query = $_db->query('SELECT * FROM `'.PREFIX.'_navigation` WHERE `submenu` = (SELECT `target` FROM `'.PREFIX.'_navigation` WHERE `type` = "submenu" AND `id` = '.intval($_SESSION['sm']).') ORDER BY `order`');
    if($_theme['sub_posn_relative'])
        echo '<frame posn="'.$spos[$sm].' 1">';
    else
        echo '<frame posn="0 0 1">';
    $x = 0;
    $y = 0;
    
    echo '<format textsize="'.$_theme['sub_textsize'].'" textcolor="'.$_theme['sub_textcolor'].'" />';
    
    while($row = $query->fetch_object())
    {
        if($y >= $_theme['sub_column_width'])
        {
            $y = 0;
            $x++;
        }
        echo '<frame posn="'.($_theme['sub_distance_x']*$y).' '.(-$_theme['sub_distance_y']*$x).'">';
        if(!in_array($row->imageid, $_theme['custom_buttons']))
            echo '<label posn="'.$_theme['sub_textpos'].' 1"
                         textid="nav-'.$row->id.'"
                         halign="center"
                         valign="center"/>';
        switch($row->type)
        {
        case 'textpage':
            $navi = "manialink=\"".KYUSS."?t={$row->target}&amp;sb=$row->id\"";
            break;
        case 'page':
            if($row->params == '')
                $navi = "manialink=\"".KYUSS."?p={$row->target}&amp;sb=$row->id\"";
            else
                $navi = "manialink=\"".KYUSS."?p={$row->target}&amp;sb=$row->id&amp;{$row->params}\"";
            break;
        case 'url':
            $navi = "url=\"{$row->target}\"";
            break;
        default:
            $navi = "manialink=\"{$row->target}\"";
        }
        if(!in_array($row->imageid, $_theme['custom_buttons']))
        {
            $img = ($row->id == $sb) ? $_theme['sub_selected'] : $_theme['sub'];
            echo '<quad posn="0 0 0" sizen="'.$_theme['sub_sizen'].'" image="'.$_theme['foldername'].'/'.$img.'"';
            if($row->id != $sb || $_theme['hover_selected'])
                echo ' imagefocus="'.$_theme['foldername'].'/'.$_theme['sub_hover'].'"';
            echo ' '.$navi.'/>';
        }
        else
        {
            $img = ($row->id == $sb) ? $row->imageid.'_selected.png' : $row->imageid.'.png';
            echo '<quad posn="0 0 0" sizen="'.$_theme['sub_sizen'].'" image="'.$_theme['foldername'].'/'.$img.'"';
            if($row->id != $sb || $_theme['hover_selected'])
                echo ' imagefocus="'.$_theme['foldername'].'/'.$row->imageid.'_hover.png"';
            echo ' '.$navi.'/>';
        }
        $y++;
        echo '</frame>';
    }
    echo '</frame>';
}
?>
</frame>