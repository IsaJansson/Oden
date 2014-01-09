Oden - My PHP based MVC inspired framework 
==========================================

This framework is created by Isa Jansson isa.jansson@hotmail.com as a part of the course "Databas driven web applications with PHP and MVC" at Blekinge Institute of Technology. This framework is based on Lydia wich is created by Mickael Roos - lecturer at BTH. 

Specifications 
--------------
* PHP
* SQLite
* Writable directorys site/data and theme/grid (chmod 777)

Installation
------------
To install this framework you can either clone from GitHub at http://github.com/IsaJansson/Oden or download the zip-file. When the download is complete point your browser to the folder you put it in and follow the instructions. 

Usage
-----
When the installation is done you can start using the framework. There are two users initiated, __root:root__ is the administrator and __doe:doe__ is a regular user. You can create your own user and it will connect your email with gravatar and fetch a profile pricture if you have one. If you don't have one you can create one at http://gravatar.com. 

If you want to know what is included in this framework you can find the ducumentation in the module controller. In 'module/index' you will find a list of all controllers in the sidebar and if you click on them you will se the specific information for each controller and its methods. 

### Create new page
To create a new page you simply go to 'content/create' and set the 'Type' as 'page'. You can choose different filters which affect how the content will be displayed. The 'Key' is what your page will be named in the URL. 
 
### Create a blogpost
To create a blogpost for your new blog you follow the steps of creating a new page only you set type as 'post' instead of 'page'.  

### Changes in the theme
To change logo, title or slogan on your site you go to 'site/config.php' and at the bottom on that file you will find these settings. At the same place you can alter the footer as well. Right above these settings you will find the settings for the menu. There is a base-style in Oden which is based on semantic grid layout. This style is easy to overide in the site-specific stylesheet you will find in 'site/themes/mytheme/style.css'. The colorscheme used as default in Oden is set in this site-specific stylesheet. 

Use of external libraries
-------------------------

### HTML Purifyer
By Edward Z. Yang is used to filter and format HTML. You can read more about what it does on it's website http://htmlpurifyer.org
* Version: 4.6.0
* License: LGPL
* Oden path: 'src/CTextFilter/htmlpurifyer-4.6.0-standalone'
* Used by: 'CTextFilter'

### PHP Markdown Extra
By Michel Fortin is used to filter text to HTML and is an extension to PHP Markdown. If you want to learn more about it visit their website http://michelf.com/projects/php-markdown
* Version: 1.2.8
* License: BSD-style open source license or GNU 
* Oden path: 'src/CTextFilter/PHP-Markdown-Extra-1.2.8'
* Used by: 'CTextfilter'

### PHP SmartyPants & PHP Typographer
By Michel Fortin is used to improve typography on the web. This is based on the concept of Markdown by John Gruber. If you want to learn more about SmartyPants typographer you can do so on their website http://michelf.com/projects/php-smartypants
* Version: 1.0.1
* License: BSD-style open source license
* Oden path: 'src/CTextFilter/PHP-SmartyPants-Typographer-1.0.1'
* Used by: 'CTextFilter'

### Lessphp
By Leaf Corcoran is used to compile LESS into CSS using PHP. Visit their website for more information http://leafo.net/lessphp
* Version: 0.4.0
* License: Dual license, MIT LICENSE & GPL VERSION 3
* Oden path: 'theme/grid/lessphp'
* used by: 'theme/grid/style.php'

### The Semantic Grid System
By Tyler Tate is used to do a grid layout throgh LESS. Learn more on their website http://semantic.gs
* License: APACHE LICENSE 2.0
* Version: 1.2
* Oden path: 'theme/grid/semantic.gs'
* Used by: 'theme/grid/style.less' & 'theme/grid/style'


