<?php if ($pages_count > 1): ?>		
	<?php foreach ($pages as $page): ?>
		<li class="pagination-item <?php if ($page == $cur_page): ?>pagination-item-active<?php endif; ?>">
			<?php if(substr_count($_SERVER['REQUEST_URI'], 'alllots')):	?>
				<a href="alllots.php?category=<?php echo $categoryById['id']?>&page=<?php echo $page;?>"><?=$page;?></a>
			<?php endif; ?>
			<?php if(substr_count($_SERVER['REQUEST_URI'], 'search')):	?>
				<a href="search.php?search=<?php echo $search; ?>&find='Найти'&page=<?php echo $page;?>"><?=$page;?></a>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>	
<?php endif; ?>


