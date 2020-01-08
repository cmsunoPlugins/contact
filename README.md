CMSUno - Plugins
================

<pre>
 uuuu      uuuu        nnnnnn           ooooooooo
u::::u    u::::u    nn::::::::nn     oo:::::::::::oo
u::::u    u::::u   nn::::::::::nn   o:::::::::::::::o
u::::u    u::::u  n::::::::::::::n  o:::::ooooo:::::o
u::::u    u::::u  n:::::nnnn:::::n  o::::o     o::::o
u::::u    u::::u  n::::n    n::::n  o::::o     o::::o
u::::u    u::::u  n::::n    n::::n  o::::o     o::::o
u:::::uuuu:::::u  n::::n    n::::n  o::::o     o::::o
u::::::::::::::u  n::::n    n::::n  o:::::ooooo:::::o
 u::::::::::::u   n::::n    n::::n  o:::::::::::::::o
  uu::::::::uu    n::::n    n::::n   oo:::::::::::oo
     uuuuuu        nnnn      nnnn       ooooooooo
        ___                                __
       / __\            /\/\              / _\
      / /              /    \             \ \
     / /___           / /\/\ \            _\ \
     \____/           \/    \/            \__/
</pre>

## Contact ##

Allows to create a custom-made contact form with Captcha.
Added with a Shortcode in the content of the page or directly in the template.

[CMSUno](https://github.com/boiteasite/cmsuno)

### PHPMailer ###

If Newsletter plugin exists, PHPMailer is used (recommended). Otherwise, PHP mail function is used.

### Template ###

The default template is uno/template/mailTemplate.php.
You can create a more sophisticate custom template in your template theme folder. The name must be : uno/template/mytheme/contactMailTemplate.php
This contactMailTemplate.php should create the HTML email content : $msgH.

### Versions ###

* 1.4 - 08/01/2020 : Fix PHPMailer issue - Custom template - Copy to sender - Custom subject
* 1.3 - 27/12/2017 : W3.css compatibility
* 1.2.2 - 15/03/2017 : Fix issue when unknow lang
* 1.2.1 - 07/03/2017 : Send with PHPMailer if the Newsletter plugin is installed
* 1.2 - 14/10/2016 : Use PHP-Gettext in place of gettext
* 1.1.1 - 12/03/2016 : Update Simple-php-captcha.
* 1.1 - 21/11/2015 : Remove Table structure.
* 1.0.1 - 05/11/2015 : Fix bugs and auto set default admin email
* 1.0 - 05/10/2015 : First stable version