<?php
/**
 * Funktionen zum Löschen eines Ordners mitsamt seines Inhalts
 * @package Kyuss
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.gnu.org/licenses/gpl.html> GNU Gerneral Public License
 */

/**
 * Leert einen Ordner rekursiv
 * @param RecursiveDirectoryIterator $folder DirectoryIterator für den zu leerenden Ordner
 */
function clear_folder(RecursiveDirectoryIterator $folder)
{
    for($folder->rewind(); $folder->valid(); $folder->next())
    {
        if($folder->isDir() && !$folder->isDot())
        {
            if($folder->hasChildren())
                clear_folder($folder->getChildren());
            rmdir($folder->getPathname());
        }
        elseif($folder->isFile())
            unlink($folder->getPathname());
    }
}

/**
 * Löscht einen Ordner mitsamt seines Inhalts
 * @param string $path Pfad des Ordners
 */
function delete_folder($path)
{
    clear_folder(new RecursiveDirectoryIterator($path));
    rmdir($path);
}