<nav class="nav">
  <ul class="nav__list container">
    <?php require_once 'list-categories.php'; ?> 
  </ul>
</nav>
<div class="container">
  <section class="lots">
    <h2>Результаты поиска по запросу «<span><?php if(isset($search)) : echo esc($search); endif;?></span>»</h2>
    <ul class="lots__list">
      <?php if(isset($lots)): ?>   
        <?php 
        foreach ($lots as $lot):
          print(include_template('lot_prev.php', ['lot' => $lot]));
        endforeach; 
        ?>
      <?php endif; ?> 
    </ul>
  </section>
  <?php if(isset($pages_count)): ?>
    <?php if($pages_count > 1): ?>
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
          <a href="search.php?search=<?php if(isset($search)) : echo esc($search); endif;?>&find='Найти'&page=<?php if (isset($cur_page)): ?><?php if(($cur_page-1) > 0) {echo $cur_page-1;} else { echo 1; }?><?php endif; ?>">Назад
          </a>
        </li>      
            <?php print(include_template('pagination.php', [
              'pages' => $pages, 
              'pages_count' => $pages_count, 
              'cur_page' => $cur_page,
              'search' =>  $search
            ])); ?>
        <li class="pagination-item pagination-item-next">
          <a href="search.php?search=<?php if(isset($search)) : echo esc($search); endif;?>&find='Найти'&page=<?php if (isset($cur_page)): ?><?php if(($cur_page+1) > $pages_count) {echo $cur_page;} else { echo $cur_page+1; }?><?php endif; ?>">Вперед
          </a>
        </li>
      </ul>
    <?php endif; ?>
  <?php endif; ?>
</div>