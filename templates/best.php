    <nav class="nav">
      <ul class="nav__list container">

        <?php foreach ($category as $cat): ?> 
          <li class="nav__item">
            <a href="all-lots.html"><?php echo $cat['name'];?></a>
          </li>
        <?php endforeach; ?>

      </ul>
    </nav>
    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
        <?php foreach ($best as $b): ?> 
            <tr class="rates__item">
              <td class="rates__info">
                <div class="rates__img">
                  <img src="<?php echo $b['url']; ?>" width="54" height="40" alt="Сноуборд">
                </div>
                <h3 class="rates__title"><a href="lot.php?id=<?php echo $b['id']; ?>"><?php echo $b['name']; ?></a></h3>
              </td>
              <td class="rates__category">
                <?php echo $b['category']; ?>
              </td>
              <td class="rates__timer">
                <div class="timer <?php echo endSaleTimerHour($b['end_time']); ?>"><?php echo endSaleTimer($b['end_time']); ?></div>
              </td>
              <td class="rates__price">
                <?php echo $b['rate_price']; ?>
              </td>
              <td class="rates__time">
                <?php echo TimeRate($b['dt']);?>
              </td>
            </tr>
         
        <?php endforeach; ?>
      </table>
    </section>