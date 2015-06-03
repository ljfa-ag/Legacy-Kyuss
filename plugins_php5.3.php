<?php
/**
 * Die Basisklassen für Plugins und einige globale Funktionen
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.gnu.org/licenses/gpl.html> GNU Gerneral Public License
 */

/**
 * Basisklasse für alle Plugins
 * @author ljfa-ag
 */
abstract class plugin
{
    /**
     * Name des Plugins
     * @var string
     */
    public static $name;
    /**
     * Beschreibung des Plugins
     * @var string
     */
    public static $desc = 'Keine Beschreibung vorhanden.';
    /**
     * Gibt an, ob das Plugin eine Konfigurationsseite besitzt
     * @var bool
     */
    public static $hasConfig = false;
    /**
     * Gibt an, welche gemeinsamen Listen das Plugin benutzt
     * @var array
     */
    public static $shared_lists = Array();
    
    /**
     * Name der Plugin-Klasse
     * @var string
     */
    public $foldername;
    /**
     * URL oder Maniacode der aktuellen Seite
     * @var string
     */
	public $url;
    /**
     * URL zum Plugin-Ordner
     * @var string
     */
	public $dir;
    /**
     * Gibt an, ob das Plugin gerade benutzt oder konfiguriert wird
     * @var bool
     */
    public $inConfig = false;
    
    /**
     * @var string
     */
    public $endpos = '10 -52 0';
    
    /**
     * Die eigentliche Benutzerschnittstelle des Plugins
     * @return void
     */
	abstract public function show();
    /**
     * Die Konfigurationsschnittstelle des Plugins
     * @return void|string
     */
    public function configure()
    {
        echo '<label posn="0 0 0">Keine Konfigurationsseite vorhanden.</label>';
    }
    /**
     * Speichern der Konfiguration
     * @return void
     * @deprecated
     */
    public function save() {}
    
    /**
     * Mit htmlspecialchars kodierte URL zum Plugin-Ordner
     * @return void
     */
    public function encurl() { return htmlspecialchars($this->url); }
    
    /**
     * Abrufen einer Konfigurationsvariable
     * @param string $property Name der Variable
     * @param mixed $default Rückgabewert, falls die Variable nicht existiert
     * @return mixed
     */
    abstract public function getConfig($property, $default = NULL);
    /**
     * Abrufen aller Konfigurationsvariablen als Array
     * @return array Im Fehlerfall wird false zurückgegeben
     */
    abstract public function getConfigArray();
    /**
     * Setzen einer Konfigurationsvariable
     * @param string $property Name der Variable
     * @param mixed $value Wert der Variable
     * @return bool Bei Erfolg true, ansonsten false
     */
    abstract public function setConfig($property, $value);
    /**
     * Löschen einer Konfigurationsvariable
     * @param string $property Name der Variable
     * @return bool Bei Erfolg true, ansonsten false
     */
    abstract public function unsetConfig($property);
    
    /**
     * Abrufen eines Elements einer gemeinsamen Liste
     * @param string $list Name der Liste
     * @param string $property Name des Elemnts
     * @param mixed $default Rückgabewert, falls die Liste oder das Element nicht existiert
     * @return mixed
     */
    public static function getSharedListItem($list, $property, $default = NULL)
    {
		if(!in_array($list, static::$shared_lists) && isset(static::$name))
            throw new InvalidArgumentException("Plugin was not declared as that it uses the list '$list'");
			
        global $_db;
        
        $query = $_db->prepare('SELECT `value` FROM `'.PREFIX.'_shared_lists` WHERE `list` = ? AND `property` = ?');
		if(!$query)
			return false;
        $query->bind_param('ss', $list, $property);
        $query->execute();
        $query->bind_result($value);
        $query->store_result();
        if($query->num_rows == 0)
            return $default;
        $query->fetch();
        $query->close();
        return unserialize($value);
    }
    
    /**
     * Abrufen aller Elemente einer gemeinsamen Liste als Array
     * @param string $list Name der Liste
     * @return array Im Fehlerfall wird false zurückgegeben
     */
    public static function getSharedListArray($list)
    {
        if(!in_array($list, static::$shared_lists) && isset(static::$name))
            throw new InvalidArgumentException("Plugin was not declared as that it uses the list '$list'");
        
        global $_db;
        
        $query = $_db->prepare('SELECT `property`, `value` FROM `'.PREFIX.'_shared_lists` WHERE `list` = ?');
		if(!$query)
			return false;
        $query->bind_param('s', $list);
        $query->execute();
        $query->bind_result($property, $value);
        $ret = Array();
        while($query->fetch())
            $ret[$property] = unserialize($value);
        $query->close();
        return $ret;
    }
    
    /**
     * Setzen eines Elements einer gemeinsamen Liste
     * @param string $list Name der Liste
     * @param string $property Name des Elements
     * @param mixed $value Wert des Elements
     * @return bool Bei Erfolg true, ansonsten false
     */
    public static function setSharedListItem($list, $property, $value)
    {
        if(!in_array($list, static::$shared_lists) && isset(static::$name))
            throw new InvalidArgumentException("Plugin was not declared as that it uses the list '$list'");
        
        global $_db;
        
        $value = serialize($value);
        
        if(is_null(self::getSharedListItem($list, $property)))
        {
            $query = $_db->prepare('INSERT INTO `'.PREFIX.'_shared_lists` (`list`, `property`, `value`) VALUES (?, ?, ?)');
			if(!$query)
				return false;
            $query->bind_param('sss', $list, $property, $value);
            $query->execute();
            $query->close();
        }
        else
        {
            $query = $_db->prepare('UPDATE `'.PREFIX.'_shared_lists` SET `value` = ? WHERE `list` = ? AND `property` = ?');
			if(!$query)
				return false;
            $query->bind_param('sss', $value, $list, $property);
            $query->execute();
            $query->close();
        }
		return true;
    }
    
    /**
     * Löschen eines Elements aus einer gemeinsamen Liste
     * @param string $list Name der Liste
     * @param string $property Name des Elements
     * @return bool Bei Erfolg true, ansonsten false
     */
    public static function deleteSharedListItem($list, $property)
    {
        if(!in_array($list, static::$shared_lists) && isset(static::$name))
            throw new InvalidArgumentException("Plugin was not declared as that it uses the list '$list'");
        
        global $_db;
        
        $query = $_db->prepare('DELETE FROM `'.PREFIX.'_shared_lists` WHERE `list` = ? AND `property` = ?');
		if(!$query)
			return false;
        $query->bind_param('ss', $list, $property);
        $query->execute();
        $query->close();
		return true;
    }
}

/**
 * Basisklasse für alle Widgets
 * @author ljfa-ag
 */
abstract class widget extends plugin
{
    /**
     * ID des Widgets aus der Tabelle kyuss_used_widgets
     * @var int
     */
    public $widgetid;
    
    /**
     * Initialisiert die Widget-Klasse zur Benutzung
     * @param string $foldername Name der Klasse
     * @param bool $inConfig Gibt an, ob die Benutzer- oder die Konfigurationsschnittstelle des Widgets benutzt wird
     */
	public function __construct($foldername, $inConfig = false)
	{
		$this->foldername = $foldername;
		$this->dir = DIR.'widgets/'.$foldername.'/';
		$this->inConfig = $inConfig;
		if($inConfig)
		{
            if($_GET['action'] == 'textpages')
                $this->url = KYUSS.'?p=admin&action=textpages&but='.$_GET['but'].'&widget='.$_GET['widget'].'&configure';
            elseif($_GET['action'] == 'globalw')
                $this->url = KYUSS.'?p=admin&action=globalw&widget='.$_GET['widget'].'&configure';
		}
		else
		{
			if(isset($_GET['p']))
                $this->url = KYUSS.'?p='.$_GET['p'];
            elseif(isset($_GET['t']))
                $this->url = KYUSS.'?t='.$_GET['t'];
            else
                $this->url = KYUSS.'?t=1';
		}
	}
    
    /**
     * @ignore
     */
    public function getConfig($property, $default = NULL)
    {
        global $_db;
        
        $query = $_db->prepare('SELECT `value` FROM `'.PREFIX.'_plugin_config` WHERE `widget_id` = ? AND `property` = ?');
		if(!$query)
			return false;
        $query->bind_param('is', $this->widgetid, $property);
        $query->execute();
        $query->bind_result($value);
        $query->store_result();
        if($query->num_rows == 0)
            return $default;
        $query->fetch();
        $query->close();
        return unserialize($value);
    }
    
    /**
     * @ignore
     */
    public function getConfigArray()
    {
        global $_db;
        
        $query = $_db->prepare('SELECT `property`, `value` FROM `'.PREFIX.'_plugin_config` WHERE `widget_id` = ?');
		if(!$query)
			return false;
        $query->bind_param('i', $this->widgetid);
        $query->execute();
        $query->bind_result($property, $value);
        $ret = Array();
        while($query->fetch())
            $ret[$property] = unserialize($value);
        $query->close();
        return $ret;
    }
    
    /**
     * @ignore
     */
    public function setConfig($property, $value)
    {
        global $_db;
        
        $value = serialize($value);
        
        if(is_null($this->getConfig($property)))
        {
            $query = $_db->prepare('INSERT INTO `'.PREFIX.'_plugin_config` (`widget_id`, `property`, `value`) VALUES (?, ?, ?)');
			if(!$query)
				return false;
            $query->bind_param('iss', $this->widgetid, $property, $value);
            $query->execute();
            $query->close();
        }
        else
        {
            $query = $_db->prepare('UPDATE `'.PREFIX.'_plugin_config` SET `value` = ? WHERE `widget_id` = ? AND `property` = ?');
			if(!$query)
				return false;
            $query->bind_param('sis', $value, $this->widgetid, $property);
            $query->execute();
            $query->close();
        }
		return true;
    }
    
    /**
     * @ignore
     */
    public function unsetConfig($property)
    {
        global $_db;
        
        $query = $_db->prepare('DELETE FROM `'.PREFIX.'_plugin_config` WHERE `widget_id` = ? AND `property` = ?');
		if(!$query)
			return false;
        $query->bind_param('is', $this->widgetid, $property);
        $query->execute();
        $query->close();
		return true;
    }
    
    /**
     * Einbinden von Widgets in die Textseiten oder global
     * @param string $widgets Komma-separierte Liste von IDs aus der Tabelle kyuss_used_widgets
     * @return void
     */
    public static function includeWidgets($widgets)
    {
        global $_db, $_dico;
        
        if($widgets != '')
        {
            $widgets = split(',', $widgets);
            foreach($widgets as $widgetid)
            {
				$row = $_db->query("SELECT * FROM `".PREFIX."_used_widgets` WHERE `id` = $widgetid")->fetch_object();
				include_once("widgets/$row->name/widget.php");
				$widget = new $row->name($row->name);
				$widget->widgetid = $widgetid;
				echo "<frame posn=\"$row->x $row->y $row->z\">";
				try
                {
					$widget->show();
                }
				catch(exception $ex)
				{
					$_dico['de']['ex'] = "Das Widget '$row->name' hat einen unbehandelten Ausnahmefehler verursacht:\n\$o".$ex->getMessage()."\$z\nStack-Trace:\n".$ex->getTraceAsString();
					$_dico['en']['ex'] = "The widget '$row->name' has thrown an uncaught exception:\n\$o".$ex->getMessage()."\$z\nStack trace:\n".$ex->getTraceAsString();
					echo '<label textid="ex" textsize="2"/>';
				}
                echo '</frame>';
            }
        }
    }
}

/**
 * Basisklasse für alle Seiten
 * @author ljfa-ag
 */
abstract class page extends plugin
{
    /**
     * Initialisiert die Seiten-Klasse zur Benutzung
     * @param string $foldername Name der Klasse
     * @param bool $inConfig Gibt an, ob die Benutzer- oder die Konfigurationsschnittstelle der Seite benutzt wird
     */ 
    public function __construct($foldername, $inConfig = false)
	{
		$this->foldername = $foldername;
		$this->dir = DIR.'pages/'.$foldername.'/';
		$this->inConfig = $inConfig;
		if($inConfig)
		{
            $this->url = KYUSS.'?p=admin&action=pgconf&page='.$foldername;
		}
		else
			$this->url = KYUSS.'?p='.$_GET['p'];
	}
    
    /**
     * @ignore
     */
    public function getConfig($property, $default = NULL)
    {
        global $_db;
        
        $query = $_db->prepare('SELECT `value` FROM `'.PREFIX.'_plugin_config` WHERE `page` = ? AND `property` = ?');
		if(!$query)
			return false;
        $query->bind_param('ss', $this->foldername, $property);
        $query->execute();
        $query->bind_result($value);
        $query->store_result();
        if($query->num_rows == 0)
            return $default;
        $query->fetch();
        $query->close();
        return unserialize($value);
    }
    
    /**
     * @ignore
     */
    public function getConfigArray()
    {
        global $_db;
        
        $query = $_db->prepare('SELECT `property`, `value` FROM `'.PREFIX.'_plugin_config` WHERE `page` = ?');
		if(!$query)
			return false;
        $query->bind_param('s', $this->foldername);
        $query->execute();
        $query->bind_result($property, $value);
        $ret = Array();
        while($query->fetch())
            $ret[$property] = unserialize($value);
        $query->close();
        return $ret;
    }
    
    /**
     * @ignore
     */
    public function setConfig($property, $value)
    {
        global $_db;
        
        $value = serialize($value);
        
        if(is_null($this->getConfig($property)))
        {
            $query = $_db->prepare('INSERT INTO `'.PREFIX.'_plugin_config` (`page`, `property`, `value`) VALUES (?, ?, ?)');
			if(!$query)
				return false;
            $query->bind_param('sss', $this->foldername, $property, $value);
            $query->execute();
            $query->close();
        }
        else
        {
            $query = $_db->prepare('UPDATE `'.PREFIX.'_plugin_config` SET `value` = ? WHERE `page` = ? AND `property` = ?');
			if(!$query)
				return false;
            $query->bind_param('sss', $value, $this->foldername, $property);
            $query->execute();
            $query->close();
        }
		return true;
    }
    
    /**
     * @ignore
     */
    public function unsetConfig($property)
    {
        global $_db;
        
        $query = $_db->prepare('DELETE FROM `'.PREFIX.'_plugin_config` WHERE `page` = ? AND `property` = ?');
		if(!$query)
			return false;
        $query->bind_param('ss', $this->foldername, $property);
        $query->execute();
        $query->close();
		return true;
    }
}

/**
 * Ruft eine allgemeine Konfigurationsvariable aus der Tabelle kyuss_config ab
 * @param string $property Name der Variable
 * @return string Im Fehlerfall wird false zurückgegeben
 */
function getGeneralConfig($property)
{
    global $_db;
    
    if($row = $_db->query("SELECT * FROM `".PREFIX."_config` WHERE `property` = \"$property\"")->fetch_object())
        return $row->value;
    else
        return false;
}

/**
 * Setzt eine allgemeine Konfigurationsvariable aus der Tabelle kyuss_config
 * @param string $property Name der Variable
 * @param string $value Wert der Variable
 * @return bool Bei Erfolg true, ansonsten false
 */
function setGeneralConfig($property, $value)
{
    global $_db;
    
	$query = $_db->prepare("UPDATE `".PREFIX."_config` SET `value` = ? WHERE `property` = ?");
	if(!$query)
		return false;
    $query->bind_param('ss', $value, $property);
    $query->execute();
    $query->close();
	return true;
}

/**
 * Fügt Wörterbucheinträge hinzu
 * @param string $lang Sprachcode
 * @param array $codes Assoziatives Array der Wörterbucheinträge
 * @return void
 */
function addDico($lang, $codes)
{
    global $_dico;
    $_dico[$lang] += $codes;
}

/**
 * Ermöglicht den Zugriff auf statische Mitglieder variabler Klassen
 * @param string $class Name der Klasse
 * @param string $property Name der Eigenschaft
 * @return mixed
 */
function getStaticProperty($class, $property)
{
    return $class::$$property;
}

/**
 * Ermöglicht das Ändern statischer Mitglieder variabler Klassen
 * @param string $class Name der Klasse
 * @param string $property Name der Eigenschaft
 * @param mixed $value Wert der Eigenschaft
 * @return void
 */
function setStaticProperty($class, $property, $value)
{
    $class::$$property = $value;
}