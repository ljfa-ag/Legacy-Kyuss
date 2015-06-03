<?php
/**
 * Eine Seite, von der Besucher Strecken herunterladen können.
 * @package Kyuss
 * @subpackage tracks
 * @author ljfa-ag
 * @copyright Copyright (c) 2010/2011 by ljfa-ag
 * @license <http://www.gnu.org/licenses/gpl.html> GNU Gerneral Public License
 * @version 1.0.1
 */
class tracks extends page
{
    public static $name = 'Strecken';
	public static $desc = 'Eine Seite, von der Besucher Strecken herunterladen können.';
	public static $hasConfig = true;
	public static $shared_lists = Array('tracks');
	
    public function show()
    {
		global $_dico;
		$_dico['de'] += Array(
			'addc' => 'Kommentar hinzufügen',
			'cinfo' => "Nicht mehr als zwei Kommentare hintereinander sind erlaubt.\nMaximal 100 Zeichen.",
            'notracks' => '$iKeine Strecken gefunden.'
		);
		$_dico['en'] += Array(
			'addc' => 'Add comment',
			'cinfo' => "Not more than two comments in a row.\n100 characters max.",
            'notracks' => '$iNo tracks found.'
		);
		
		if(isset($_GET['addc']))
		{
			$track = self::getSharedListItem('tracks', $_GET['info']);
            $deny1 = explode("&#0;", $track->comments[0], 2);
			$deny2 = explode("&#0;", $track->comments[1], 2);
			if($deny1[0] == $deny2[0] && $deny1[0] == $_GET['playerlogin'])
                return;
			if($_GET['addc'] != '')
			{
				$comment = substr($_GET['addc'], 0, 100);
				$comment = htmlspecialchars($comment);
                $cooment = stripslashes($comment);
				$comment = "$_GET[playerlogin]&#0;".stripslashes("$_GET[nickname]\$z\n$comment");
				array_unshift($track->comments, $comment);
				self::setSharedListItem('tracks', intval($_GET['info']), $track);
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
					<label posn="18 -10.25 1" halign="center" style="CardButtonMedium" manialink="'.$this->url.'&amp;info='.$track->id.'&amp;addc=comment" addplayerid="1" textid="addc"/>
				</frame>';
				return;
			}
		}
		if(isset($_GET['info']))
		{
			$track = self::getSharedListItem('tracks', $_GET['info']);
			$moods_de = Array('Sunrise' => 'Morgen', 'Day' => 'Tag', 'Sunset' => 'Abend', 'Night' => 'Nacht');
			$types_de = Array('Race' => 'Rennen', 'Platform' => 'Platform', 'Stunt' => 'Stunt');
			$length = round($track->authortime/100);
			$length = sprintf('%d:%02d', floor($length/60), $length % 60);
			$_dico['de']['info'] = "Autor: $track->author
Umgebung: $track->envi
Länge: $length min
Typ: {$types_de[$track->type]}
Tageszeit: {$moods_de[$track->mood]}
Schwierigkeit: ";
			$_dico['en']['info'] = "Author: $track->author
Environment: $track->envi
Length: $length min
Type: $track->type
Mood: $track->mood
Difficulty: ";
			if($track->desc == '')
			{
				$_dico['en']['desc'] = '$iNo description.';
				$_dico['de']['desc'] = '$iKeine Beschreibung vorhanden.';
			}
			else
				$_dico['en']['desc'] = $track->desc;
			$page = intval($_GET['cpage']);
			if(count($track->comments) == 0)
            {
				$comments = Array('$iKein Kommentar vorhanden.');
                $deny = false;
            }
			else
			{
				$deny1 = explode("&#0;", $track->comments[0], 2);
				$deny2 = explode("&#0;", $track->comments[1], 2);
				$deny = $deny1[0] == $deny2[0] && $deny1[0] == $_GET['playerlogin'];
				$comments = array_slice($track->comments, 3*$page);
                
                function exploder($x)
                {
                    $ret = explode("&#0;", $x, 2);
                    return $ret[1];
                }
				$comments = array_map('exploder', $comments);
			}
			echo '<quad posn="-1 1 0" sizen="65 33.5" style="Bgs1" substyle="BgList"/>
			<label posn="32 0 1" halign="center" textsize="4" text="'.$track->name.'"/>
			<quad posn="0 -4 1" sizen="30 22.5" image="./'.$track->getImage().'"/>
			<label posn="31 -4 1" textsize="4" textid="info"/>
			<quad posn="46 -22.5 1" sizen="5 5" style="Icons128x128_1" substyle="'.$track->difficulty.'"/>
			<label posn="32 -27.5 1" halign="center" style="CardButtonMedium" manialink="'.$this->getConfig('code', '').'?id='.$track->id.'" text="Download"/>
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
				echo '<label posn="18 -1 2" halign="center" style="CardButtonMedium" manialink="'.$this->url.'&amp;info='.$track->id.'&amp;addc" textid="addc" addplayerid="1"/>';
			if($page == 0)
				echo '<quad style="Icons64x64_1" substyle="StarGold" posn="1.2 -3 2" sizen="3.5 3.5" valign="center"/>';
			else
				echo '<quad style="Icons64x64_1" substyle="ArrowPrev" posn="1.2 -3 2" sizen="3.5 3.5" valign="center" manialink="'.$this->url.'&amp;info='.$track->id.'&amp;cpage='.($page-1).'"/>';
			if($page >= count($track->comments)/3-1)
				echo '<quad style="Icons64x64_1" substyle="StarGold" posn="34.8 -3 2" sizen="3.5 3.5" valign="center" halign="right"/>';
			else
				echo '<quad style="Icons64x64_1" substyle="ArrowNext" posn="34.8 -3 2" sizen="3.5 3.5" valign="center" halign="right" manialink="'.$this->url.'&amp;info='.$track->id.'&amp;cpage='.($page+1).'"/>';
			echo '</frame>';
			return;
		}
		
		echo '<label posn="0 0 0" style="TextRankingsBig" text="Tracks"/>
        <label posn="0 -52.5 0" textid="pages"/>';
		$tracks = self::getSharedListArray('tracks');
		ksort($tracks);
        $tracks = array_reverse($tracks, false);
        function _filterer_p($track)
        {
            return stripos($track->envi, $_GET['envi']) === 0;
        }
        if(isset($_GET['envi']))
            $tracks = array_filter($tracks, '_filterer_p');
        $seite = intval($_GET['page']);
        $ps = '';
        for($i = 0; $i < count($tracks)/8; $i++)
        {
            if($i == $seite)
                $ps .= ' $779'.($i+1).'$g';
            else
                $ps .= " \$h[$this->url&page=$i]".($i+1).'$h';
        }
        $_dico['de']['pages'] = 'Seiten'.$ps;
        $_dico['en']['pages'] = 'Pages'.$ps;
        if(!$tracks)
            echo '<label posn="0 -5 0" textid="notracks"/>';
		$tracks = array_slice($tracks, 8*$seite);
		for($i = 0; $i < 8; $i++)
		{
			$pos = ($i < 4) ? '0 '.(-4-12*$i).' 0' : '50 '.(44-12*$i).' 0';
			$track = $tracks[$i];
			if(!$track)
				break;
			echo '<frame posn="'.$pos.'">
			<quad posn="0 0 0" sizen="49 11" style="BgsPlayerCard" substyle="BgCard" manialink="'.$this->url.'&amp;info='.$track->id.'" addplayerid="1"/>
			<quad posn="1 -1 1" sizen="12 9" image="./'.$track->getImage().'"/>
			<label posn="14 -1 0" textsize="4" text="'.htmlspecialchars($track->name).'"/>
			<label posn="14 -4.5 0">'.$track->author."\nDownloads: ".$track->downloads.'</label>
			</frame>';
		}
    }
	
	public function configure()
	{
        global $_dico;
		$_dico['de'] += Array(
			'tracksv' => 'Strecken verwalten',
            'author' => 'Autor:',
            'authortime' => 'Autorzeit:',
            'type' => 'Typ:',
            'mood' => 'Tageszeit:',
            'diffic' => 'Schwierigkeit:',
            'save' => '$fffSpeichern',
            'chimg' => '$fffBild ändern',
            'deltrack' => '$fffStrecke entfernen',
            'upload' => '$fffHochladen',
            'imginfo' => 'Unterstützte Dateitypen: png, jpg, dds, gif, bik',
            'imgfns' => 'Nicht unterstützes Bildformat!',
            'mediav' => '$fffZusätzliche Medien verwalten',
            'media' => 'Zusätzliche Medien',
            'fname' => 'Dateiname',
            'delete' => '$fffEntfernen',
            'add' => '$fffHinzufügen',
            'comm' => 'Kommentar:',
            'addt' => 'Strecke hochladen',
            'invgbx' => 'Ungültige Streckendatei!',
            'maniacode' => "Maniacode zum Download:\nDie URL für den Code lautet: \"".DIR."index.php?maniacode=pages/tracks/download\"",
            'commv' => '$fffKommentare verwalten',
            'edit' => '$fffBearbeiten',
            'fromtmx' => "Von TM-United-Exchange",
            'tmxid' => 'TMX-ID:'
		);
		$_dico['en'] += Array(
			'tracksv' => 'Manage tracks',
            'author' => 'Author:',
            'authortime' => 'Autor time:',
            'type' => 'Type:',
            'mood' => 'Mood:',
            'diffic' => 'Difficulty:',
            'save' => '$fffSave',
            'chimg' => '$fffChange image',
            'deltrack' => '$fffDelete track',
            'upload' => '$fffUpload',
            'imginfo' => 'Supported filetypes: png, jpg, dds, gif, bik',
            'imgfns' => 'Image format not supported!',
            'mediav' => '$fffEdit additional media',
            'media' => 'Additional media',
            'fname' => 'file name',
            'delete' => '$fffDelete',
            'add' => '$fffAdd',
            'comm' => 'Comment:',
            'addt' => 'Upload track',
            'invgbx' => 'Invalid track file!',
            'maniacode' => "Maniacode for downloading:\nThe URL for the code is: \"".DIR."index.php?maniacode=pages/tracks/download\"",
            'commv' => '$fffManage comments',
            'edit' => '$fffEdit',
            'fromtmx' => "From TM-United-Exchange",
            'tmxid' => 'TMX-ID:'
		);
        
        if(isset($_GET['delete']))
        {
            $delt = self::getSharedListItem('tracks', $_GET['delete']);
            unlink("data/tracks/track$delt->id.Challenge.Gbx");
            unlink($delt->getImage());
            self::deleteSharedListItem('tracks', $_GET['delete']);
        }
        elseif(isset($_GET['addt']))
        {
            if($_GET['addt'] != '')
            {
                $gbx = file_get_contents('php://input', 'rb');
                preg_match('{<header(.*?)</header>}is', $gbx, $header);
                $header = $header[0];
                $header = simplexml_load_string($header);
                if(substr($_GET['addt'], -14, 14) != '.Challenge.Gbx' || substr($gbx, 0, 3) != 'GBX' || (string)$header->attributes()->type != 'challenge')
                {
                    echo '<label posn="0 0 0" style="TextRankingsBig" textid="invgbx"/>';
                    return;
                }
                $track = new track;
                $track->id = max(array_keys(self::getSharedListArray('tracks'))) + 1;
                $track->imgformat = 'jpg';
                $track->name = urldecode((string)$header->ident->attributes()->name);
                $track->author = (string)$header->ident->attributes()->author;
                $track->envi = (string)$header->desc->attributes()->envir;
                if($track->envi == 'Speed')
                    $track->envi = 'Desert';
                elseif($track->envi == 'Alpine')
                    $track->envi = 'Snow';
                $track->authortime = intval($header->times->attributes()->authortime) / 10;
                $track->type = (string)$header->desc->attributes()->type;
                $track->mood = trim((string)$header->desc->attributes()->mood);
                preg_match('{<Comments>....(.*?)</Comments>}is', $gbx, $desc);
                $track->desc = $desc[1];
                foreach($header->deps->dep as $media)
                    if(($url = (string)$media->attributes()->url) != '')
                        $track->media[] = $url.'&#0;'.(string)$media->attributes()->file;
                preg_match('{<Thumbnail.jpg>(.*?)</Thumbnail.jpg>}is', $gbx, $jpeg);
                file_put_contents($_dir.'data/tracks/track'.$track->id.'.Challenge.Gbx', $gbx);
                unset($gbx, $header);
                $jpeg = imagecreatefromstring($jpeg[1]);
                $jpeg = imagerotate($jpeg, 180, 0);
                $mirr = imagecreatetruecolor(256, 256);
                imagecopyresampled($mirr, $jpeg, 0, 0, 256, 0, 256, 256, -256, 256);
                imagejpeg($mirr, $track->getImage(), 100);
                imagedestroy($jpeg);
                imagedestroy($mirr);
                self::setSharedListItem('tracks', $track->id, $track);
            }
            else
            {
                echo '<label posn="0 0 0" style="TextRankingsBig" textid="addt"/>
                <fileentry posn="0 -5 0" sizen="30 2.5" name="track" default="Durchsuchen..." folder="Tracks"/>
                <label posn="0 -8.5 0" style="TextCardScores2" textid="upload" manialink="POST('.htmlspecialchars($this->url).'&amp;addt=track,track)"/>';
                if(@ini_get("allow_url_fopen"))
                    echo '<label posn="0 -13.5 0" style="TextRankingsBig" textid="fromtmx"/>
                <label posn="0 -17.5 0" textid="tmxid"/>
                <entry posn="10 -17.5 0" sizen="10 2.5" name="tmxid"/>
                <label posn="0 -21.5 0" style="TextCardScores2" textid="upload" manialink="'.$this->encurl().'&amp;addtmx=tmxid"/>';
                return;
            }
        }
        elseif(isset($_GET['addtmx']))
        {
            $gbx = file_get_contents('http://united.tm-exchange.com/get.aspx?action=trackgbx&id='.$_GET['addtmx'], 'rb');
            preg_match('{<header(.*?)</header>}is', $gbx, $header);
            $header = $header[0];
            $header = simplexml_load_string($header);
            if(substr($gbx, 0, 3) != 'GBX' || (string)$header->attributes()->type != 'challenge')
            {
                echo '<label posn="0 0 0" style="TextRankingsBig" textid="invgbx"/>';
                return;
            }
            $track = new track;
            $track->id = max(array_keys(self::getSharedListArray('tracks'))) + 1;
            $track->imgformat = 'jpg';
            $track->name = urldecode((string)$header->ident->attributes()->name);
            $track->author = (string)$header->ident->attributes()->author;
            $track->envi = (string)$header->desc->attributes()->envir;
            if($track->envi == 'Speed')
                $track->envi = 'Desert';
            elseif($track->envi == 'Alpine')
                $track->envi = 'Snow';
            $track->authortime = intval($header->times->attributes()->authortime) / 10;
            $track->type = (string)$header->desc->attributes()->type;
            $track->mood = trim((string)$header->desc->attributes()->mood);
            preg_match('{<Comments>....(.*?)</Comments>}is', $gbx, $desc);
            $track->desc = $desc[1];
            foreach($header->deps->dep as $media)
                if(($url = (string)$media->attributes()->url) != '')
                    $track->media[] = $url.'&#0;'.(string)$media->attributes()->file;
            file_put_contents($_dir.'data/tracks/track'.$track->id.'.Challenge.Gbx', $gbx);
            unset($gbx, $header);
            copy('http://united.tm-exchange.com/get.aspx?action=trackscreen&id='.$_GET['addtmx'], $track->getImage());
            self::setSharedListItem('tracks', $track->id, $track);
        }
        
        if(isset($_GET['track']) OR isset($track))
        {
            if(!isset($track))
                $track = self::getSharedListItem('tracks', $_GET['track']);
                
            if(isset($_GET['chimage']))
            {
                echo '<label posn="0 0 0" style="TextRankingsBig" textid="chimg"/>
                <fileentry posn="0 -5 0" sizen="30 2.5" name="image" default="Durchsuchen..." folder=""/>
                <label posn="0 -8.5 0" style="TextCardScores2" textid="upload" manialink="POST('.htmlspecialchars($this->url).'&amp;track='.$track->id.'&amp;upimage=image,image)"/>
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
                    unlink($track->getImage());
                    $track->imgformat = $ext;
                    copy('php://input', $track->getImage());
                    self::setSharedListItem('tracks', $track->id, $track);
                    break;
                case 'gif':
                    unlink($track->getImage());
                    $track->imgformat = 'png';
                    $image = imagecreatefromgif('php://input');
                    imagepng($image, $track->getImage());
                    imagedestroy($image);
                    self::setSharedListItem('tracks', $track->id, $track);
                    break;
                default:
                    echo '<label posn="0 0 0" style="TextRankingsBig" textid="imgfns"/>';
                    return;
                }
            }
            elseif(isset($_GET['mediav']))
            {
                if(isset($_GET['chm']))
                {
                    $track->media[$_GET['chm']] = stripslashes($_GET['murl'].'&#0;'.$_GET['mfile']);
                    self::setSharedListItem('tracks', $track->id, $track);
                }
                elseif(isset($_GET['addm']))
                {
                    $track->media[] = stripslashes($_GET['murl'].'&#0;'.$_GET['mfile']);
                    self::setSharedListItem('tracks', $track->id, $track);
                }
                elseif(isset($_GET['delm']))
                {
                    unset($track->media[$_GET['delm']]);
                    self::setSharedListItem('tracks', $track->id, $track);
                }
                echo '<label posn="0 0 0" style="TextRankingsBig" textid="media"/>
                <label posn="0 -5 0" text="URL"/>
                <label posn="46 -5 0" textid="fname"/>';
                $y = 0;
                foreach($track->media as $key => $media)
                {
                    $pos = -8.5-3.5*$y++;
                    $media = explode('&#0;', $media);
                    echo '<entry posn="0 '.$pos.'" sizen="45 2.5" default="'.$media[0].'" name="url'.$key.'"/>
                    <entry posn="46 '.$pos.'" sizen="40 2.5" default="'.$media[1].'" name="file'.$key.'"/>
                    <label posn="86.7 '.$pos.'" style="TextCardScores2" text="$fffOK" manialink="'.htmlspecialchars($this->url).'&amp;track='.$track->id.'&amp;mediav&amp;chm='.$key.'&amp;murl=url'.$key.'&amp;mfile=file'.$key.'"/>
                    <label posn="91 '.$pos.'" style="TextCardScores2" textid="delete" manialink="'.htmlspecialchars($this->url).'&amp;track='.$track->id.'&amp;mediav&amp;delm='.$key.'"/>';
                }
                $pos = -10-3.5*$y;
                echo '<entry posn="0 '.$pos.'" sizen="45 2.5" default="" name="newurl"/>
                <entry posn="46 '.$pos.'" sizen="40 2.5" default="" name="newfile"/>
                <label posn="86.7 '.$pos.'" style="TextCardScores2" textid="add" manialink="'.htmlspecialchars($this->url).'&amp;track='.$track->id.'&amp;mediav&amp;addm&amp;murl=newurl&amp;mfile=newfile"/>
                <label posn="0 '.($pos-4).'" style="TextCardScores2" textid="tcs2back" manialink="'.htmlspecialchars($this->url).'&amp;track='.$track->id.'"/>';
                return;
            }
            elseif(isset($_GET['commv']))
            {
                echo '<label posn="0 0 0" style="TextRankingsBig" textid="commv"/>';
                if(isset($_GET['delc']))
                {
                    unset($track->comments[intval($_GET['delc'])]);
                    self::setSharedListItem('tracks', $track->id, $track);
                }
                elseif(isset($_GET['editc']))
                {
                    if(!isset($_GET['text']))
                    {
                        $comment = explode("&#0;", $track->comments[intval($_GET['editc'])], 2);
                        echo '<label posn="0 -5 0" text="Login:"/>
                        <entry posn="7 -5 1" sizen="36 3" default="'.$comment[0].'" name="login"/>
                        <label posn="0 -8.5 1" text="Text:"/>
                        <entry posn="7 -8.5 1" sizen="36 16" default="'.htmlspecialchars(utf8_decode($comment[1])).'" name="text" autonewline="1"/>
                        <label posn="0 -25.5 0" style="TextCardScores2" textid="save" manialink="'.$this->encurl().'&amp;track='.$track->id.'&amp;commv&amp;editc='.$_GET['editc'].'&amp;login=login&amp;text=text"/>';
                        return;
                    }
                    else
                        $track->comments[intval($_GET['editc'])] = $_GET['login'].'&#0;'.stripslashes($_GET['text']);
                    self::setSharedListItem('tracks', $track->id, $track);
                }
                $page = intval($_GET['cpage']);
                $comments = $track->comments;
                function _walker_t(&$x, $k)
                {
                    $ret = explode("&#0;", $x, 2);
                    $x = array($ret[1], $k);
                }
				array_walk($comments, '_walker_t');
                $comments = array_slice($comments, 3*$page);
                echo '<frame posn="25 -5 0">
                    <quad posn="0 0 0" sizen="36 16.67" style="Bgs1" substyle="BgList"/>';
                if(isset($comments[0]))
                    echo '<label posn="37 -1 0" style="TextCardScores2" textid="edit" manialink="'.htmlspecialchars($this->url).'&amp;track='.$track->id.'&amp;commv&amp;editc='.$comments[0][1].'"/>
                    <label posn="37 -4.5 0" style="TextCardScores2" textid="delete" manialink="'.htmlspecialchars($this->url).'&amp;track='.$track->id.'&amp;commv&amp;delc='.$comments[0][1].'"/>
    				<label posn="1 -1 1" sizen="36" autonewline="1">'.$comments[0][0].'</label>';
    			echo '</frame>
    			<frame posn="25 -21.67 0">
                    <quad posn="0 0 0" sizen="36 16.67" style="Bgs1" substyle="BgList"/>';
                if(isset($comments[1]))
    				echo '<label posn="37 -1 0" style="TextCardScores2" textid="edit" manialink="'.htmlspecialchars($this->url).'&amp;track='.$track->id.'&amp;commv&amp;editc='.$comments[1][1].'"/>
                    <label posn="37 -4.5 0" style="TextCardScores2" textid="delete" manialink="'.htmlspecialchars($this->url).'&amp;track='.$track->id.'&amp;commv&amp;delc='.$comments[1][1].'"/>
    				<label posn="1 -1 1" sizen="36" autonewline="1">'.$comments[1][0].'</label>';
    			echo '</frame>
    			<frame posn="25 -38.33 0">
                    <quad posn="0 0 0" sizen="36 16.67" style="Bgs1" substyle="BgList"/>';
                if(isset($comments[2]))
       				echo '<label posn="37 -1 0" style="TextCardScores2" textid="edit" manialink="'.htmlspecialchars($this->url).'&amp;track='.$track->id.'&amp;commv&amp;editc='.$comments[2][1].'"/>
                    <label posn="37 -4.5 0" style="TextCardScores2" textid="delete" manialink="'.htmlspecialchars($this->url).'&amp;track='.$track->id.'&amp;commv&amp;delc='.$comments[2][1].'"/>
       				<label posn="1 -1 1" sizen="36" autonewline="1">'.$comments[2][0].'</label>';
    			echo '</frame>
                <frame posn="25 -55 0">
				<quad posn="0 0 1" sizen="36 6" style="Bgs1" substyle="BgList"/>
                <label posn="18 -1 1" halign="center" style="CardButtonMedium" manialink="'.htmlspecialchars($this->url).'&amp;track='.$track->id.'" text="Back"/>';
                if($page == 0)
    				echo '<quad style="Icons64x64_1" substyle="StarGold" posn="1.2 -3 2" sizen="3.5 3.5" valign="center"/>';
    			else
    				echo '<quad style="Icons64x64_1" substyle="ArrowPrev" posn="1.2 -3 2" sizen="3.5 3.5" valign="center" manialink="'.htmlspecialchars($this->url).'&amp;track='.$track->id.'&amp;commv&amp;cpage='.($page-1).'"/>';
    			if($page >= count($track->comments)/3-1)
    				echo '<quad style="Icons64x64_1" substyle="StarGold" posn="34.8 -3 2" sizen="3.5 3.5" valign="center" halign="right"/>';
    			else
    				echo '<quad style="Icons64x64_1" substyle="ArrowNext" posn="34.8 -3 2" sizen="3.5 3.5" valign="center" halign="right" manialink="'.htmlspecialchars($this->url).'&amp;track='.$track->id.'&amp;commv&amp;cpage='.($page+1).'"/>';
                echo '</frame>';
                return;
            }
            elseif(isset($_GET['comm']))
            {
                $track->desc = $_GET['comm'];
                self::setSharedListItem('tracks', $track->id, $track);            
            }
            elseif(isset($_GET['askdel']))
            {
                $_dico['en']['deltq'] = "Do you really want to delete the track '$track->name\$z'?\n\$h[$this->url&delete=$_GET[track]]Yes\$h   \$h[$this->url&track=$_GET[track]]No";
                $_dico['de']['deltq'] = "Möchten Sie die Strecke '$track->name\$z' wirklich entfernen?\n\$h[$this->url&delete=$_GET[track]]Ja\$h   \$h[$this->url&track=$_GET[track]]Nein";
                echo '<label posn="0 0 0" style="TextRankingsBig" textid="deltrack"/>
                <label posn="0 -5 0" textid="deltq"/>';
                return;
            }
            
            $this->save($track);
            $length = round($track->authortime/100);
			$length = sprintf('%d:%02d', floor($length/60), $length % 60);
            echo '<label posn="0 0 0" style="TextRankingsBig" textid="tracksv"/>
            <label posn="0 -5 0" text="Name:"/>
            <entry posn="14 -5 0" sizen="25 2.5" default="'.$track->name.'" name="name"/>
            <label posn="0 -8.5 0" textid="author"/>
            <entry posn="14 -8.5 0" sizen="15 2.5" default="'.$track->author.'" name="author"/>
            <label posn="0 -12 0" text="Envi:"/>
            <entry posn="14 -12 0" sizen="15 2.5" default="'.$track->envi.'" name="envi"/>
            <label posn="0 -15.5 0" textid="authortime"/>
            <entry posn="14 -15.5 0" sizen="10 2.5" default="'.$length.'" name="authortime"/>
            <label posn="0 -19 0" textid="type"/>
            <entry posn="14 -19 0" sizen="10 2.5" default="'.$track->type.'" name="type"/>
            <label posn="0 -22.5 0" textid="mood"/>
            <entry posn="14 -22.5 0" sizen="10 2.5" default="'.$track->mood.'" name="mood"/>
            <label posn="0 -26 0" textid="diffic"/>
            <entry posn="14 -26 0" sizen="10 2.5" default="'.$track->difficulty.'" name="diffic"/>
            <label posn="0 -29.5 0" style="TextCardScores2" textid="save" manialink="'.$this->encurl().'&amp;track='.$track->id.'&amp;name=name&amp;author=author&amp;envi=envi&amp;authortime=authortime&amp;type=type&amp;mood=mood&amp;diffic=diffic"/>
            <label posn="0 -33 0" style="TextCardScores2" textid="deltrack" manialink="'.$this->encurl().'&amp;track='.$track->id.'&amp;askdel"/>
            <label posn="0 -36.5 0" style="TextCardScores2" textid="mediav" manialink="'.$this->encurl().'&amp;track='.$track->id.'&amp;mediav"/>
            <label posn="0 -40 0" style="TextCardScores2" textid="commv" manialink="'.$this->encurl().'&amp;track='.$track->id.'&amp;commv"/>
            <label posn="0 -43.5 0" style="TextCardScores2" textid="tcs2back" manialink="'.$this->encurl().'"/>
            <quad posn="55 0 0" sizen="30 22.5" halign="center" image="./'.$track->getImage().'"/>
            <label posn="55 -23 0" halign="center" style="TextCardScores2" textid="chimg" manialink="'.$this->encurl().'&amp;track='.$track->id.'&amp;chimage"/>
            <label posn="40 -28 0" textid="comm"/>
            <entry posn="40 -31 0" sizen="30 18" autonewline="1" name="comm" default="'.htmlspecialchars($track->desc).'"/>
            <label posn="40 -50 0" style="TextCardScores2" textid="save" manialink="'.$this->encurl().'&amp;track='.$track->id.'&amp;comm=comm"/>';
            return;
        }
        $this->save();
        echo '<label posn="0 0 0" style="TextRankingsBig" textid="tracksv"/>
        <quad posn="10 -47 0" sizen="20 5" valign="center" halign="center" style="Bgs1" substyle="BgButton" manialink="'.$this->encurl().'&amp;addt"/>
        <label posn="10 -47 0" valign="center" halign="center" textid="addt" textcolor="000"/>
        <label posn="21 -52 0" sizen="80" valign="center" textid="maniacode"/>
        <entry posn="50 -52 0" sizen="20 3" valign="center" default="'.$this->getConfig('code', '').'" name="code"/>
        <label posn="71 -52 0" valign="center" text="$fffOK" style="TextCardRaceRank" manialink="'.$this->encurl().'&amp;code=code"/>';
        $y = 0;
        $x = 0;
        $tracks = self::getSharedListArray('tracks');
        foreach($tracks as $track)
        {
            $pos = -6-3.5*$y++;
            echo '<label posn="'.(30*$x).' '.$pos.' 0" valign="center" text="'.$track->name.'" style="TextCardRaceRank" manialink="'.$this->encurl().'&amp;track='.$track->id.'"/>';
            if($y >= 11)
            {
                $y = 0;
                $x++;
            }
        }
        return;
	}
    
    public function save(&$track = NULL)
    {
        if(isset($_GET['name']))
        {
            if(is_null($track))
                $track = self::getSharedListItem('tracks', $_GET['track']);
            $track->name = $_GET['name'];
            $track->author = $_GET['author'];
            $track->envi = $_GET['envi'];
            switch(strtolower($_GET['type']))
            {
            case 'platform':
                $track->type = 'Platform';
                break;
            case 'stunt':
                $track->type = 'Stunt';
                break;
            default:
                $track->type = 'Race';
                break;
            }
            switch(strtolower($_GET['mood']))
            {
            case 'sunrise':
                $track->mood = 'Sunrise';
                break;
            case 'sunset':
                $track->mood = 'Sunset';
                break;
            case 'night':
                $track->mood = 'Night';
                break;
            default:
                $track->mood = 'Day';
                break;
            }
            switch(strtolower($_GET['diffic']))
            {
            case 'beginner':
                $track->difficulty = 'Beginner';
                break;
            case 'easy':
                $track->difficulty = 'Easy';
                break;
            case 'hard':
                $track->difficulty = 'Hard';
                break;
            case 'extreme':
                $track->difficulty = 'Extreme';
                break;
            default:
                $track->difficulty = 'Medium';
                break;
            }
            $time = explode(':', $_GET['authortime'], 2);
            $track->authortime = intval($time[0])*6000 + intval($time[1])*100;
            self::setSharedListItem('tracks', $_GET['track'], $track);
        }
        elseif(isset($_GET['code']))
            $this->setConfig('code', $_GET['code']);
    }
}

require_once('track.class.php');