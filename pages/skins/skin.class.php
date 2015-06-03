<?php
/**
 * Die Klasse zur Beschreibung von Skins
 * @package Kyuss
 * @subpackage skins
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.gnu.org/licenses/gpl.html> GNU Gerneral Public License
 * @version 1.0
 */
class skin
{
    /**
     * ID des Skins in der Datenbank
     * @var int
     */
    public $id = 0;
    /**
     * Dateiendung des Bildes
     * @var string
     */
    public $imgformat = 'png';
    /**
     * Name des Skins
     * @var string
     */
    public $name = '';
    /**
     * Autor der Lackierung
     * @var string
     */
    public $skinauthor = '';
    /**
     * Autor des 3D-Modells
     * @var string
     */
    public $modelauthor = '';
    /**
     * Speicherort des Skins
     * @var string
     */
    public $dest = 'Skins\Vehicles\CarCommon\\';
    /**
     * Falls es sich um einen 2D-Skin handelt, wird hier das Environment angegeben
     * @var string
     */
    public $envi = NULL;
    /**
     * Beschreibung des Skins
     * @var string
     */
    public $desc = '';
    /**
     * Benutzerdefinierter Maniacode
     * @var string
     */
    public $maniacode = NULL;
    /**
     * Anzahl der Downloads
     * @var int
     */
    public $downloads = 0;
    /**
     * Kommentare der Benutzer
     * @var array
     */
    public $comments = Array();
    
    /**
     * Liefert Dateiname und Pfad des Bildes relativ zum Kyuss-Ordner
     * @return string 
     */
    public function getImage() { return "data/skins/skin$this->id.$this->imgformat"; }
}