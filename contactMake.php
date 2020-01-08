<?php
if(!isset($_SESSION['cmsuno'])) exit();
?>
<?php
if(file_exists('data/_sdata-'.$sdata.'/'.$Ubusy.'/contact.json'))
	{
	// {"t":"te","l":"Pr\u00e9nom"}
	$o1 = "\r\n".'<div class="'.(isset($Uw3['input']['w3-container'])?$Uw3['input']['w3-container']:'w3-container').' contactBloc" id="contactBloc">'."\r\n\t".'<form>'."\r\n";
	$q1 = file_get_contents('data/_sdata-'.$sdata.'/'.$Ubusy.'/contact.json');
	$a1 = json_decode($q1,true);
	$s1 = '';
	$s2 = '';
	foreach($a1['frm'] as $k1=>$v1)
		{
		$v2 = stripslashes(utf8_decode($v1['l']));
		$v2 = strtr($v2, 'באגהדוחיטךכםלמןסףעפצץתשûü‎ÿ -', 'aaaaaaceeeeiiiinooooouuuuyy__');
		$v2 = preg_replace("/[^a-zA-Z0-9\d_]+/","",$v2);
		$o1 .= "\t\t".'<div class="w3-section"><label>'.stripslashes(str_replace('_',' ',$v1['l'])).'</label>';
		if($v1['t']=='te')
			{
			$v2 = 'text0'.$v2;
			$o1 .= '<input class="'.(isset($Uw3['input']['w3-input'])?$Uw3['input']['w3-input']:'w3-input').'" type="text" name="'.$v2.'" id="'.$v2.'" />';
			}
		else if($v1['t']=='tm')
			{
			$v2 = 'mail0'.$v2;
			$o1 .= '<input class="'.(isset($Uw3['input']['w3-input'])?$Uw3['input']['w3-input']:'w3-input').'" type="email" name="'.$v2.'" id="'.$v2.'" />';
			}
		else if($v1['t']=='ta')
			{
			$v2 = 'area0'.$v2;
			$o1 .= '<textarea class="'.(isset($Uw3['input']['w3-input'])?$Uw3['input']['w3-input']:'w3-input').'" name="'.$v2.'" id="'.$v2.'"></textarea>';
			}
		$s1 .= '&'.$v2.'="+document.getElementById(\''.$v2.'\').value+"';
		$o1 .= '</div>'."\r\n";
		$s2 .= 'document.getElementById(\''.$v2.'\').value="";';
		}
	if($a1['captcha']==1)
		{
		$s1 .= '&contactCaptcha="+document.getElementById(\'contactCaptcha\').value+"';
		$s2 .= 'document.getElementById(\'contactCaptcha\').value="";';
		$o1 .= "\t\t".'<div class="w3-section"><label>Captcha</label>';
		$o1 .= '<input class="'.(isset($Uw3['input']['w3-input'])?$Uw3['input']['w3-input']:'w3-input').' contactCaptcha" type="text" name="contactCaptcha" id="contactCaptcha" />';
		$o1 .= '<img class="imageCaptcha" src="" title="Captcha" id="imageCaptcha" style="height:30px; width:72px" /></div>'."\r\n";
		$Uonload .= 'var x=new XMLHttpRequest();x.open("POST","uno/plugins/contact/captcha.php",true);x.setRequestHeader("Content-type","application/x-www-form-urlencoded");x.setRequestHeader("X-Requested-With","XMLHttpRequest");x.onreadystatechange=function(){if(x.readyState==4 && x.status==200){document.getElementById("imageCaptcha").src=x.responseText;}};x.send();'."\r\n";
		}
	$o1 .= "\t\t".'<div><button class="'.(isset($Uw3['input']['w3-button'])?$Uw3['input']['w3-button']:'w3-button').'" type="button" onClick="contactSend();">'.$a1['send'].'</button></div>'."\r\n";
	$o1 .= "\t".'</form>'."\r\n".'</div><!-- #contactBloc -->'."\r\n";
	if(empty($Ua['w3'])) $Ustyle .= ".contactBloc{max-width:480px;margin:10px;}.contactBloc label{text-transform:capitalize;}.contactBloc input,.contactBloc textarea{display:block;width:100%;margin:0 0 5px;padding:0}.contactBloc textarea{height:120px;}.contactBloc button{margin:7px 0 0;padding:4px 12px;}";
	$Ustyle .= "input.contactCaptcha{display:inline-block;margin:10px;max-width:80px;}.imageCaptcha{margin:-10px 10px;}\r\n";
	$Ucontent = str_replace('[[contact]]',$o1,$Ucontent);
	$Uhtml = str_replace('[[contact]]',$o1,$Uhtml);
	//
	$o2 = '<script type="text/javascript">';
	$o2 .= 'function contactSend(){var x=new XMLHttpRequest(),p="action=send'.$s1.'";x.open("POST","uno/plugins/contact/contactCall.php",true);x.setRequestHeader("Content-type","application/x-www-form-urlencoded");x.setRequestHeader("X-Requested-With","XMLHttpRequest");x.setRequestHeader("Content-length",p.length);x.setRequestHeader("Connection","close");x.onreadystatechange=function(){if(x.readyState==4&&x.status==200){unoPop(x.responseText,5000);document.getElementById(\'contactCaptcha\').value=""}};x.send(p);}';
	$o2 .= '</script>'."\r\n";
	$unoPop=1; // include unoPop.js in output
	$Ufoot .= $o2;
	}
?>
