<?php if ($pages_count > 1): ?>		
	<?php foreach ($pages as $page): ?>
		<li class="pagination-item <?php if ($page === $cur_page): ?>pagination-item-active<?php endif; ?>">
			<?php if(substr_count($_SERVER['REQUEST_URI'], 'alllots')):	?>
				<a href="alllots.php?category=<?php if (isset($categoryById['id'])) : echo esc($categoryById['id']); endif;?>&page=<?php echo esc($page);?>">
					<?php echo esc($page);?>
				</a>
			<?php endif; ?>
			<?php if(substr_count($_SERVER['REQUEST_URI'], 'search')):	?>
				<a href="search.php?search=<?php echo esc($search); ?>&find='Найти'&page=<?php echo esc($page);?>">
					<?php echo esc($page);?>
				</a>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>	
<?php endif; ?>


