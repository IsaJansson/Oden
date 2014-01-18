<h1>Index Controller</h1>
<p>Welcome to Oden index controller.</p>

<h2>Download</h2>
<p>You can download Oden from <a href='https://github.com/IsaJansson/Oden'>GitHub</a>, 
or you can clone it using the link below.</p>
<blockquote>
<code>git clone git://github.com/IsaJansson/Oden.git</code>
</blockquote>


<h2>Installation</h2>
<p>First if you haven't already made the data-directory writable you have to do that now. 
The data-directory is the place where Oden needs to be able to write and create files.</p>
<blockquote>
<code>cd Oden; chmod 777 site/data</code> <br />
</blockquote>

<p>Second, sometimes you need a rewrite base which you set up in the .htaccess file. 
The code you need to change can look like this:</p>
<blockquote>
	<code>RewriteBase /~isja13/phpmvc/me/projekt/oden/</code>
</blockquote> 
<p>You need to change this to your own path and if you dosent need it you simply put a # in front of that row. </p>

<p>If the obove steps are done Oden has some modules that need to be initialised before you can build your site. 
This will be done by clicking on the link below.</p>
<blockquote>
<a href='<?=create_url('module/install')?>'>Install Oden</a>
</blockquote>

