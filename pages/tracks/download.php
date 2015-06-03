<?php
/**
 * Der Maniacode zum Downloaden einer Strecke
 * @package Kyuss
 * @subpackage tracks
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 * @version 1.0
 */
require_once('track.class.php'); 
$track = plugin::getSharedListItem('tracks', $_GET['id']);
echo '<?xml version="1.0" encoding="utf-8" ?>
<maniacode noconfirmation="1">';
foreach($track->media as $media)
{
    $media = explode('&#0;', $media, 2);
    echo '<get_skin>
        <name>Additional media: '.basename(str_replace('\\', '/', $media[1])).'</name>
        <url>'.$media[0].'</url>
        <file>'.$media[1].'</file>
    </get_skin>';
}
echo '<install_track>
        <name>'.htmlspecialchars($track->name).'</name>
        <url>'.DIR.'data/tracks/track'.$track->id.'.Challenge.Gbx</url>
    </install_track>
    <show_message>
        <message>Danke für deinen Download!
Thank you for downloading!</message>
    </show_message>
</maniacode>';
$track->downloads++;
plugin::setSharedListItem('tracks', $_GET['id'], $track);
?>