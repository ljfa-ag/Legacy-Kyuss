<?php
/**
 * Konfigurieren der Seiten
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 */
if(!$sec)
    exit;

$_dico['en'] += Array(
    'pagesc' => 'Configure pages',
    'endc' => '$000End config'
);
$_dico['de'] += Array(
    'pagesc' => 'Seiten konfigurieren',
    'endc' => '$000Konfig. beenden'
);

if(isset($_GET['page']))
{
    $row = $_db->query("SELECT `rights` FROM `".PREFIX."_pages` WHERE `folder_name` = '".$_db->real_escape_string($_GET['page'])."'")->fetch_object();
    if($rank > $row->rights)
        return;
    $foldername = $_GET['page'];
    if(stristr($_GET['page'], '..') !== false)
        return;
    include("pages/$foldername/page.php");
    $cfg = new $foldername($foldername, true);
    try
    {
        $cfg->configure();
	}
	catch(exception $ex)
	{
		$_dico['de']['ex'] = "Die Konfigurationsseite von '$foldername' hat einen unbehandelten Ausnahmefehler verursacht:\n\$o".$ex->getMessage()."\$z\nStack-Trace:\n".$ex->getTraceAsString();
		$_dico['en']['ex'] = "The configuration page of '$foldername' has thrown an uncaught exception:\n\$o".$ex->getMessage()."\$z\nStack trace:\n".$ex->getTraceAsString();
		echo '<label textid="ex"/>';
	}
    $foldername = $_db->real_escape_string($foldername);
    $id = $_db->query("SELECT `id` FROM `".PREFIX."_pages` WHERE `folder_name` = '$foldername' LIMIT 1")->fetch_object()->id;
    echo '
<quad posn="'.$cfg->endpos.'" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=pgconf"/>
<label posn="'.$cfg->endpos.'" valign="center" halign="center" textid="endc" textcolor="000"/>';
    return;
}

$y = 0;
echo '<label posn="0 0 0" style="TextRankingsBig" textid="pagesc"/>';
$query = $_db->query("SELECT * FROM `".PREFIX."_pages` WHERE `hasConfig` = 1 AND ".$_SESSION['user_rank']." <= `rights`");
while($row = $query->fetch_object())
{
    echo '<label posn="0 '.(-5-3.5*$y).' 0" text="$fff'.$row->name.'" style="TextCardScores2" manialink="'.KYUSS.'?p=admin&amp;action=pgconf&amp;page='.$row->folder_name.'"/>';
    $y++;
}
echo '<quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin"/>
<label posn="10 -52 0" valign="center" halign="center" text="Back" textcolor="000"/>';