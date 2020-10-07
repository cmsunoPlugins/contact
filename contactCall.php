<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])!='xmlhttprequest') {sleep(2);exit;} // ajax request
?>
<?php
include('../../config.php'); // $sdata
include('lang/lang.php');
// ********************* actions *************************************************************************
if(isset($_POST['action'])) {
	switch($_POST['action']) {
		// ********************************************************************************************
		case 'send':
		$q = file_get_contents('../../data/busy.json'); $a = json_decode($q,true);
		$Ubusy = (!empty($a['nom'])?$a['nom']:'');
		$Umaster = (!empty($a['master'])?$a['master']:'');
		$busy = $Ubusy;
		if($Umaster && file_exists('../../data/contactMaster.txt') && file_exists('../../data/_sdata-'.$sdata.'/'.$Umaster.'/contact.json')) $busy = $Umaster;
		if(isset($_POST['contactCaptcha'])) {
			session_start();
			if($_POST['contactCaptcha']!=$_SESSION['captcha']['code']) {
				echo '<h3>'.T_('Captcha error').'</h3>';
				Exit;
			}
		}
		include('../../template/mailTemplate.php');
		$bottom = str_replace('[[unsubscribe]]',"", $bottom); // template
		$msgT = "";
		$msgH = $top . "<table>";
		$a = false; $b = false; $c = false;
		$q = file_get_contents('../../data/_sdata-'.$sdata.'/ssite.json'); if($q) $a = json_decode($q,true);
		$q = file_get_contents('../../data/'.$busy.'/site.json'); if($q) $b = json_decode($q,true); // mail template
		$q = file_get_contents('../../data/_sdata-'.$sdata.'/'.$busy.'/contact.json'); $c = json_decode($q,true);
		$mailadm = (!empty($c['mail'])?$c['mail']:$a['mel']);
		if(!filter_var($mailadm, FILTER_VALIDATE_EMAIL)) die;
		$happy = (!empty($c['happy'])?$c['happy']:'');
		$name = (!empty($c['name'])?$c['name']:'No Reply');
		$copy = array();
		$l = 0;
		$reply = (!empty($c['reply'])?'':false);
		foreach($_POST as $k=>$v) {
			if($k!='action') {
				$v = strip_tags($v);
				$kk = $k;
				if(substr($k,0,5)=='mail0' && filter_var($v,FILTER_VALIDATE_EMAIL)) {
					$copy[] = $v;
					if($reply==='') $reply = $v;
				}
				if(substr($k,0,5)=='text0' || substr($k,0,5)=='area0' || substr($k,0,5)=='mail0') $kk = substr($k,5);
				$msgT .= $kk.' : '.$v."\r\n";
				$msgH .= '<tr><td>'.$kk.'</td><td> : '.$v.'</td></tr>';
				$l += strlen($v);
			}
		}
		$name2 = (!empty($reply)?'User':$name);
		if(empty($reply)) $reply = $mailadm;
		$msgH .= "</table>" . $bottom;
		if(file_exists('../../template/'.$b['tem'].'/contactMailTemplate.php')) include('../../template/'.$b['tem'].'/contactMailTemplate.php'); // custom template with custom methods - $msgH
		if(empty($a['subject'])) $sujet = $b['tit'] . " - Contact";
		else $sujet = $a['subject'];
		if(file_exists('../newsletter/PHPMailer/PHPMailerAutoload.php')) {
			// PHPMailer
			require '../newsletter/PHPMailer/PHPMailerAutoload.php';
			$phm = new PHPMailer();
			$phm->CharSet = "UTF-8";
			$phm->Encoding = "base64";
			$phm->setFrom($reply, $name2);
			$phm->addAddress($mailadm);
			$phm->isHTML(true);
			$phm->Subject = stripslashes($sujet);
			$phm->Body = stripslashes($msgH);		
			$phm->AltBody = stripslashes($msgT);
			if($l>10 && $phm->send()) {
				if(!$happy) echo T_('OK');
				else echo ' '.$happy;
				if(!empty($c['copy']) && !empty($copy)) {
					$ncopy = 0;
					$phm->ClearAllRecipients();
					$phm->CharSet = "UTF-8";
					$phm->Encoding = "base64";
					$phm->setFrom($mailadm, $name);
					foreach($copy as $r) {
						if(filter_var($r, FILTER_VALIDATE_EMAIL)) $phm->addAddress($r);
						++$ncopy;
					}
					$phm->isHTML(true);
					$phm->Subject = stripslashes($sujet);
					$phm->Body = stripslashes($msgH);		
					$phm->AltBody = stripslashes($msgT);
					if($ncopy) $phm->send();
				}
			}
			else echo T_('Failed to send').' : '.$phm->ErrorInfo;
		}
		else {
			$boundary = "-----=".md5(rand());
			if(!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $reply)) $rn = "\r\n";
			else $rn = "\n";
			$header = "From: \"".$name2."\"<".$reply.">".$rn;
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
			if($l>10 && mail($mailadm, stripslashes($sujet), stripslashes($msg), $header)) {
				if(!$happy) echo T_('OK');
				else echo " ".$happy;
				if(!empty($c['copy']) && !empty($copy)) {
					if(!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mailadm)) $rn = "\r\n";
					else $rn = "\n";
					$header = "From: \"".$name."\"<".$mailadm.">".$rn;
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
					foreach($copy as $r) if(filter_var($r, FILTER_VALIDATE_EMAIL)) mail($r, stripslashes($sujet), stripslashes($msg), $header);
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
