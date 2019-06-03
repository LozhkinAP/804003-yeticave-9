    <nav class="nav">
      <ul class="nav__list container">
        <?php require_once 'list-categories.php'; ?> 
      </ul>
    </nav>
      <section class="lot-item container">
      <h2><?php echo esc($lotById['name']);?><?php if (isset($lotById['name'])) : echo esc($lotById['name']); endif;?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="../<?php echo esc($lotById['url']);?>" width="730" height="548" alt="Сноуборд">
          </div>
          <p class="lot-item__category">Категория: <span><?php echo esc($lotById['category']);?></span></p>
          <p class="lot-item__description">
            <?php echo esc($lotById['description']);?>
          </p>
        </div>
        <div class="lot-item__right">

          <?php if (isset($_SESSION['username'])) :?>

              <div class="lot-item__state">
                <div class="lot-item__timer timer <?php echo end_sale_timer_hour(esc($lotById['end_time']))?>">
                  <?php echo end_lot(esc($lotById['end_time']));?>
                </div>
                <div class="lot-item__cost-state">
                  <div class="lot-item__rate">
                    <span class="lot-item__amount">Текущая цена</span>
                    <span class="lot-item__cost"><?php echo esc($lotById['rate_price']);?></span>
                  </div>
                  <div class="lot-item__min-cost">
                    Мин. ставка <span><?php echo $lotById['step_rate'] + $lotById['rate_price']; ?></span>
                  </div>
                </div>
                
                <?php if (($_SESSION['userid'] !== $lotById['usercreate_id']) && ($_SESSION['userid'] !== $lastRateUser['user_id'])) :?>

                  <form class="lot-item__form <?php if (isset($errors['rate'])) : echo 'form--invalid'; endif; ?>" action="lot.php?id=<?php echo esc($lotById['id']);?>" method="post" autocomplete="off" enctype="multipart/form-data">
                    <p class="lot-item__form-item form__item <?php if (isset($errors['rate'])) : echo 'form__item--invalid'; endif; ?>">
                      <label for="rate">Ваша ставка</label>
                      <input id="rate" type="text" name="rate" placeholder="<?php echo esc($lotById['step_rate'] + $lotById['rate_price']); ?>" value="">
                      <span class="form__error"><?php if (isset($errors['rate'])) : echo esc($errors['rate']); endif; ?></span>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                  </form>

                <?php endif; ?>
                
              </div>

              <div class="history">
                <h3>История ставок (<span><?php echo count($rates);?></span>)</h3>
                <table class="history__list">
                  <?php foreach($rates as $rate): ?>
                    <tr class="history__item">
                      <td class="history__name"><?php echo esc($rate['name']);?></td>
                      <td class="history__price"><?php echo esc($rate['rate_price']);?></td>
                      <td class="history__time"><?php echo time_rate(esc($rate['dt_rate']));?></td>
                    </tr>
                  <?php endforeach; ?>
                </table>
              </div>

          <?php endif; ?>

        </div>
      </div>
      </section>
  
