//
// CMSUno
// Plugin Contact
//
function f_contact_load(){
	jQuery(document).ready(function(){
		jQuery.post('uno/plugins/contact/contact.php',{'action':'load','unox':Unox},function(r){r=JSON.parse(r);
			if(r.mail)document.getElementById('contactAdmin').value=r.mail;
			if(r.send)document.getElementById('contactSend').value=r.send;
			if(r.happy)document.getElementById('contactHappy').value=r.happy;
			if(r.subject)document.getElementById('contactSubject').value=r.subject;
			if(r.captcha==1)document.getElementById('contactCaptcha').checked=true;else document.getElementById('contactCaptcha').checked=false;
			if(r.copy==1)document.getElementById('contactCopy').checked=true;else document.getElementById('contactCopy').checked=false;
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
		h.push({name:'contactSend',value:document.getElementById('contactSend').value});
		h.push({name:'contactHappy',value:document.getElementById('contactHappy').value});
		h.push({name:'contactSubject',value:document.getElementById('contactSubject').value});
		h.push({name:'contactCaptcha',value:document.getElementById('contactCaptcha').checked});
		h.push({name:'contactCopy',value:document.getElementById('contactCopy').checked});
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
