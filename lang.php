<dico>
<?php
/**
 * Erstellen des dico-Tags
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 */

$langs = Array('en', 'cz', 'de', 'es', 'fr', 'hu', 'it', 'jp', 'kr', 'nl', 'pl', 'pt', 'ru', 'sk', 'zh');
foreach($langs as $lng)
{
    $result = $_db->query("SELECT `name`, `text` FROM `".PREFIX."_dico` WHERE `langcode` = '$lng' AND (`name` NOT LIKE 'text-%' OR `name` = 'text-$tpage')");
    if($result->num_rows == 0)
        continue;
    while($row = $result->fetch_object())
        $_dico[$lng][$row->name] = $row->text;
}

foreach($_dico as $lng => $refs)
{
    if($refs == Array())
        continue;
    echo "<language id=\"$lng\">";
    foreach($refs as $name => $text)
    {
        echo "<$name>".htmlspecialchars($text)."</$name>";
    }
    echo '</language>';
}
?>
</dico>