<li class="lots__item lot">
    <div class="lot__image">
        <img src="<?php echo esc($lot['url']);?>" width="350" height="260" alt="">
    </div>
    <div class="lot__info">
        <span class="lot__category"><?php echo esc($lot['category']);?></span>
        <h3 class="lot__title">
            <a class="text-link" href="lot.php?id=<?php echo $lot['id'];?>"><?php echo esc($lot['name']);?>
            </a>
        </h3>
        <div class="lot__state">
            <div class="lot__rate">
                <span class="lot__amount">Стартовая цена </span>
                <span class="lot__cost"><?php echo esc(initPrice($lot['price'])); ?>
            </div>
            <div class="lot__timer timer <?php echo endSaleTimerHour($lot['end_time'])?>">
                <?php echo endSaleTimer($lot['end_time']);?>
            </div>
        </div>
    </div>
</li>