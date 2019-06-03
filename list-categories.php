<?php if(isset($category)): ?> 
	<?php foreach ($category as $cat): ?> 
		<li class="nav__item">
			<a href="alllots.php?category=<?php echo esc($cat['id']); ?>"><?php echo esc($cat['name']);?></a>
		</li>
	<?php endforeach; ?>
<?php endif; ?>