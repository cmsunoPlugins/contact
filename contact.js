//
// CMSUno
// Plugin Contact
//
function f_contact_load(){
	jQuery(document).ready(function(){
		jQuery.post('uno/plugins/contact/contact.php',{'action':'load','unox':Unox},function(r){
			r=JSON.parse(r);
			if(r.mail)document.getElementById('contactAdmin').value=r.mail;
			if(r.name)document.getElementById('contactName').value=r.name;
			if(r.send)document.getElementById('contactSend').value=r.send;
			if(r.happy)document.getElementById('contactHappy').value=r.happy;
			if(r.clas)document.getElementById('contactClass').value=r.clas;
			if(r.subject)document.getElementById('contactSubject').value=r.subject;
			if(r.captcha==1)document.getElementById('contactCaptcha').checked=true;else document.getElementById('contactCaptcha').checked=false;
			if(r.copy==1)document.getElementById('contactCopy').checked=true;else document.getElementById('contactCopy').checked=false;
			if(r.master==1)document.getElementById('contactMaster').checked=true;else document.getElementById('contactMaster').checked=false;
			if(r.reply==1)document.getElementById('contactReply').checked=true;else document.getElementById('contactReply').checked=false;
			jQuery("#contactResult").empty();
			jQuery.each(r.frm,function(k,v){f_contact_add(v.l,v.t);});
		});
	});
}
function f_contact_save(){
	jQuery(document).ready(function(){
		h=jQuery('#frmContact').serializeArray();
		h.push({name:'action',value:'save'});
		h.push({name:'unox',value:Unox});
		h.push({name:'contactAdmin',value:document.getElementById('contactAdmin').value});
		h.push({name:'contactName',value:document.getElementById('contactName').value});
		h.push({name:'contactSend',value:document.getElementById('contactSend').value});
		h.push({name:'contactHappy',value:document.getElementById('contactHappy').value});
		h.push({name:'contactClass',value:document.getElementById('contactClass').value});
		h.push({name:'contactSubject',value:document.getElementById('contactSubject').value});
		h.push({name:'contactCaptcha',value:document.getElementById('contactCaptcha').checked});
		h.push({name:'contactCopy',value:document.getElementById('contactCopy').checked});
		h.push({name:'contactMaster',value:document.getElementById('contactMaster').checked});
		h.push({name:'contactReply',value:document.getElementById('contactReply').checked});
		jQuery.post('uno/plugins/contact/contact.php',h,function(r){f_alert(r);});
	});
}
function f_contact_add(f,g){
	a=document.getElementById('contactResult');
	b=document.createElement('tr');
	c=document.createElement('td');
	f=f.replace(/_/g,' ');f=f.replace(/\\/g,'');
	c.innerHTML=f;
	b.appendChild(c);
	c=document.createElement('td');
	if(g=='te'){d=document.createElement('input');d.type='text';}
	else if(g=='tm'){d=document.createElement('input');d.type='email';}
	else if(g=='ta')d=document.createElement('textarea');
	d.name=g+f;
	d.style.width='100%';
	c.appendChild(d);
	b.appendChild(c);
	c=document.createElement('td');
	c.style.backgroundImage='url('+Udep+'includes/img/close.png)';
	c.style.backgroundPosition='center center';
	c.style.backgroundRepeat='no-repeat';
	c.style.cursor='pointer';
	c.width='30px';
	c.onclick=function(){this.parentNode.parentNode.removeChild(this.parentNode);}
	b.appendChild(c);
	a.appendChild(b);
	document.getElementById('contactLabel').value='';
}
//
f_contact_load();
