<?php if (isset($pages_count)) : ?>
	<?php if ($pages_count > 1): ?>		
		<?php foreach ($pages as $page): ?>
			<li class="pagination-item <?php if (isset($cur_page) && isset($page)) : ?><?php if ((int)$page === (int)$cur_page): ?> pagination-item-active<?php endif; ?><?php endif; ?>">
				<?php if(substr_count($_SERVER['REQUEST_URI'], 'alllots')):	?>
					<a href="alllots.php?category=<?php if (isset($categoryById['id'])) : echo esc($categoryById['id']); endif;?>&page=<?php if (isset($page)) : echo esc($page); endif;?>">
						<?php if (isset($page)) : echo esc($page); endif;?>
					</a>
				<?php endif; ?>
				<?php if(substr_count($_SERVER['REQUEST_URI'], 'search')):	?>
					<a href="search.php?search=<?php if (isset($search)) : echo esc($search); endif;?>&find='Найти'&page=<?php if (isset($page)) : echo esc($page); endif;?>">
						<?php if (isset($page)) : echo esc($page); endif;?>
					</a>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>	
	<?php endif; ?>
<?php endif; ?>


