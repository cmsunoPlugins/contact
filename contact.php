<?php
session_start(); 
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])!='xmlhttprequest') {sleep(2);exit;} // ajax request
if(!isset($_POST['unox']) || $_POST['unox']!=$_SESSION['unox']) {sleep(2);exit;} // appel depuis uno.php
?>
<?php
include('../../config.php');
include('lang/lang.php');
// ********************* actions *************************************************************************
if (isset($_POST['action']))
	{
	switch ($_POST['action'])
		{
		// ********************************************************************************************
		case 'plugin': ?>
		<style type="text/css">#contactResult td{padding:10px;vertical-align:middle;} #contactResult td:first-child{max-width:120px;}</style>
		<div class="blocForm">
			<h2>Contact</h2>
			<p><?php echo T_("This plugin allows you to create a contact form that can be installed at any location of the site.");?></p>
			<p><?php echo T_("Just insert the code")." <code>[[contact]]</code> ".T_("in the text of your page or directly into the template. This code will be replaced by the form.");?></p>
			<table class="hForm">
				<tr>
					<td><label><?php echo T_("Admin email");?></label></td>
					<td><input type="text" class="input" name="contactAdmin" id="contactAdmin" /></td>
					<td><em><?php echo T_("E-mail address of destination : Your email address or the contact site.");?></em></td>
				</tr>
				<tr>
					<td><label><?php echo T_("Send button");?></label></td>
					<td><input type="text" class="input" name="contactSend" id="contactSend" /></td>
					<td><em><?php echo T_("The word that should appear on the 'Send' button.");?></em></td>
				</tr>
				<tr>
					<td><label><?php echo T_("Thank you");?></label></td>
					<td><input type="text" class="input" name="contactHappy" id="contactHappy" /></td>
					<td><em><?php echo T_("The sentence of thanks that will be displayed on the screen after sending the message.");?></em></td>
				</tr>
				<tr>
					<td><label><?php echo T_("Enable Captcha");?></label></td>
					<td><input type="checkbox" class="input"  name="contactCaptcha" id="contactCaptcha" /></td>
					<td><em><?php echo T_("Code image to enter to block the automatic sending of email by robots.");?></em></td>
				</tr>
			</table>
			<h3><?php echo T_("Add a field :");?></h3>
			<table class="hForm">
				<tr>
					<td><label><?php echo T_("Label");?></label></td>
					<td>
						<input type="text" class="input" name="contactLabel" id="contactLabel" value="" />
						<select name="contactType" id="contactType" />
							<option value="te"><?php echo T_("Text");?></option>
							<option value="ta"><?php echo T_("Textarea");?></option>
						</select>
					</td>
					<td><div class="bouton fr" onClick="f_contact_add(document.getElementById('contactLabel').value, document.getElementById('contactType').options[document.getElementById('contactType').selectedIndex].value);" title="<?php echo T_("Save settings");?>"><?php echo T_("Add");?></div></td>
				</tr>
			</table>
			<h3><?php echo T_("Result :");?></h3>
			<form id="frmContact">
				<table id="contactResult"></table>
			</form>
			<div class="bouton fr" onClick="f_contact_save();" title="<?php echo T_("Save settings");?>"><?php echo T_("Save");?></div>
			<div class="clear"></div>
		</div>
		<?php break;
		// ********************************************************************************************
		case 'save':
		$q = file_get_contents('../../data/busy.json'); $a = json_decode($q,true); $Ubusy = $a['nom'];
		if(!is_dir('../../data/_sdata-'.$sdata.'/'.$Ubusy.'/')) mkdir('../../data/_sdata-'.$sdata.'/'.$Ubusy.'/',0711);
		$a = array(); $c=0;
		if(!$_POST['contactAdmin'])
			{
			$q1 = @file_get_contents('../../data/_sdata-'.$sdata.'/ssite.json');
			if($q1)
				{
				$a1 = json_decode($q1,true);
				$a['mail'] = $a1['mel'];
				}
			}
		else $a['mail'] = $_POST['contactAdmin'];
		$a['send'] = (stripslashes($_POST['contactSend'])?stripslashes($_POST['contactSend']):'OK');
		$a['happy'] = stripslashes($_POST['contactHappy']);
		if ($_POST['contactCaptcha']=="true") $a['captcha']=1; else $a['captcha']=0;
		foreach($_POST as $k=>$v)
			{
			if ($k!='action' && $k!='unox' && $k!='contactAdmin' && $k!='contactSend' && $k!='contactHappy' && $k!='contactCaptcha')
				{
				$a['frm'][$c]['t'] = substr($k,0,2);
				$a['frm'][$c]['l'] = stripslashes(substr($k,2));
				}
			++$c;
			}
		$out = json_encode($a);
		if (file_put_contents('../../data/_sdata-'.$sdata.'/'.$Ubusy.'/contact.json', $out)) echo T_('Backup performed');
		else echo '!'.T_('Impossible backup');
		break;
		// ********************************************************************************************
		case 'load':
		$q = file_get_contents('../../data/busy.json'); $a = json_decode($q,true); $Ubusy = $a['nom'];
		$q = file_get_contents('../../data/_sdata-'.$sdata.'/'.$Ubusy.'/contact.json');
		$q = str_replace("\\\\'","'",$q);
		echo $q; exit;
		break;
		// ********************************************************************************************
		}
	clearstatcache();
	exit;
	}
?>
