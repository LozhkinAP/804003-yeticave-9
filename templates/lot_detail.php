    <nav class="nav">
      <ul class="nav__list container">
        <?php require_once 'list-categories.php'; ?> 

      </ul>
    </nav>
      <section class="lot-item container">
      <h2><?php echo $contentLot['name'];?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="../<?php echo $contentLot['url'];?>" width="730" height="548" alt="Сноуборд">
          </div>
          <p class="lot-item__category">Категория: <span><?php echo $contentLot['category'];?></span></p>
          <p class="lot-item__description">
            <?php echo $contentLot['description'];?>
          </p>
        </div>
        <div class="lot-item__right">

          <?php if (isset($_SESSION['username'])) :?>

          <div class="lot-item__state">
            <div class="lot-item__timer timer <?php echo endSaleTimerHour($contentLot['end_time'])?>">
              <?php echo endLot($contentLot['end_time']);?>
            </div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?php echo $contentLot['rate_price'];?></span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?php echo $contentLot['step_rate'] + $contentLot['rate_price']; ?></span>
              </div>
            </div>
            
            
              <form class="lot-item__form <?php if (isset($errors['cost'])) : echo 'form--invalid'; endif; ?>" action="lot.php?id=<?php echo $contentLot['id'];?>" method="post" autocomplete="off" enctype="multipart/form-data">
                <p class="lot-item__form-item form__item <?php if (isset($errors['cost'])) : echo 'form__item--invalid'; endif; ?>">
                  <label for="cost">Ваша ставка</label>
                  <input id="cost" type="text" name="cost" placeholder="<?php echo $contentLot['step_rate'] + $contentLot['rate_price']; ?>" value="<?php if (isset($add_rate['cost'])) : echo $add_rate['cost']; endif;?>">
                  <span class="form__error"><?php echo $errors['cost']; ?></span>
                </p>
                <button type="submit" class="button">Сделать ставку</button>
              </form>

            
          </div>

          <div class="history">
            <h3>История ставок (<span><?php echo count($rates);?></span>)</h3>
            <table class="history__list">
              <?php foreach($rates as $r): ?>
                <tr class="history__item">
                  <td class="history__name"><?php echo $r['name'];?></td>
                  <td class="history__price"><?php echo $r['rate_price'];?></td>
                  <td class="history__time"><?php echo TimeRate($r['dt_rate']);?></td>
                </tr>
              <?php endforeach; ?>
            </table>
          </div>
          <?php endif; ?>

        </div>
      </div>
      </section>
  
