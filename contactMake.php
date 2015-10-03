<?php
if (!isset($_SESSION['cmsuno'])) exit();
?>
<?php
if(file_exists('data/_sdata-'.$sdata.'/'.$Ubusy.'/contact.json'))
	{
	// {"t":"te","l":"Pr\u00e9nom"}
	$o1 = "\r\n".'<form id="contactfrm">'."\r\n\t".'<table>'."\r\n";
	$q1 = file_get_contents('data/_sdata-'.$sdata.'/'.$Ubusy.'/contact.json');
	$a1 = json_decode($q1,true);
	$s1 = '';
	$s2 = '';
	foreach ($a1['frm'] as $k1=>$v1)
		{
		$v2 = stripslashes(utf8_decode($v1['l']));
		$v2 = strtr($v2, 'באגהדוחיטךכםלמןסףעפצץתשûü‎ÿ -', 'aaaaaaceeeeiiiinooooouuuuyy__');
		$v2 = preg_replace("/[^a-zA-Z0-9\d_]+/","",$v2);
		$s1 .= '&'.$v2.'="+document.getElementById(\''.$v2.'\').value+"';
		$o1 .= "\t\t".'<tr><td><label>'.stripslashes(str_replace('_',' ',$v1['l'])).'</label></td>';
		if ($v1['t']=='te') $o1 .= '<td><input type="text" name="'.$v2.'" id="'.$v2.'" /></td>';
		else if ($v1['t']=='ta') $o1 .= '<td><textarea name="'.$v2.'" id="'.$v2.'"></textarea></td>';
		$o1 .= '</tr>'."\r\n";
		$s2 .= 'document.getElementById(\''.$v2.'\').value="";';
		}
	if ($a1['captcha']==1)
		{
		$s1 .= '&contactCaptcha="+document.getElementById(\'contactCaptcha\').value+"';
		$s2 .= 'document.getElementById(\'contactCaptcha\').value="";';
		$o1 .= "\t\t".'<tr><td>Captcha :<br /><img src="" title="Captcha" id="imageCaptcha" style="height:30px; width:72px" /></td><td><input type="text" name="contactCaptcha" id="contactCaptcha" /><br />'._('Copy this code').'</td></tr>'."\r\n";
		$Uonload .= 'var x=new XMLHttpRequest();x.open("POST","uno/plugins/contact/captcha.php",true);x.setRequestHeader("Content-type","application/x-www-form-urlencoded");x.onreadystatechange=function(){if(x.readyState==4 && x.status==200){document.getElementById("imageCaptcha").src=x.responseText;}};x.send();'."\r\n";
		}
	$o1 .= "\t\t".'<tr><td></td><td><button type="button" onClick="contactSend();">'.$a1['send'].'</button></td>'."\r\n";
	$o1 .= "\t".'</table>'."\r\n".'</form>'."\r\n";
	$Ucontent = str_replace('[[contact]]',$o1,$Ucontent);
	$Uhtml = str_replace('[[contact]]',$o1,$Uhtml);
	//
	$o2 = '<script type="text/javascript">';
	$o2 .= 'function contactSend(){x=new XMLHttpRequest();x.open("POST","uno/plugins/contact/contactCall.php",true);x.setRequestHeader("Content-type","application/x-www-form-urlencoded");x.setRequestHeader("X-Requested-With","XMLHttpRequest");x.onreadystatechange=function(){if(x.readyState==4&&x.status==200){unoPop(x.responseText,5000);document.getElementById(\'contactCaptcha\').value=""}};x.send("action=send'.$s1.'");}';
	$o2 .= '</script>'."\r\n";
	$unoPop=1; // include unoPop.js in output
	$Ufoot .= $o2;
	}
?>
