    <nav class="nav">
      <ul class="nav__list container">

        <?php require_once 'list-categories.php'; ?> 

      </ul>
    </nav>
    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
      <?php if(isset($best)): ?>  
        <?php foreach ($best as $bests): ?> 
            <tr class="rates__item">
              <td class="rates__info">
                <div class="rates__img">
                  <img src="<?php if (isset($bests['url'])) : echo esc($bests['url']); endif;?>" width="54" height="40" alt="Сноуборд">
                </div>
                <h3 class="rates__title">
                  <a href="lot.php?id=<?php if (isset($bests['id'])) : echo esc($bests['id']); endif;?>"> 
                    <?php if (isset($bests['name'])) : echo esc($bests['name']); endif;?>                    
                  </a>
                </h3>
              </td>
              <td class="rates__category">
                <?php if (isset($bests['category'])) : echo esc($bests['category']); endif;?>
              </td>
              <td class="rates__timer">
                <div class="timer <?php if (isset($bests['end_time'])) : echo end_sale_timer_hour(esc($bests['end_time'])); endif;?>">
                  <?php if (isset($bests['end_time'])) : echo end_lot(esc($bests['end_time'])); endif;?>
                </div>
              </td>
              <td class="rates__price">
                <?php if (isset($bests['rate_price'])) : echo esc($bests['rate_price']); endif;?>
              </td>
              <td class="rates__time">
                <?php if (isset($bests['dt'])) : echo time_rate(esc($bests['dt'])); endif;?>
              </td>
            </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </table>
    </section>