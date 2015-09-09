<?php
/**
 * Eine Seite, von der Besucher Fahrzeuge herunterladen können.
 * @package Kyuss
 * @subpackage skins
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.apache.org/licenses/LICENSE-2.0> Apache License 2.0
 * @version 1.0.1
 */
class skins extends page
{
    public static $name = 'Skins';
    public static $desc = 'Eine Seite, von der Besucher Fahrzeuge herunterladen können.';
    public static $hasConfig = true;
    public static $shared_lists = Array('skins');
    
    public function show()
    {
        global $_dico;
        $_dico['de'] += array(
            'noskins' => '$iKeine Skins gefunden.',
            'addc' => 'Kommentar hinzufügen',
            'cinfo' => "Nicht mehr als zwei Kommentare hintereinander sind erlaubt.\nMaximal 100 Zeichen."
        );
        $_dico['en'] += array(
            'noskins' => '$iNo skins found.',
            'addc' => 'Add comment',
            'cinfo' => "Not more than two comments in a row.\n100 characters max."
        );
        
        if(isset($_GET['addc']))
        {
            $skin = self::getSharedListItem('skins', $_GET['info']);
            $deny1 = explode("&#0;", $skin->comments[0], 2);
            $deny2 = explode("&#0;", $skin->comments[1], 2);
            if($deny1[0] == $deny2[0] && $deny1[0] == $_GET['playerlogin'])
                return;
            if($_GET['addc'] != '')
            {
                $comment = substr($_GET['addc'], 0, 100);
                $comment = htmlspecialchars($comment);
                $comment = stripslashes($comment);
                $comment = "$_GET[playerlogin]&#0;$_GET[nickname]\$z\n$comment";
                array_unshift($skin->comments, $comment);
                self::setSharedListItem('skins', intval($_GET['info']), $skin);
            }
            else
            {
                echo '<label posn="18 0 0" halign="center" style="TextRankingsBig" textid="addc"/>
                <frame posn="0 -4 0">
                    <quad posn="0 0 0" sizen="36 16.67" style="Bgs1" substyle="BgList"/>
                    <entry posn="1 -1 0" sizen="34 14.67" autonewline="1" name="comment"/>
                </frame>
                <frame posn="0 -20.67 0">
                    <quad posn="0 0 0" sizen="36 15" style="Bgs1" substyle="BgList"/>
                    <label posn="1 -1 0" sizen="34" autonewline="1" textid="cinfo"/>
                    <label posn="18 -10.25 1" halign="center" style="CardButtonMedium" manialink="'.$this->url.'&amp;info='.$skin->id.'&amp;addc=comment" addplayerid="1" textid="addc"/>
                </frame>';
                return;
            }
        }
        if(isset($_GET['info']))
        {
            $skin = self::getSharedListItem('skins', $_GET['info']);
            $_dico['de']['info'] = "Modellautor: $skin->modelauthor
Skinautor: $skin->skinauthor".(is_null($skin->envi) ? '' : "\nUmgebung: $skin->envi");
            $_dico['en']['info'] = "Model author: $skin->modelauthor
Skin author: $skin->skinauthor".(is_null($skin->envi) ? '' : "\nEnvironment: $skin->envi");
            if($skin->desc == '')
            {
                $_dico['en']['desc'] = '$iNo description.';
                $_dico['de']['desc'] = '$iKeine Beschreibung vorhanden.';
            }
            else
                $_dico['en']['desc'] = $skin->desc;
            $page = intval($_GET['cpage']);
            if(count($skin->comments) == 0)
            {
                $comments = Array('$iKein Kommentar vorhanden.');
                $deny = false;
            }
            else
            {
                $deny1 = explode("&#0;", $skin->comments[0], 2);
                $deny2 = explode("&#0;", $skin->comments[1], 2);
                $deny = $deny1[0] == $deny2[0] && $deny1[0] == $_GET['playerlogin'];
                $comments = array_slice($skin->comments, 3*$page);
                
                function exploder($x)
                {
                    $ret = explode("&#0;", $x, 2);
                    return $ret[1];
                }
                $comments = array_map('exploder', $comments);
            }
            $code = is_null($skin->maniacode) ? ($this->getConfig('code', '').'?id='.$skin->id) : $skin->maniacode;
            echo '<quad posn="-1 1 0" sizen="65 33.5" style="Bgs1" substyle="BgList"/>
            <label posn="32 0 1" halign="center" textsize="4" text="'.$skin->name.'"/>
            <quad posn="0 -4 1" sizen="22.5 22.5" image="./'.$skin->getImage().'"/>
            <label posn="23.5 -4 1" textsize="4" textid="info"/>
            <quad posn="46 -22.5 1" sizen="5 5" style="Icons128x128_1" substyle="'.$skin->difficulty.'"/>
            <label posn="32 -27.5 1" halign="center" style="CardButtonMedium" manialink="'.$code.'" text="Download"/>
            <quad posn="-1 -33 0" sizen="65 22" style="Bgs1" substyle="BgList"/>
            <label posn="0 -34 0" sizen="63" autonewline="1" textid="desc"/>
            <frame posn="64.5 1 0">
                <quad posn="0 0 0" sizen="36 16.67" style="Bgs1" substyle="BgList"/>
                <label posn="1 -1 1" sizen="36" autonewline="1">'.$comments[0].'</label>
            </frame>
            <frame posn="64.5 -15.67 0">
                <quad posn="0 0 0" sizen="36 16.67" style="Bgs1" substyle="BgList"/>
                <label posn="1 -1 1" sizen="36" autonewline="1">'.$comments[1].'</label>
            </frame>
            <frame posn="64.5 -32.33 0">
                <quad posn="0 0 0" sizen="36 16.67" style="Bgs1" substyle="BgList"/>
                <label posn="1 -1 1" sizen="36" autonewline="1">'.$comments[2].'</label>
            </frame>
            <frame posn="64.5 -49 0">
                <quad posn="0 0 1" sizen="36 6" style="Bgs1" substyle="BgList"/>';
            if(!$deny)
                echo '<label posn="18 -1 1" halign="center" style="CardButtonMedium" manialink="'.$this->url.'&amp;info='.$skin->id.'&amp;addc" textid="addc" addplayerid="1"/>';
            if($page == 0)
                echo '<quad style="Icons64x64_1" substyle="StarGold" posn="1.2 -3 2" sizen="3.5 3.5" valign="center"/>';
            else
                echo '<quad style="Icons64x64_1" substyle="ArrowPrev" posn="1.2 -3 2" sizen="3.5 3.5" valign="center" manialink="'.$this->url.'&amp;info='.$skin->id.'&amp;cpage='.($page-1).'"/>';
            if($page >= count($skin->comments)/3-1)
                echo '<quad style="Icons64x64_1" substyle="StarGold" posn="34.8 -3 2" sizen="3.5 3.5" valign="center" halign="right"/>';
            else
                echo '<quad style="Icons64x64_1" substyle="ArrowNext" posn="34.8 -3 2" sizen="3.5 3.5" valign="center" halign="right" manialink="'.$this->url.'&amp;info='.$skin->id.'&amp;cpage='.($page+1).'"/>';
            echo '</frame>';
            return;
        }
        
        echo '<label posn="0 0 0" style="TextRankingsBig" text="Skins"/>
        <label posn="0 -52.5 0" textid="pages"/>';
        $skins = self::getSharedListArray('skins');
        ksort($skins);
        $skins = array_reverse($skins, false);
        $seite = intval($_GET['page']);
        $ps = '';
        for($i = 0; $i < count($skins)/8; $i++)
        {
            if($i == $seite)
                $ps .= ' $779'.($i+1).'$g';
            else
                $ps .= " \$h[$this->url&page=$i]".($i+1).'$h';
        }
        $_dico['de']['pages'] = 'Seiten'.$ps;
        $_dico['en']['pages'] = 'Pages'.$ps;
        if(!$skins)
            echo '<label posn="0 -5 0" textid="notracks"/>';
        $skins = array_slice($skins, 8*$seite);
        for($i = 0; $i < 8; $i++)
        {
            $pos = ($i < 4) ? '0 '.(-4-12*$i).' 0' : '50 '.(44-12*$i).' 0';
            $skin = $skins[$i];
            if(!$skin)
                break;
            echo '<frame posn="'.$pos.'">
            <quad posn="0 0 0" sizen="49 11" style="BgsPlayerCard" substyle="BgCard" manialink="'.$this->url.'&amp;info='.$skin->id.'" addplayerid="1"/>
            <quad posn="1 -1 1" sizen="9 9" image="./'.$skin->getImage().'"/>
            <label posn="11 -1 0" textsize="4" text="'.htmlspecialchars($skin->name).'"/>
            <label posn="11 -4.5 0">'.$skin->skinauthor."\nDownloads: ".$skin->downloads.'</label>
            </frame>';
        }
    }
    
    public function configure()
    {
        global $_dico;
        $_dico['de'] += Array(
            'skinsv' => 'Skins verwalten',
            'maniacode' => "Maniacode zum Download:\nDie URL für den Code lautet: \"".DIR."index.php?maniacode=pages/skins/download\"",
            'adds' => 'Skin hochladen',
            'skinauthor' => 'Skinautor:',
            'modelauthor' => 'Modellautor:',
            'save' => '$fffSpeichern',
            'delskin' => '$fffSkin entfernen',
            'dest' => 'Speicherort:',
            'chimg' => '$fffBild ändern',
            'upload' => '$fffHochladen',
            'imginfo' => 'Unterstützte Dateitypen: png, jpg, dds, gif, bik',
            'commv' => '$fffKommentare verwalten',
            'edit' => '$fffBearbeiten',
            'delete' => '$fffEntfernen'
        );
        $_dico['en'] += Array(
            'skinsv' => 'Manage skins',
            'maniacode' => "Maniacode for downloading:\nThe URL for the code is: \"".DIR."index.php?maniacode=pages/skins/download\"",
            'adds' => 'Upload skin',
            'skinauthor' => 'Skin author:',
            'modelauthor' => 'Model author:',
            'save' => '$fffSave',
            'delskin' => '$fffDelete skin',
            'dest' => 'Destination:',
            'chimg' => '$fffChange image',
            'upload' => '$fffUpload',
            'imginfo' => 'Supported filetypes: png, jpg, dds, gif, bik',
            'commv' => '$fffManage comments',
            'edit' => '$fffEdit',
            'delete' => '$fffDelete'
        );
        
        if(isset($_GET['delete']))
        {
            $dels = self::getSharedListItem('skins', $_GET['delete']);
            unlink("data/skins/skin$dels->id.zip");
            unlink($dels->getImage());
            self::deleteSharedListItem('skins', $_GET['delete']);
        }
        elseif(isset($_GET['adds']))
        {
            if($_GET['adds'] != '')
            {
                $skin = new skin;
                $skin->id = max(array_keys(self::getSharedListArray('skins'))) + 1;
                $skin->imgformat = 'png';
                $skin->name = $_GET['nname'];
                $skin->skinauthor = $_GET['nskinauthor'];
                $skin->modelauthor = $_GET['nmodelauthor'];
                switch(strtolower($_GET['nenvi']))
                {
                case 'desert':
                    $folder = 'American';
                    $skin->envi = 'Desert';
                    break;
                case 'snow':
                    $folder = 'SnowCar';
                    $skin->envi = 'Snow';
                    break;
                case 'rally':
                    $folder = 'Rally';
                    $skin->envi = 'Rally';
                    break;
                case 'bay':
                    $folder = 'BayCar';
                    $skin->envi = 'Bay';
                    break;
                case 'island':
                    $folder = 'SportCar';
                    $skin->envi = 'Island';
                    break;
                case 'coast':
                    $folder = 'CoastCar';
                    $skin->envi = 'Coast';
                    break;
                case 'stadium':
                    $folder = 'StadiumCar';
                    $skin->envi = 'Stadium';
                    break;
                default:
                    $folder = 'CarCommon';
                    $skin->envi = NULL;
                    break;
                }
                $skin->dest = 'Skins\Vehicles\\'.$folder.'\\';
                copy('php://input', DIR.'data/skins/skin'.$skin->id.'.zip');
                copy('data/images/skin_nop.png', $skin->getImage());
                self::setSharedListItem('skins', $skin->id, $skin);
            }
            else
            {
                echo '<label posn="0 0 0" style="TextRankingsBig" textid="adds"/>
                <fileentry posn="0 -5 0" sizen="30 2.5" name="skin" default="Durchsuchen..." folder="Skins"/>
                <label posn="0 -8.5 0" text="Name:"/>
                <entry posn="14 -8.5 0" sizen="25 2.5" default="'.$skin->name.'" name="name"/>
                <label posn="0 -12 0" textid="skinauthor"/>
                <entry posn="14 -12 0" sizen="15 2.5" default="'.$skin->skinauthor.'" name="skinauthor"/>
                <label posn="0 -15.5 0" textid="modelauthor"/>
                <entry posn="14 -15.5 0" sizen="15 2.5" default="'.$skin->modelauthor.'" name="modelauthor"/>
                <label posn="0 -19 0" text="Envi:"/>
                <entry posn="14 -19 0" sizen="15 2.5" default="'.$envi.'" name="envi"/>
                <label posn="0 -22.5 0" style="TextCardScores2" textid="upload" manialink="POST('.$this->encurl().'&amp;adds=skin&amp;nname=name&amp;nskinauthor=skinauthor&amp;nmodelauthor=modelauthor&amp;nenvi=envi,skin)"/>';
                return;
            }
        }
        if(isset($_GET['skin']) OR isset($skin))
        {
            if(!isset($skin))
                $skin = self::getSharedListItem('skins', $_GET['skin']);
            
            if(isset($_GET['chimage']))
            {
                echo '<label posn="0 0 0" style="TextRankingsBig" textid="chimg"/>
                <fileentry posn="0 -5 0" sizen="30 2.5" name="image" default="Durchsuchen..." folder=""/>
                <label posn="0 -8.5 0" style="TextCardScores2" textid="upload" manialink="POST('.$this->encurl().'&amp;skin='.$skin->id.'&amp;upimage=image,image)"/>
                <label posn="0 -13 0" textid="imginfo"/>';
                return;
            }
            elseif(isset($_GET['upimage']))
            {
                $ext = strtolower(substr($_GET['upimage'], -3, 3));
                switch($ext)
                {
                case 'peg':
                    $ext = 'jpg';
                case 'png':
                case 'jpg':
                case 'dds':
                case 'bik':
                    unlink($skin->getImage());
                    $skin->imgformat = $ext;
                    copy('php://input', $skin->getImage());
                    self::setSharedListItem('skins', $skin->id, $skin);
                    break;
                case 'gif':
                    unlink($skin->getImage());
                    $skin->imgformat = 'png';
                    $image = imagecreatefromgif('php://input');
                    imagepng($image, $skin->getImage());
                    imagedestroy($image);
                    self::setSharedListItem('skins', $skin->id, $skin);
                    break;
                default:
                    echo '<label posn="0 0 0" style="TextRankingsBig" textid="imgfns"/>';
                    return;
                }
            }
            elseif(isset($_GET['comm']))
            {
                $skin->desc = $_GET['comm'];
                self::setSharedListItem('skins', $skin->id, $skin);            
            }
            elseif(isset($_GET['askdel']))
            {
                $_dico['en']['delsq'] = "Do you really want to delete the skin '$skin->name\$z'?\n\$h[$this->url&delete=$_GET[skin]]Yes\$h   \$h[$this->url&skin=$_GET[skin]]No";
                $_dico['de']['delsq'] = "Möchten Sie den Skin '$skin->name\$z' wirklich entfernen?\n\$h[$this->url&delete=$_GET[skin]]Ja\$h   \$h[$this->url&skin=$_GET[skin]]Nein";
                echo '<label posn="0 0 0" style="TextRankingsBig" textid="delskin"/>
                <label posn="0 -5 0" textid="delsq"/>';
                return;
            }
            elseif(isset($_GET['commv']))
            {
                echo '<label posn="0 0 0" style="TextRankingsBig" textid="commv"/>';
                if(isset($_GET['delc']))
                {
                    unset($skin->comments[intval($_GET['delc'])]);
                    self::setSharedListItem('skins', $skin->id, $skin);
                }
                elseif(isset($_GET['editc']))
                {
                    if(!isset($_GET['text']))
                    {
                        $comment = explode("&#0;", $skin->comments[intval($_GET['editc'])], 2);
                        echo '<label posn="0 -5 0" text="Login:"/>
                        <entry posn="7 -5 1" sizen="36 3" default="'.$comment[0].'" name="login"/>
                        <label posn="0 -8.5 1" text="Text:"/>
                        <entry posn="7 -8.5 1" sizen="36 16" default="'.htmlspecialchars(utf8_decode($comment[1])).'" name="text" autonewline="1"/>
                        <label posn="0 -25.5 0" style="TextCardScores2" textid="save" manialink="'.$this->encurl().'&amp;skin='.$skin->id.'&amp;commv&amp;editc='.$_GET['editc'].'&amp;login=login&amp;text=text"/>';
                        return;
                    }
                    else
                        $skin->comments[intval($_GET['editc'])] = $_GET['login'].'&#0;'.stripslashes($_GET['text']);
                    self::setSharedListItem('skins', $skin->id, $skin);
                }
                $page = intval($_GET['cpage']);
                $comments = $skin->comments;
                function _walker_sk(&$x, $k)
                {
                    $ret = explode("&#0;", $x, 2);
                    $x = array($ret[1], $k);
                }
                array_walk($comments, '_walker_sk');
                $comments = array_slice($comments, 3*$page);
                echo '<frame posn="25 -5 0">
                    <quad posn="0 0 0" sizen="36 16.67" style="Bgs1" substyle="BgList"/>';
                if(isset($comments[0]))
                    echo '<label posn="37 -1 0" style="TextCardScores2" textid="edit" manialink="'.htmlspecialchars($this->url).'&amp;skin='.$skin->id.'&amp;commv&amp;editc='.$comments[0][1].'"/>
                    <label posn="37 -4.5 0" style="TextCardScores2" textid="delete" manialink="'.htmlspecialchars($this->url).'&amp;skin='.$skin->id.'&amp;commv&amp;delc='.$comments[0][1].'"/>
                    <label posn="1 -1 1" sizen="36" autonewline="1">'.$comments[0][0].'</label>';
                echo '</frame>
                <frame posn="25 -21.67 0">
                    <quad posn="0 0 0" sizen="36 16.67" style="Bgs1" substyle="BgList"/>';
                if(isset($comments[1]))
                    echo '<label posn="37 -1 0" style="TextCardScores2" textid="edit" manialink="'.htmlspecialchars($this->url).'&amp;skin='.$skin->id.'&amp;commv&amp;editc='.$comments[1][1].'"/>
                    <label posn="37 -4.5 0" style="TextCardScores2" textid="delete" manialink="'.htmlspecialchars($this->url).'&amp;skin='.$skin->id.'&amp;commv&amp;delc='.$comments[1][1].'"/>
                    <label posn="1 -1 1" sizen="36" autonewline="1">'.$comments[1][0].'</label>';
                echo '</frame>
                <frame posn="25 -38.33 0">
                    <quad posn="0 0 0" sizen="36 16.67" style="Bgs1" substyle="BgList"/>';
                if(isset($comments[2]))
                    echo '<label posn="37 -1 0" style="TextCardScores2" textid="edit" manialink="'.htmlspecialchars($this->url).'&amp;skin='.$skin->id.'&amp;commv&amp;editc='.$comments[2][1].'"/>
                    <label posn="37 -4.5 0" style="TextCardScores2" textid="delete" manialink="'.htmlspecialchars($this->url).'&amp;skin='.$skin->id.'&amp;commv&amp;delc='.$comments[2][1].'"/>
                    <label posn="1 -1 1" sizen="36" autonewline="1">'.$comments[2][0].'</label>';
                echo '</frame>
                <frame posn="25 -55 0">
                <quad posn="0 0 1" sizen="36 6" style="Bgs1" substyle="BgList"/>
                <label posn="18 -1 1" halign="center" style="CardButtonMedium" manialink="'.htmlspecialchars($this->url).'&amp;skin='.$skin->id.'" text="Back"/>';
                if($page == 0)
                    echo '<quad style="Icons64x64_1" substyle="StarGold" posn="1.2 -3 2" sizen="3.5 3.5" valign="center"/>';
                else
                    echo '<quad style="Icons64x64_1" substyle="ArrowPrev" posn="1.2 -3 2" sizen="3.5 3.5" valign="center" manialink="'.htmlspecialchars($this->url).'&amp;skin='.$skin->id.'&amp;commv&amp;cpage='.($page-1).'"/>';
                if($page >= count($skin->comments)/3-1)
                    echo '<quad style="Icons64x64_1" substyle="StarGold" posn="34.8 -3 2" sizen="3.5 3.5" valign="center" halign="right"/>';
                else
                    echo '<quad style="Icons64x64_1" substyle="ArrowNext" posn="34.8 -3 2" sizen="3.5 3.5" valign="center" halign="right" manialink="'.htmlspecialchars($this->url).'&amp;skin='.$skin->id.'&amp;commv&amp;cpage='.($page+1).'"/>';
                echo '</frame>';
                return;
            }
            
            $this->save($skin);
            $envi = is_null($skin->envi) ? 'CarCommon' : $skin->envi;
            echo '<label posn="0 0 0" style="TextRankingsBig" textid="skinsv"/>
            <label posn="0 -5 0" text="Name:"/>
            <entry posn="14 -5 0" sizen="25 2.5" default="'.$skin->name.'" name="name"/>
            <label posn="0 -8.5 0" textid="skinauthor"/>
            <entry posn="14 -8.5 0" sizen="15 2.5" default="'.$skin->skinauthor.'" name="skinauthor"/>
            <label posn="0 -12 0" textid="modelauthor"/>
            <entry posn="14 -12 0" sizen="15 2.5" default="'.$skin->modelauthor.'" name="modelauthor"/>
            <label posn="0 -15.5 0" text="Envi:"/>
            <entry posn="14 -15.5 0" sizen="15 2.5" default="'.$envi.'" name="envi"/>
            <label posn="0 -19 0" textid="dest"/>
            <entry posn="14 -19 0" sizen="25 2.5" default="'.str_replace('\\\\', '\\', $skin->dest).'" name="dest"/>
            <label posn="0 -22.5 0" text="Maniacode:"/>
            <entry posn="14 -22.5 0" sizen="15 2.5" default="'.$skin->maniacode.'" name="mcode"/>
            <label posn="0 -26 0" style="TextCardScores2" textid="save" manialink="'.$this->encurl().'&amp;skin='.$skin->id.'&amp;name=name&amp;skinauthor=skinauthor&amp;modelauthor=modelauthor&amp;envi=envi&amp;dest=dest&amp;mcode=mcode"/>
            <label posn="0 -29.5 0" style="TextCardScores2" textid="delskin" manialink="'.$this->encurl().'&amp;skin='.$skin->id.'&amp;askdel"/>
            <label posn="0 -33 0" style="TextCardScores2" textid="commv" manialink="'.$this->encurl().'&amp;skin='.$skin->id.'&amp;commv"/>
            <label posn="0 -36.5 0" style="TextCardScores2" textid="tcs2back" manialink="'.$this->encurl().'"/>
            <quad posn="55 0 0" sizen="22.5 22.5" halign="center" image="./'.$skin->getImage().'"/>
            <label posn="55 -23 0" halign="center" style="TextCardScores2" textid="chimg" manialink="'.$this->encurl().'&amp;skin='.$skin->id.'&amp;chimage"/>
            <label posn="40 -28 0" textid="comm"/>
            <entry posn="40 -31 0" sizen="30 18" autonewline="1" name="comm" default="'.htmlspecialchars($skin->desc).'"/>
            <label posn="40 -50 0" style="TextCardScores2" textid="save" manialink="'.$this->encurl().'&amp;skin='.$skin->id.'&amp;comm=comm"/>';
            return;
        }
        
        $this->save();
        echo '<label posn="0 0 0" style="TextRankingsBig" textid="skinsv"/>
        <quad posn="10 -47 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.$this->encurl().'&amp;adds"/>
        <label posn="10 -47 0" valign="center" halign="center" textid="adds" textcolor="000"/>
        <label posn="21 -52 0" sizen="80" valign="center" textid="maniacode"/>
        <entry posn="50 -52 0" sizen="20 3" valign="center" default="'.$this->getConfig('code', '').'" name="code"/>
        <label posn="71 -52 0" valign="center" text="$fffOK" style="TextCardRaceRank" manialink="'.$this->encurl().'&amp;code=code"/>';
        $y = 0;
        $x = 0;
        $skins = self::getSharedListArray('skins');
        foreach($skins as $skin)
        {
            $pos = -6-3.5*$y++;
            echo '<label posn="'.(30*$x).' '.$pos.' 0" valign="center" text="'.$skin->name.'" style="TextCardRaceRank" manialink="'.$this->encurl().'&amp;skin='.$skin->id.'"/>';
            if($y >= 11)
            {
                $y = 0;
                $x++;
            }
        }
        return;
    }
    
    public function save(&$skin = NULL)
    {
        if(isset($_GET['name']))
        {
            if(is_null($skin))
                $skin = self::getSharedListItem('skins', $_GET['skin']);
            $skin->name = $_GET['name'];
            $skin->skinauthor = $_GET['skinauthor'];
            $skin->modelauthor = $_GET['modelauthor'];
            $oldenvi = $skin->envi;
            switch(strtolower($_GET['envi']))
            {
            case 'desert':
                $folder = 'American';
                $skin->envi = 'Desert';
                break;
            case 'snow':
                $folder = 'SnowCar';
                $skin->envi = 'Snow';
                break;
            case 'rally':
                $folder = 'Rally';
                $skin->envi = 'Rally';
                break;
            case 'bay':
                $folder = 'BayCar';
                $skin->envi = 'Bay';
                break;
            case 'island':
                $folder = 'SportCar';
                $skin->envi = 'Island';
                break;
            case 'coast':
                $folder = 'CoastCar';
                $skin->envi = 'Coast';
                break;
            case 'stadium':
                $folder = 'StadiumCar';
                $skin->envi = 'Stadium';
                break;
            default:
                $folder = 'CarCommon';
                $skin->envi = NULL;
                break;
            }
            $olddest = $skin->dest;
            if($skin->envi != $oldenvi)
                $skin->dest = 'Skins\Vehicles\\'.$folder.'\\';
            if($olddest != $_GET['dest'])
                $skin->dest = $_GET['dest'];
            if($_GET['mcode'] != '')
                $skin->maniacode = $_GET['mcode'];
            else
                $skin->maniacode = NULL;
            self::setSharedListItem('skins', $_GET['skin'], $skin);
        }
        elseif(isset($_GET['code']))
            $this->setConfig('code', $_GET['code']);
    }
}

require_once('skin.class.php');