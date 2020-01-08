<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])!='xmlhttprequest') {sleep(2);exit;} // ajax request
?>
<?php
include('../../config.php');
include('lang/lang.php');
// ********************* actions *************************************************************************
if(isset($_POST['action']))
	{
	switch($_POST['action'])
		{
		// ********************************************************************************************
		case 'send':
		$q = file_get_contents('../../data/busy.json'); $a = json_decode($q,true); $Ubusy = $a['nom'];
		if(isset($_POST['contactCaptcha']))
			{
			session_start();
			if($_POST['contactCaptcha']!=$_SESSION['captcha']['code'])
				{
				echo '<h3>'.T_('Captcha error').'</h3>';
				Exit;
				}
			}
		include('../../template/mailTemplate.php');
		$bottom = str_replace('[[unsubscribe]]',"", $bottom); // template
		$msgT = "";
		$msgH = $top . "<table>";
		$q = file_get_contents('../../data/_sdata-'.$sdata.'/'.$Ubusy.'/contact.json'); $a = json_decode($q,true);
		$q = file_get_contents('../../data/'.$Ubusy.'/site.json'); $b = json_decode($q,true);
		$mail = $a['mail'];
		$happy = $a['happy'];
		$copy = array();
		$l = 0;
		foreach($_POST as $k=>$v)
			{
			if($k!='action')
				{
				$v = strip_tags($v);
				$kk = $k;
				if(substr($k,0,5)=='mail0' && filter_var($v,FILTER_VALIDATE_EMAIL)) $copy[] = $v;
				if(substr($k,0,5)=='text0' || substr($k,0,5)=='area0' || substr($k,0,5)=='mail0') $kk = substr($k,5);
				$msgT .= $kk.' : '.$v."\r\n";
				$msgH .= '<tr><td>'.$kk.'</td><td> : '.$v.'</td></tr>';
				$l += strlen($v);
				}
			}
		$msgH .= "</table>" . $bottom;
		if(file_exists('../../template/'.$b['tem'].'/contactMailTemplate.php')) include('../../template/'.$b['tem'].'/contactMailTemplate.php'); // custom template with custom methods - $msgH
		if(empty($a['subject'])) $sujet = $b['tit'] . " - Contact";
		else $sujet = $a['subject'];
		if(file_exists('../newsletter/PHPMailer/PHPMailerAutoload.php'))
			{
			// PHPMailer
			require '../newsletter/PHPMailer/PHPMailerAutoload.php';
			$phm = new PHPMailer();
			$phm->CharSet = "UTF-8";
			$phm->Encoding = "base64";
			$phm->setFrom($mail, 'No Reply');
			$phm->addAddress($mail);
			$phm->isHTML(true);
			$phm->Subject = stripslashes($sujet);
			$phm->Body = stripslashes($msgH);		
			$phm->AltBody = stripslashes($msgT);
			if($l>10 && $phm->send())
				{
				if(!$happy) echo T_('OK');
				else echo ' '.$happy;
				if(!empty($a['copy']) && !empty($copy))
					{
					$phm->ClearAllRecipients();
					$phm->CharSet = "UTF-8";
					$phm->Encoding = "base64";
					$phm->setFrom($mail, 'No Reply');
					foreach($copy as $r) $phm->addAddress($r);
					$phm->isHTML(true);
					$phm->Subject = stripslashes($sujet);
					$phm->Body = stripslashes($msgH);		
					$phm->AltBody = stripslashes($msgT);
					$phm->send();
					}
				}
			else echo T_('Failed to send').' : '.$phm->ErrorInfo;
			}
		else
			{
			$boundary = "-----=".md5(rand());
			if(!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) $rn = "\r\n";
			else $rn = "\n";
			$header = "From: \"No reply\"<".$mail.">".$rn;
			$header.= "MIME-Version: 1.0".$rn;
			$header.= "Content-Type: multipart/alternative;".$rn." boundary=\"$boundary\"".$rn;
			$msg= $rn."--".$boundary.$rn;
			$msg.= "Content-Type: text/plain; charset=\"utf-8\"".$rn;
			$msg.= "Content-Transfer-Encoding: 8bit".$rn;
			$msg.= $rn.$msgT.$rn;
			$msg.= $rn."--".$boundary.$rn;
			$msg.= "Content-Type: text/html; charset=\"utf-8\"".$rn;
			$msg.= "Content-Transfer-Encoding: 8bit".$rn;
			$msg.= $rn.$msgH.$rn;
			$msg.= $rn."--".$boundary."--".$rn;
			$msg.= $rn."--".$boundary."--".$rn;
			if($l>10 && mail($mail, stripslashes($sujet), stripslashes($msg), $header))
				{
				if(!$happy) echo T_('OK');
				else echo " ".$happy;
				if(!empty($a['copy']) && !empty($copy))
					{
					foreach($copy as $r) mail($r, stripslashes($sujet), stripslashes($msg), $header);
					}
				}
			else echo T_('Failed to send');
			}
		break;
		// ********************************************************************************************
		}
	clearstatcache();
	exit;
	}
?>
