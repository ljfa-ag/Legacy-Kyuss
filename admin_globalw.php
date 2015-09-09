<?php
/**
 * Ändern der globalen Widgets
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 */
if(!$sec)
    exit;

$_dico['en'] += Array(
    'save' => '$fffSave',
    'globalwv' => '$fffManage global widgets',
    'configure' => '$fffConfigure',
    'choose' => '$fffChoose position on the screen',
    'rights' => 'Max. rights number to configure:',
    'endc' => '$000End config',
    'chwidget' => 'Choose widget'
);
$_dico['de'] += Array(
    'save' => '$fffSpeichern',
    'globalwv' => '$fffGlobale Widgets verwalten',
    'configure' => '$fffKonfigurieren',
    'choose' => '$fffPosition auf dem Bildschirm wählen',
    'rights' => 'Max. Rechtezahl zum Konfigurieren:',
    'endc' => '$000Konfig. beenden',
    'chwidget' => 'Widget auswählen'
);

if(isset($_GET['chpos']))
{
    $cpos = explode(' ', $_theme['content_posn']);
    for($x = -64-$cpos[0]; $x < 64-$cpos[0]; $x += 4)
        for($y = -48-$cpos[1]+4; $y <= 48-$cpos[1]; $y += 4)
            echo '<label posn="'.$x.' '.$y.' 5" sizen="4 4" text=" " focusareacolor1="0000" focusareacolor2="fff5" manialink="'.KYUSS.'?p=admin&amp;action=globalw&amp;widget='.$_GET['widget'].'&amp;chx='.($x+$cpos[0]).'&amp;chy='.($y+$cpos[1]).'"/>';
}
if(isset($_GET['addwidget']) && $rank <= $perms['widgets'])
{
    if($_GET['addwidget'] == '')
    {
        $y = 0;
        echo '<label posn="0 0 0" style="TextRankingsBig" textid="chwidget"/>';
        $query = $_db->query("SELECT * FROM `".PREFIX."_widgets`");
        while($row = $query->fetch_object())
        {
            echo '<label posn="0 '.(-5-3.5*$y).' 0" text="$fff'.$row->name.'" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=globalw&amp;addwidget='.$row->folder_name.'"/>';
            $y++;
        } 
        return;
    }
    
    $_db->query("INSERT INTO `".PREFIX."_used_widgets` SET `name` = '".$_db->real_escape_string($_GET['addwidget'])."'");
    $wadded = $_db->insert_id;
    $widgets = getGeneralConfig('global_widgets');
    $widgets = (!$widgets) ? $wadded : $widgets.','.$wadded;
    setGeneralConfig('global_widgets', $widgets);
}
if(isset($_GET['widget']) OR isset($wadded))
{
    $widget = isset($_GET['widget']) ? intval($_GET['widget']) : $wadded;
    $row = $_db->query("SELECT * FROM `".PREFIX."_used_widgets` WHERE `id` = $widget")->fetch_object();
    if(isset($_GET['x']) && $rank <= $row->rights)
    {
        $query = $_db->prepare("UPDATE `".PREFIX."_used_widgets` SET `x` = ?, `y` = ?, `z` = ?, `rights` = ? WHERE `id` = ?");
        $query->bind_param('dddii', $_GET['x'], $_GET['y'], $_GET['z'], $_GET['rechte'], $widget);
        $query->execute();
        $query->close();
    }
    elseif(isset($_GET['chx']) && $rank <= $row->rights)
    {
        $query = $_db->prepare("UPDATE `".PREFIX."_used_widgets` SET `x` = ?, `y` = ? WHERE `id` = ?");
        $query->bind_param('ddi', $_GET['chx'], $_GET['chy'], $widget);
        $query->execute();
        $query->close();
    }
    elseif(isset($_GET['configure']) && $rank <= $row->rights)
    {
        include_once("widgets/$row->name/widget.php");
        $widget = new $row->name($row->name, true);
        $widget->widgetid = $row->id;
        try
        {
            $save = $widget->configure();
        }
        catch(exception $ex)
        {
            $_dico['de']['ex'] = "Das Konfigurationsseite von '$row->name' hat einen unbehandelten Ausnahmefehler verursacht:\n\$o".$ex->getMessage()."\$z\nStack-Trace:\n".$ex->getTraceAsString();
            $_dico['en']['ex'] = "The configuration page of '$row->name' has thrown an uncaught exception:\n\$o".$ex->getMessage()."\$z\nStack trace:\n".$ex->getTraceAsString();
            echo '<label textid="ex" textsize="2"/>';
        }
        echo '<quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=globalw&amp;widget='.$_GET['widget'].'"/>
        <label posn="10 -52 0" valign="center" halign="center" textid="endc" textcolor="000"/>';
        return;
    }
    elseif(isset($_GET['askdelwidg']) && $rank <= $row->rights)
    {
        $row = $_db->query("SELECT `name`, `rights` FROM `".PREFIX."_used_widgets` WHERE `id` = ".intval($_GET['widget']))->fetch_object();
        $_dico['en']['widgdel'] = "Do you really want to remove the widget '$row->name'?\n\$h[".KYUSS."?p=admin&action=globalw&delwidg=$_GET[widget]]Yes\$h   \$h[".KYUSS."?p=admin&action=globalw&widget=$_GET[widget]]No";
        $_dico['de']['widgdel'] = "Möchten Sie das Widget '$row->name' wirklich entfernen?\n\$h[".KYUSS."?p=admin&action=globalw&delwidg=$_GET[widget]]Ja\$h   \$h[".KYUSS."?p=admin&action=globalw&widget=$_GET[widget]]Nein";
        echo '<label posn="0 0 0" textid="widgdel"/>';
        return;
    }
    $row = $_db->query("SELECT * FROM `".PREFIX."_used_widgets` WHERE `id` = $widget")->fetch_object();
    $row2 = $_db->query("SELECT * FROM `".PREFIX."_widgets` WHERE `folder_name` = '$row->name'")->fetch_object();
    echo '<label posn="0 0 0" style="TextRankingsBig" text="'.$row2->name.'"/>';
    if($rank <= $row->rights)
    {
        echo '<label posn="0 -5 0" text="Position:"/>
        <entry posn="10 -5 0" sizen="6 2.5" default="'.$row->x.'" name="x"/>
        <label posn="13 -8 0" sizen="6" halign="center" text="X"/>
        <entry posn="17 -5 0" sizen="6 2.5" default="'.$row->y.'" name="y"/>
        <label posn="20 -8 0" sizen="6" halign="center" text="Y"/>
        <entry posn="24 -5 0" sizen="6 2.5" default="'.$row->z.'" name="z"/>
        <label posn="27 -8 0" sizen="6" halign="center" text="Z"/>
        <label posn="31 -5 0" textid="choose" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=globalw&amp;widget='.$widget.'&amp;chpos"/>
        <label posn="0 -12 0" textid="rights"/>
        <entry posn="35 -12 0" sizen="6 2.5" default="'.$row->rights.'" name="rechte"/>
        <label posn="0 -16 0" textid="save" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=globalw&amp;widget='.$widget.'&amp;x=x&amp;y=y&amp;z=z&amp;rechte=rechte"/>';
        if($row2->hasConfig != 0)
            echo '<label posn="0 -19.5 0" textid="configure" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=globalw&amp;widget='.$widget.'&amp;configure"/>';
        echo '<label posn="0 -23 0" textid="delete" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=globalw&amp;widget='.$widget.'&amp;askdelwidg"/>';
    }
    echo '<label posn="0 -26.5 0" textid="tcs2back" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=globalw"/>';
    return;
}
elseif(isset($_GET['delwidg']))
{
    $_db->query("DELETE FROM `".PREFIX."_used_widgets` WHERE `id` = ".intval($_GET['delwidg']));
    $_db->query("DELETE FROM `".PREFIX."_plugin_config` WHERE `widget_id` = ".intval($_GET['delwidg']));
    $widgets = explode(',', getGeneralConfig('global_widgets'));
    $id = array_search($_GET['delwidg'], $widgets);
    unset($widgets[$id]);
    $widgets = implode(',', $widgets);
    setGeneralConfig('global_widgets', $widgets);
}
echo '<label posn="0 0 0" style="TextRankingsBig" textid="globalwv"/>';
$widgets = explode(',', getGeneralConfig('global_widgets'));
foreach($widgets as $uwid)
{
    $row2 = $_db->query("SELECT `name`, `id` FROM `".PREFIX."_used_widgets` WHERE `id` = $uwid")->fetch_object();
    $row3 = $_db->query("SELECT `name` FROM `".PREFIX."_widgets` WHERE `folder_name` = '$row2->name'")->fetch_object();
    $pos = -5-3.5*$y;
    echo '<label posn="0 '.$pos.' 0" text="$fff'.$row3->name.'" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=globalw&amp;widget='.$row2->id.'"/>';
    $y++;
} 
$pos = -6.5-3.5*$y;

echo '<label posn="0 '.$pos.' 0" textid="add" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=globalw&amp;addwidget"/>
<quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin"/>
<label posn="10 -52 0" valign="center" halign="center" text="Back" textcolor="000"/>';
return;