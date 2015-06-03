<?php
/**
 * Der Maniacode zum Downloaden eines Skins
 * @package Kyuss
 * @subpackage skins
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.gnu.org/licenses/gpl.html> GNU Gerneral Public License
 * @version 1.0
 */
require_once('skin.class.php'); 
$skin = plugin::getSharedListItem('skins', $_GET['id']);
echo '<?xml version="1.0" encoding="utf-8" ?>
<maniacode noconfirmation="1">
    <install_skin>
        <name>'.htmlspecialchars($skin->name).'</name>
        <file>'.str_replace('\\\\', '\\', $skin->dest).htmlspecialchars($skin->name).'.zip</file>
        <url>'.DIR.'data/skins/skin'.$skin->id.'.zip</url>
    </install_skin>
    <show_message>
        <message>Danke für deinen Download!
Thank you for downloading!</message>
    </show_message>
</maniacode>';
$skin->downloads++;
plugin::setSharedListItem('skins', $skin->id, $skin);
?>