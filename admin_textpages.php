<?php
/**
 * Verwalten der Textseiten
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 */
if(!$sec)
    exit;
$_dico['en'] += Array(
    'textpv' => 'Manage textpages',
    'textsize' => 'Text size:',
    'save' => '$fffSave',
    'customxml' => '$fffEdit additional custom XML',
    'lang-en' => '$fffEnglish',
    'lang-cz' => '$fffCzech',
    'lang-de' => '$fffGerman',
    'lang-es' => '$fffSpanish',
    'lang-fr' => '$fffFrench',
    'lang-hu' => '$fffHungarian',
    'lang-it' => '$fffItalian',
    'lang-jp' => '$fffJapanese',
    'lang-kr' => '$fffCroatian',
    'lang-nl' => '$fffDutch',
    'lang-pl' => '$fffPolish',
    'lang-pt' => '$fffPortugese',
    'lang-ru' => '$fffRussian',
    'lang-sk' => '$fffSlowakian',
    'lang-zh' => '$fffChinese',
    'tlang' => 'Text language:',
    'editt' => 'Edit text',
    'langcode' => 'Language code:',
    'add' => '$fffAdd',
    'rights' => 'Rights:',
    'configure' => '$fffConfigure',
    'chwidget' => 'Choose widget',
    'choose' => '$fffChoose',
    'endc' => '$000End config'
    
);
$_dico['de'] += Array(
    'textpv' => 'Textseiten verwalten',
    'textsize' => 'Textgröße:',
    'save' => '$fffSpeichern',
    'customxml' => '$fffZusätzliches benutzerdefiniertes XML bearbeiten',
    'lang-en' => '$fffEnglisch',
    'lang-cz' => '$fffTschechisch',
    'lang-de' => '$fffDeutsch',
    'lang-es' => '$fffSpanisch',
    'lang-fr' => '$fffFranzösisch',
    'lang-hu' => '$fffUngarisch',
    'lang-it' => '$fffItalienisch',
    'lang-jp' => '$fffJapanisch',
    'lang-kr' => '$fffKroatisch',
    'lang-nl' => '$fffNiederländisch',
    'lang-pl' => '$fffPolnisch',
    'lang-pt' => '$fffPortugiesisch',
    'lang-ru' => '$fffRussisch',
    'lang-sk' => '$fffSlowakisch',
    'lang-zh' => '$fffChinesisch',
    'tlang' => 'Textsprache:',
    'editt' => 'Text bearbeiten',
    'langcode' => 'Sprachcode:',
    'add' => '$fffHinzufügen',
    'rights' => 'Rechte:',
    'configure' => '$fffKonfigurieren',
    'chwidget' => 'Widget auswählen',
    'choose' => '$fffWählen',
    'endc' => '$000Konfig. beenden'
);

$url = KYUSS.'?p=admin&amp;action=textpages';

if(isset($_GET['chpos']))
{
    include("textpage.php");
    return;
}
elseif(isset($_GET['askdelpage']))
{
    $row = $_db->query("SELECT * FROM `".PREFIX."_textpages` WHERE `id` = ".intval($_GET['askdelpage']))->fetch_object();
    $_dico['en']['pagedel'] = "Do you really want to delete the textpage '$row->name'?\n\$h[".KYUSS."?p=admin&action=textpages&delpage=$_GET[askdelpage]]Yes\$h   \$h[".KYUSS."?p=admin&action=textpages]No";
    $_dico['de']['pagedel'] = "Möchten Sie die Textseite '$row->name' wirklich löschen?\n\$h[".KYUSS."?p=admin&action=textpages&delpage=$_GET[askdelpage]]Ja\$h   \$h[".KYUSS."?p=admin&action=textpages]Nein";
    echo '<label posn="0 0 0" textid="pagedel"/>';
    return;
}
elseif(isset($_GET['delpage']))
{
    $_db->query("DELETE FROM `".PREFIX."_textpages` WHERE `id` = ".intval($_GET['delpage']));
    $_db->query("DELETE FROM `".PREFIX."_dico` WHERE `name` = 'text-".intval($_GET['delpage'])."'");
}
elseif(isset($_GET['add']))
{
    $_db->query("INSERT INTO `".PREFIX."_textpages` () VALUES ()");
    $added = $_db->insert_id;
}
if(isset($_GET['but']) || isset($added))
{
    $but = isset($added) ? $added : intval($_GET['but']);
    if(isset($_GET['customxml']))
    {
        $row = $_db->query("SELECT `xml` FROM `".PREFIX."_textpages` WHERE `id` = '$but'")->fetch_object();
        echo '<label posn="0 0 0" style="TextRankingsBig" textid="customxml"/>
        <entry posn="0 -5 0" sizen="100 43" default="'.htmlspecialchars($row->xml).'" autonewline="1" name="xml" textsize="2"/>
        <quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.$url.'&amp;but='.$_GET['but'].'&amp;setcustomxml=xml"/>
        <label posn="10 -52 0" valign="center" halign="center" text="Save" textcolor="000"/>
        <quad posn="32 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.$url.'&amp;but='.$_GET['but'].'"/>
        <label posn="32 -52 0" valign="center" halign="center" text="Abort" textcolor="000"/>';
        return;
    }
    elseif(isset($_GET['langid']) && !isset($_GET['settext']))
    {
        if($_GET['langid'] == 'addlang')
        {
            $_db->query("INSERT INTO `".PREFIX."_dico` SET `name` = 'text-$but'");
            $langid = $_db->insert_id;
        }
        else
            $langid = intval($_GET['langid']);
        $row = $_db->query("SELECT * FROM `".PREFIX."_dico` WHERE `id` = '$langid'")->fetch_object();
        echo '<label posn="0 0 0" style="TextRankingsBig" textid="editt"/>
        <entry posn="0 -5 0" sizen="100 40" default="'.htmlspecialchars(utf8_decode($row->text)).'" autonewline="1" name="text" textsize="2"/>
        <quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.$url.'&amp;but='.$_GET['but'].'&amp;langid='.$row->id.'&amp;settext=text&amp;setlang=lang"/>
        <label posn="0 -46 0" textid="langcode"/>
        <entry posn="15 -46 0" sizen="4 2.5" default="'.$row->langcode.'" name="lang"/>
        <label posn="10 -52 0" valign="center" halign="center" text="Save" textcolor="000"/>
        <quad posn="32 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.$url.'&amp;but='.$_GET['but'].'"/>
        <label posn="32 -52 0" valign="center" halign="center" text="Abort" textcolor="000"/>
        <quad posn="54 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.$url.'&amp;but='.$_GET['but'].'&amp;askdelete='.$row->id.'"/>
        <label posn="54 -52 0" valign="center" halign="center" text="Delete" textcolor="000"/>';
        return;
    }
    elseif(isset($_GET['askdelete']))
    {
        $row = $_db->query("SELECT * FROM `".PREFIX."_dico` WHERE `id` = ".intval($_GET['askdelete']))->fetch_object();
        $_dico['en']['langdel'] = "Do you really want to delete the ".$_dico['en']['lang-'.$row->langcode]." text?\n\$h[".KYUSS."?p=admin&action=textpages&but=$_GET[but]&deletelangid=$_GET[askdelete]]Yes\$h   \$h[".KYUSS."?p=admin&action=textpages&but=$_GET[but]]No";
        $_dico['de']['langdel'] = "Möchten Sie den ".$_dico['de']['lang-'.$row->langcode]."en Text wirklich löschen?\n\$h[".KYUSS."?p=admin&action=textpages&but=$_GET[but]&deletelangid=$_GET[askdelete]]Ja\$h   \$h[".KYUSS."?p=admin&action=textpages&but=$_GET[but]]Nein";
        echo '<label posn="0 0 0" textid="langdel"/>';
        return;
    }
    elseif(isset($_GET['addwidget']))
    {
        if($_GET['addwidget'] == '')
        {
            $y = 0;
            echo '<label posn="0 0 0" style="TextRankingsBig" textid="chwidget"/>';
            $query = $_db->query("SELECT * FROM `".PREFIX."_widgets`");
            while($row = $query->fetch_object())
            {
                echo '<label posn="0 '.(-5-3.5*$y).' 0" text="$fff'.$row->name.'" style="TextCardScores2" manialink="'.$url.'&amp;but='.$but.'&amp;addwidget='.$row->folder_name.'"/>';
                $y++;
            } 
            return;
        }
        
        $_db->query("INSERT INTO `".PREFIX."_used_widgets` SET `name` = '".$_db->real_escape_string($_GET['addwidget'])."'");
        $wadded = $_db->insert_id;
        $widgets = $_db->query("SELECT `widgets` FROM `".PREFIX."_textpages` WHERE `id` = ".intval($_GET['but']))->fetch_object()->widgets;
        $widgets = (!$widgets) ? $wadded : $widgets.','.$wadded;
        $_db->query("UPDATE `".PREFIX."_textpages` SET `widgets` = '$widgets' WHERE `id` = ".intval($_GET['but']));
    }
    if(isset($_GET['widget']) || isset($wadded))
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
            echo <<<EOT
<quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="$url&amp;but=$_GET[but]&amp;widget=$_GET[widget]"/>
<label posn="10 -52 0" valign="center" halign="center" textid="endc" textcolor="000"/>
EOT;
            return;
        }
        elseif(isset($_GET['askdelwidg']) && $rank <= $row->rights)
        {
            $row = $_db->query("SELECT * FROM `".PREFIX."_used_widgets` WHERE `id` = ".intval($_GET['widget']))->fetch_object();
            $_dico['en']['widgdel'] = "Do you really want to remove the widget '$row->name' from this textpage?\n\$h[".KYUSS."?p=admin&action=textpages&but=$_GET[but]&delwidg=$_GET[widget]]Yes\$h   \$h[".KYUSS."?p=admin&action=textpages&but=$_GET[but]&widget=$_GET[widget]]No";
            $_dico['de']['widgdel'] = "Möchten Sie das Widget '$row->name' wirklich von dieser Textseite entfernen?\n\$h[".KYUSS."?p=admin&action=textpages&but=$_GET[but]&delwidg=$_GET[widget]]Ja\$h   \$h[".KYUSS."?p=admin&action=textpages&but=$_GET[but]&widget=$_GET[widget]]Nein";
            echo '<label posn="0 0 0" textid="widgdel"/>';
            return;
        }
        $row = $_db->query("SELECT * FROM `".PREFIX."_used_widgets` WHERE `id` = $widget")->fetch_object();
        $row2 = $_db->query("SELECT * FROM `".PREFIX."_widgets` WHERE `folder_name` = '$row->name'")->fetch_object();
        if($rank <= $row->rights)
        {
            echo '<label posn="0 0 0" style="TextRankingsBig" text="'.$row2->name.'"/>';
            echo '<label posn="0 -5 0" text="Position:"/>
            <entry posn="10 -5 0" sizen="6 2.5" default="'.$row->x.'" name="x"/>
            <label posn="13 -8 0" sizen="6" halign="center" text="X"/>
            <entry posn="17 -5 0" sizen="6 2.5" default="'.$row->y.'" name="y"/>
            <label posn="20 -8 0" sizen="6" halign="center" text="Y"/>
            <entry posn="24 -5 0" sizen="6 2.5" default="'.$row->z.'" name="z"/>
            <label posn="27 -8 0" sizen="6" halign="center" text="Z"/>
            <label posn="35 -5 0" halign="center" textid="choose" style="TextCardScores2" manialink="'.$url.'&amp;t='.$but.'&amp;widget='.$widget.'&amp;chpos"/>
            <label posn="0 -12 0" textid="rights"/>
            <entry posn="10 -12 0" sizen="6 2.5" default="'.$row->rights.'" name="rechte"/>
            <label posn="0 -16 0" textid="save" style="TextCardScores2" manialink="'.$url.'&amp;but='.$but.'&amp;widget='.$widget.'&amp;x=x&amp;y=y&amp;z=z&amp;rechte=rechte"/>';
            if($row2->hasConfig != 0)
                echo '<label posn="0 -19.5 0" textid="configure" style="TextCardScores2" manialink="'.$url.'&amp;but='.$but.'&amp;widget='.$widget.'&amp;configure"/>';
            echo '<label posn="0 -23 0" textid="delete" style="TextCardScores2" manialink="'.$url.'&amp;but='.$but.'&amp;widget='.$widget.'&amp;askdelwidg"/>';
        }
        echo '<label posn="0 -26.5 0" textid="tcs2back" style="TextCardScores2" manialink="'.$url.'&amp;but='.$but.'"/>';
        return;
    }
    elseif(isset($_GET['delwidg']))
    {
        $_db->query("DELETE FROM `".PREFIX."_used_widgets` WHERE `id` = ".intval($_GET['delwidg']));
        $_db->query("DELETE FROM `".PREFIX."_plugin_config` WHERE `widget_id` = ".intval($_GET['delwidg']));
        $widgets = $_db->query("SELECT `widgets` FROM `".PREFIX."_textpages` WHERE id = ".intval($_GET['but']))->fetch_object()->widgets;
        $widgets = explode(',', $widgets);
        $id = array_search($_GET['delwidg'], $widgets);
        unset($widgets[$id]);
        $widgets = implode(',', $widgets);
        $_db->query("UPDATE `".PREFIX."_textpages` SET `widgets` = '$widgets' WHERE `id` = ".intval($_GET['but']));
    }
    elseif(isset($_GET['name']))
    {
        $query = $_db->prepare("UPDATE `".PREFIX."_textpages` SET `name` = ?, `textsize` = ? WHERE `id` = ?");
        $query->bind_param('sss', stripslashes($_GET['name']), $_GET['textsize'], $but);
        $query->execute();
        $query->close();
    }
    elseif(isset($_GET['setcustomxml']))
    {
        $query = $_db->prepare("UPDATE `".PREFIX."_textpages` SET `xml` = ? WHERE `id` = ?");
        $query->bind_param('ss', stripslashes($_GET['setcustomxml']), $but);
        $query->execute();
        $query->close();
    }
    elseif(isset($_GET['settext']))
    {
        $query = $_db->prepare("UPDATE `".PREFIX."_dico` SET `text` = ?, `langcode` = ? WHERE `id` = ?");
        $query->bind_param('sss', stripslashes($_GET['settext']), $_GET['setlang'], $_GET['langid']);
        $query->execute();
        $query->close();
    }
    elseif(isset($_GET['deletelangid']))
    {
        $query = $_db->prepare("DELETE FROM `".PREFIX."_dico` WHERE `id` = ?");
        $query->bind_param('s', $_GET['deletelangid']);
        $query->execute();
        $query->close();
    }
    
    $row = $_db->query("SELECT * FROM `".PREFIX."_textpages` WHERE `id` = '$but'")->fetch_object();
    echo '<label posn="0 0 0" style="TextRankingsBig" textid="textpv"/>
    <label posn="0 -5 0" text="Name:"/>
    <entry posn="11 -5 0" sizen="20 2.5" default="'.$row->name.'" name="name"/>
    <label posn="0 -8.5 0" textid="textsize"/>
    <entry posn="11 -8.5 0" sizen="5 2.5" default="'.$row->textsize.'" name="textsize"/>
    <label posn="0 -12 0" textid="save" style="TextCardScores2" manialink="'.$url.'&amp;but='.$but.'&amp;name=name&amp;textsize=textsize"/>
    <label posn="0 -15.5 0" textid="customxml" style="TextCardScores2" manialink="'.$url.'&amp;but='.$but.'&amp;customxml"/>
    <label posn="0 -21 0" textid="tlang"/>';
    $query = $_db->query("SELECT * FROM `".PREFIX."_dico` WHERE `name` = 'text-$but'");
    $y = 0;
    while($row2 = $query->fetch_object())
    {
        $pos = -24-3.5*$y;
        echo '<label posn="0 '.$pos.' 0" textid="lang-'.$row2->langcode.'" style="TextCardScores2" manialink="'.$url.'&amp;but='.$but.'&amp;langid='.$row2->id.'"/>';
        $y++;
    }
    $pos = -25.5-3.5*$y;
    echo '<label posn="0 '.$pos.' 0" textid="add" style="TextCardScores2" manialink="'.$url.'&amp;but='.$but.'&amp;langid=addlang"/>
    <label posn="20 -21 0" text="Widgets:"/>';
    $y = 0;
    if($row->widgets != '')
    {
        $widgets = explode(',', $row->widgets);
        foreach($widgets as $uwid)
        {
            $row2 = $_db->query("SELECT `name`, `id` FROM `".PREFIX."_used_widgets` WHERE `id` = $uwid")->fetch_object();
            $row3 = $_db->query("SELECT `name` FROM `".PREFIX."_widgets` WHERE `folder_name` = '$row2->name'")->fetch_object();
            $pos = -24-3.5*$y;
            echo '<label posn="20 '.$pos.' 0" text="$fff'.$row3->name.'" style="TextCardScores2" manialink="'.$url.'&amp;but='.$but.'&amp;widget='.$row2->id.'"/>';
            $y++;
        }
    }
    $pos = -25.5-3.5*$y;
    
    echo '<label posn="20 '.$pos.' 0" textid="add" style="TextCardScores2" manialink="'.$url.'&amp;but='.$but.'&amp;addwidget"/>
    <label posn="20 -21 0" text="Widgets:"/>
    <quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.$url.'"/>
    <label posn="10 -52 0" valign="center" halign="center" text="Back" textcolor="000"/>';
    return;
}

$y = 0;
echo '<label posn="0 0 0" style="TextRankingsBig" textid="textpv"/>';
$query = $_db->query("SELECT * FROM `".PREFIX."_textpages`");
while($row = $query->fetch_object())
{
    $pos = -6-3.5*$y++;
    echo '<label posn="3.2 '.$pos.' 0" valign="center" text="'.$row->name.'" style="TextCardRaceRank" manialink="'.$url.'&amp;but='.$row->id.'"/>';
    if($row->id != 1)
        echo '<quad posn="0 '.($pos-0.3).' 0" sizen="3 3" valign="center" style="Icons64x64_1" substyle="Close" manialink="'.$url.'&amp;askdelpage='.$row->id.'"/>';
}
echo '
<quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.$url.'&amp;add"/>
<label posn="10 -52 0" valign="center" halign="center" text="Add" textcolor="000"/>
<quad posn="10 -57 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin"/>
<label posn="10 -57 0" valign="center" halign="center" text="Back" textcolor="000"/>';
