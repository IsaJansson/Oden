<div class='box'>
<h4>All Modules</h4>
	<ul>
		<?php foreach($modules as $module): ?>
			<li><?=$module['name']?></li>
		<?php endforeach; ?>
	</ul>
</div>

<div class='box'>
	<h4>Oden Core</h4>
	<ul>
		<?php foreach($modules as $module): ?>
			<?php if($module['isOdenCore']): ?>
				<li><?=$module['name']?></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>

<div class='box'>
	<h4>Oden CMF</h4>
	<ul>
		<?php foreach($modules as $module): ?>
			<?php if($module['isOdenCMF']): ?>
				<li><?=$module['name']?></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>

<div class='box'>
	<h4>Models</h4>
	<p>A class is considered a model if its name starts with CM.</p>
	<ul>
		<?php foreach($modules as $module): ?>
			  <?php if($module['isModel']): ?>
			  	<li><?=$module['name']?></li>
			  <?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>

<div class='box'>
<h4>Controllers</h4>
<p>Implements interface <code>IController</code>.</p>
	<ul>
		<?php foreach($modules as $module): ?>
		  <?php if($module['isController']): ?>
		  	<li><?=$module['name']?></li>
		  <?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>


<div class='box'>
<h4>Contains SQL</h4>
<p>Implements interface <code>IHasSQL</code>.</p>
	<ul>
		<?php foreach($modules as $module): ?>
		  <?php if($module['hasSQL']): ?>
		  	<li><?=$module['name']?></li>
		  <?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>

<div class='box'>
<h4>More modules</h4>
<p>Modules that does not implement any specific Lydia interface.</p>
	<ul>
		<?php foreach($modules as $module): ?>
		  <?php if(!($module['isController'] || $module['isOdenCore'] || $module['isOdenCMF'])): ?>
		  	<li><?=$module['name']?></li>
		  <?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>