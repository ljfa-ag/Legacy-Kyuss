<?php
/**
 * Verwalten der Benutzer
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.gnu.org/licenses/gpl.html> GNU Gerneral Public License
 */
if(!$sec)
    exit;
$_dico['en'] += Array(
    'usersv' => 'Manage users',
    'change' => '$fffChange',
    'rights' => 'Rights:',
    'deluser' => '$fffDelete user'
);
$_dico['de'] += Array(
    'usersv' => 'Benutzer verwalten',
    'change' => '$fffÄndern',
    'rights' => 'Rechte:',
    'deluser' => '$fffBenutzer entfernen'
);

if(isset($_GET['delete']))
    $_db->query("DELETE FROM `".PREFIX."_users` WHERE `id` = ".intval($_GET['delete']));
elseif(isset($_GET['add']))
{
    $_db->query("INSERT INTO `".PREFIX."_users` (`password`) VALUES ('".md5(crypt(uniqid(mt_rand())))."')");
    $added = $_db->insert_id;
}
if(isset($_GET['user']) OR isset($added))
{
    $id = isset($added) ? $added : intval($_GET['user']);
    if(isset($_GET['login']))
    {
        $query = $_db->prepare("UPDATE `".PREFIX."_users` SET `name` = ? WHERE `id` = ?");
        $query->bind_param('si', $_GET['login'], $id);
        $query->execute();
        $query->close();
        if($id == $_SESSION['user_id'])
            $_SESSION['user_name'] = $_GET['login'];
    }
    elseif(isset($_GET['password']))
    {
        $query = $_db->prepare("UPDATE `".PREFIX."_users` SET `password` = ? WHERE `id` = ?");
        $query->bind_param('si', crypt($_GET['password']), $id);
        $query->execute();
        $query->close();
    }
    elseif(isset($_GET['rights']))
    {
        $query = $_db->prepare("UPDATE `".PREFIX."_users` SET `rank` = ? WHERE `id` = ?");
        $query->bind_param('ii', $_GET['rights'], $id);
        $query->execute();
        $query->close();
        if($id == $_SESSION['user_id'])
            $_SESSION['user_rank'] = $_GET['rights'];
    }
    elseif(isset($_GET['askdel']))
    {
        $row = $_db->query("SELECT * FROM `".PREFIX."_users` WHERE `id` = '".$_GET['user']."'")->fetch_object();
        $_dico['en']['deluserq'] = "Do you really want to delete the user '$row->name'?\n\$h[".KYUSS."?p=admin&action=users&delete=$_GET[user]]Yes\$h   \$h[".KYUSS."?p=admin&action=users]No";
        $_dico['de']['deluserq'] = "Möchten Sie den Benutzer '$row->name' wirklich entfernen?\n\$h[".KYUSS."?p=admin&action=users&delete=$_GET[user]]Ja\$h   \$h[".KYUSS."?p=admin&action=users]Nein";
        echo '<label posn="0 0 0" style="TextRankingsBig" textid="deluser"/>
        <label posn="0 -5 0" textid="deluserq"/>';
        return;
    }
    
    $row = $_db->query("SELECT * FROM `".PREFIX."_users` WHERE `id` = $id LIMIT 1")->fetch_object();
    echo '<label posn="0 0 0" style="TextRankingsBig" textid="usersv"/>
    <label posn="0 -5 0" text="Login:"/>
    <entry posn="11 -5 0" sizen="15 2.5" default="'.$row->name.'" name="login"/>
    <label posn="27 -5 0" style="TextCardScores2" textid="change" manialink="'.KYUSS.'?p=admin&amp;action=users&amp;user='.$row->id.'&amp;login=login"/>
    <label posn="0 -8.5 0" textid="password"/>
    <entry posn="11 -8.5 0" sizen="15 2.5" name="password"/>
    <quad posn="11 -8.5 1" sizen="15 2.5" bgcolor="444"/>
    <label posn="27 -8.5 0" style="TextCardScores2" textid="change" manialink="'.KYUSS.'?p=admin&amp;action=users&amp;user='.$row->id.'&amp;password=password"/>
    <label posn="0 -12 0" textid="rights"/>
    <entry posn="11 -12 0" sizen="6 2.5" name="rights" default="'.$row->rank.'"/>
    <label posn="27 -12 0" style="TextCardScores2" textid="change" manialink="'.KYUSS.'?p=admin&amp;action=users&amp;user='.$row->id.'&amp;rights=rights"/>
    <label posn="0 -15.5 0" style="TextCardScores2" textid="deluser" manialink="'.KYUSS.'?p=admin&amp;action=users&amp;user='.$row->id.'&amp;askdel"/>
    <label posn="0 -19 0" style="TextCardScores2" textid="tcs2back" manialink="'.KYUSS.'?p=admin&amp;action=users"/>';
    return;
}

$y = 0;
echo '<label posn="0 0 0" style="TextRankingsBig" textid="usersv"/>
<quad posn="10 -52 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin&amp;action=users&amp;add"/>
<label posn="10 -52 0" valign="center" halign="center" text="Add" textcolor="000"/>
<quad posn="10 -57 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.KYUSS.'?p=admin"/>
<label posn="10 -57 0" valign="center" halign="center" text="Back" textcolor="000"/>';
$query = $_db->query("SELECT * FROM `".PREFIX."_users`");
while($row = $query->fetch_object())
{
    $pos = -6-3.5*$y++;
    echo '<label posn="0 '.$pos.' 0" valign="center" text="'.$row->name.'" style="TextCardRaceRank" manialink="'.KYUSS.'?p=admin&amp;action=users&amp;user='.$row->id.'"/>';
}