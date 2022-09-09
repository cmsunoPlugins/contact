<?php
session_start(); 
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])!='xmlhttprequest') {sleep(2);exit;} // ajax request
if(!isset($_POST['unox']) || $_POST['unox']!=$_SESSION['unox']) {sleep(2);exit;} // appel depuis uno.php
?>
<?php
include('../../config.php');
include('lang/lang.php');
// ********************* actions *************************************************************************
if(isset($_POST['action'])) {
	$q = file_get_contents('../../data/busy.json'); $a = json_decode($q,true);
	$Ubusy = (!empty($a['nom'])?$a['nom']:'');
	$Umaster = (!empty($a['master'])?$a['master']:'');
	if(empty($Ubusy)) exit;
	switch ($_POST['action']) {
		// ********************************************************************************************
		case 'plugin': ?>
			
		<style type="text/css">#contactResult td{padding:10px;vertical-align:middle;} #contactResult td:first-child{max-width:120px;}</style>
		<div class="blocForm">
			<h2>Contact</h2>
			<p><?php echo T_("This plugin allows you to create a contact form that can be installed at any location of the site.");?></p>
			<p><?php echo T_("Just insert the code")." <code>[[contact]]</code> ".T_("in the text of your page or directly into the template. This code will be replaced by the form.");?></p>
		<?php if($Umaster && $Ubusy!=$Umaster && file_exists('../../data/contactMaster.txt')) { ?>
			
			<p style="text-align:center;font-size:110%;font-weight:700;margin:20px 0;"><?php echo T_("Master mode activated.");?></p>
			<p style="text-align:center">
				<?php echo T_("Config the plugin in the master page : "); ?>
				<span style="font-weight:700; color:green;"><?php echo $Umaster;?></span>
			</p>
		<?php } else { ?>
			
			<table class="hForm">
				<tr>
					<td><label><?php echo T_("Admin email");?></label></td>
					<td><input type="text" class="input" name="contactAdmin" id="contactAdmin" /></td>
					<td><em><?php echo T_("E-mail address of destination : Your email address or the contact site.");?></em></td>
				</tr>
				<tr>
					<td><label><?php echo T_("Name");?></label></td>
					<td><input type="text" class="input" name="contactName" id="contactName" /></td>
					<td><em><?php echo T_("The name for your email address.");?></em></td>
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
					<td><label><?php echo T_("Subject");?></label></td>
					<td><input type="text" class="input" name="contactSubject" id="contactSubject" /></td>
					<td><em><?php echo T_("The title of the email for sending. Empty => Site title - Contact");?></em></td>
				</tr>
				<tr>
					<td><label><?php echo T_("Enable Captcha");?></label></td>
					<td><input type="checkbox" class="input" name="contactCaptcha" id="contactCaptcha" /></td>
					<td><em><?php echo T_("Code image to enter to block the automatic sending of email by robots.");?></em></td>
				</tr>
				<tr>
					<td><label><?php echo T_("Mail to author");?></label></td>
					<td><input type="checkbox" class="input" name="contactCopy" id="contactCopy" /></td>
					<td><em><?php echo T_("Send a copy to the emails of the form.").' ('.T_("Text email").')';?></em></td>
				</tr>
				<tr>
					<td><label><?php echo T_("Master mode");?></label></td>
					<td><input type="checkbox" class="input" name="contactMaster" id="contactMaster" /></td>
					<td><em><?php echo T_("In multipage site, the configuration will be centralized on the master page.");?></em></td>
				</tr>
				<tr>
					<td><label><?php echo T_("Reply to the email");?></label></td>
					<td><input type="checkbox" class="input" name="contactReply" id="contactReply" /></td>
					<td><em><?php echo T_("Reply to the first email field if exists. Default is admin email.");?></em></td>
				</tr>
				<tr>
					<td><label><?php echo T_("CSS Class");?></label></td>
					<td><input type="text" class="input" name="contactClass" id="contactClass" /></td>
					<td><em><?php echo T_("Additional CSS class for this contact block. Only alphanumeric characters.");?></em></td>
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
							<option value="tm"><?php echo T_("Text email");?></option>
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
		<?php } ?>
			
			<div class="clear"></div>
		</div>
		<?php break;
		// ********************************************************************************************
		case 'save':
		if(!is_dir('../../data/_sdata-'.$sdata.'/'.$Ubusy.'/')) mkdir('../../data/_sdata-'.$sdata.'/'.$Ubusy.'/',0711);
		$a = array(); $c=0;
		if(!$_POST['contactAdmin']) {
			$q1 = @file_get_contents('../../data/_sdata-'.$sdata.'/ssite.json');
			if($q1) {
				$a1 = json_decode($q1,true);
				$a['mail'] = $a1['mel'];
			}
		}
		else $a['mail'] = $_POST['contactAdmin'];
		$a['send'] = (stripslashes($_POST['contactSend'])?stripslashes($_POST['contactSend']):'OK');
		$a['name'] = (!empty($_POST['contactName'])?stripslashes($_POST['contactName']):'No Reply');
		$a['happy'] = stripslashes($_POST['contactHappy']);
		$a['clas'] = preg_replace("/[^a-zA-Z0-9]+/", "", $_POST['contactClass']);
		$a['subject'] = stripslashes($_POST['contactSubject']);
		if($_POST['contactMaster']=="true" && !file_exists('../../data/contactMaster.txt')) file_put_contents('../../data/contactMaster.txt', '1');
		else if($_POST['contactMaster']!="true" && file_exists('../../data/contactMaster.txt')) unlink('../../data/contactMaster.txt');
		if($_POST['contactCaptcha']=="true") $a['captcha']=1; else $a['captcha']=0;
		if($_POST['contactCopy']=="true") $a['copy']=1; else $a['copy']=0;
		if($_POST['contactReply']=="true") $a['reply']=1; else $a['reply']=0;
		$b = array('action','unox','contactAdmin','contactName','contactSend','contactHappy','contactClass','contactCaptcha','contactSubject','contactCopy','contactMaster','contactReply');
		foreach($_POST as $k=>$v) {
			if(!in_array($k,$b)) {
				$a['frm'][$c]['t'] = substr($k,0,2);
				$a['frm'][$c]['l'] = stripslashes(substr($k,2));
			}
			++$c;
		}
		$out = json_encode($a);
		if(file_put_contents('../../data/_sdata-'.$sdata.'/'.$Ubusy.'/contact.json', $out)) echo T_('Backup performed');
		else echo '!'.T_('Impossible backup');
		break;
		// ********************************************************************************************
		case 'load':
		$q = file_get_contents('../../data/_sdata-'.$sdata.'/'.$Ubusy.'/contact.json');
		$a = json_decode($q,true);
		$a['master'] = (file_exists('../../data/contactMaster.txt')?1:0);
		$q = json_encode($a);
		$q = str_replace("\\\\'","'",$q);
		echo $q; exit;
		break;
		// ********************************************************************************************
	}
	clearstatcache();
	exit;
}
?>
