    <nav class="nav">
      <ul class="nav__list container">
        <?php require_once 'list-categories.php'; ?> 
      </ul>
    </nav>
      <section class="lot-item container">
      <h2><?php if (isset($lotById['name'])) : echo esc($lotById['name']); endif;?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="../<?php if (isset($lotById['url'])) : echo esc($lotById['url']); endif;?>" width="730" height="548" alt="Сноуборд">
          </div>
          <p class="lot-item__category">Категория: <span><?php if (isset($lotById['category'])) : echo esc($lotById['category']); endif;?></span></p>
          <p class="lot-item__description">
            <?php if (isset($lotById['description'])) : echo esc($lotById['description']); endif;?>
          </p>
        </div>
        <div class="lot-item__right">

          <?php if (isset($_SESSION['username'])) :?>

              <div class="lot-item__state">
                <div class="lot-item__timer timer <?php if (isset($lotById['end_time'])) : echo end_sale_timer_hour(esc($lotById['end_time'])); endif;?>">
                  <?php if (isset($lotById['end_time'])) : echo end_lot(esc($lotById['end_time'])); endif;?>
                </div>
                <div class="lot-item__cost-state">
                  <div class="lot-item__rate">
                    <span class="lot-item__amount">Текущая цена</span>
                    <span class="lot-item__cost">
                      <?php if (isset($lotById['rate_price'])) : echo esc($lotById['rate_price']); endif;?>
                    </span>
                  </div>
                  <div class="lot-item__min-cost">
                    Мин. ставка 
                    <span>
                      <?php if ((isset($lotById['rate_price'])) && (isset($lotById['step_rate']))) : echo esc($lotById['step_rate'] + $lotById['rate_price']); endif; ?>
                    </span>
                  </div>
                </div>
                
                <?php if (($_SESSION['userid'] !== $lotById['usercreate_id']) && ($_SESSION['userid'] !== $lastRateUser['user_id'])) :?>

                  <form class="lot-item__form <?php if (isset($errors['rate'])) : echo 'form--invalid'; endif; ?>" action="lot.php?id=<?php echo esc($lotById['id']);?>" method="post" autocomplete="off" enctype="multipart/form-data">
                    <p class="lot-item__form-item form__item <?php if (isset($errors['rate'])) : echo 'form__item--invalid'; endif; ?>">
                      <label for="rate">Ваша ставка</label>
                      <input id="rate" type="text" name="rate" placeholder=" <?php if ((isset($lotById['rate_price'])) && (isset($lotById['step_rate']))) : echo esc($lotById['step_rate'] + $lotById['rate_price']); endif; ?>" value="">
                      <span class="form__error"><?php if (isset($errors['rate'])) : echo esc($errors['rate']); endif; ?></span>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                  </form>

                <?php endif; ?>
                
              </div>

              <div class="history">
                <h3>История ставок (<span><?php if (isset($rates)) : echo count($rates); endif;?></span>)</h3>
                <table class="history__list">
                <?php if (isset($rates)) :?>
                  <?php foreach($rates as $rate): ?>
                    <tr class="history__item">
                      <td class="history__name"><?php if (isset($rate['name'])) : echo esc($rate['name']); endif; ?></td>
                      <td class="history__price"><?php if (isset($rate['rate_price'])) : echo esc($rate['rate_price']); endif; ?></td>
                      <td class="history__time"><?php if (isset($rate['dt_rate'])) : echo time_rate(esc($rate['dt_rate'])); endif; ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif;?>
                </table>
              </div>

          <?php endif; ?>

        </div>
      </div>
      </section>
  
