<?php if(isset($category)): ?> 
	<?php foreach ($category as $cat): ?> 
		<li class="nav__item">
			<a href="alllots.php?category=<?php if(isset($cat['id'])) : echo esc($cat['id']); endif;?>">
				<?php if(isset($cat['name'])) : echo esc($cat['name']); endif;?>
			</a>
		</li>
	<?php endforeach; ?>
<?php endif; ?>