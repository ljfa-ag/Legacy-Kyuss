<?php
/**
 * Die Klasse zur Beschreibung von Strecken
 * @package Kyuss
 * @subpackage tracks
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.gnu.org/licenses/gpl.html> GNU Gerneral Public License
 * @version 1.0
 */
class track
{
    /**
     * ID der Strecke in der Datenbank
     * @var int
     */
    public $id = 0;
    /**
     * Dateiendung des Bildes
     * @var string
     */
    public $imgformat = 'png';
    /**
     * Name der Strecke
     * @var string
     */
    public $name = '';
    /**
     * Autor der Strecke
     * @var string
     */
    public $author = '';
    /**
     * Environment der Strecke, ggf. mit Envimixing
     * @var string
     */
    public $envi = '';
    /**
     * Autorzeit der Strecke in Hundertstelsekunden
     * @var int
     */
    public $authortime = 0;
    /**
     * Typ der Strecke: Race, Platform oder Stunt
     * @var string
     */
    public $type = 'Race';
    /**
     * Tageszeit der Strecke: Sunrise, Day, Sunset oder Night
     * @var string
     */
    public $mood = 'Day';
    /**
     * Schwierigkeitsgrad der Strecke: Beginner (weiß), Easy (grün), Medium (blau), Hard (rot) oder Extreme (schwarz)
     * @var string
     */
    public $difficulty = 'Medium';
    /**
     * Beschreibung der Strecke
     * @var string
     */
    public $desc = '';
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
     * Zusätzliche Medien, die beim Download der Strecke vor dem Spielen automatisch in den Cache geladen werden, damit der Benutzer sie sofort sehen kann
     * @var array
     */
    public $media = Array();
    
    /**
     * Liefert Dateiname und Pfad des Bildes relativ zum Kyuss-Ordner
     * @return string 
     */
    public function getImage() { return "data/tracks/track$this->id.$this->imgformat"; }
}