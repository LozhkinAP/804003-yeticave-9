        <?php foreach ($category as $cat): ?> 
          <li class="nav__item">
            <a href="alllots.php?category=<?php echo $cat['id']; ?>"><?php echo $cat['name'];?></a>
          </li>
        <?php endforeach; ?>