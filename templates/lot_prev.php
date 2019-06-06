<li class="lots__item lot">
    <div class="lot__image">
        <img src="<?php if (isset($lot['url'])) : echo esc($lot['url']); endif;?>" width="350" height="260" alt="">
    </div>
    <div class="lot__info">
        <span class="lot__category">
            <?php if (isset($lot['category'])) : echo esc($lot['category']); endif;?>
    
        </span>
        <h3 class="lot__title">
            <a class="text-link" href="lot.php?id=<?php if (isset($lot['id'])) : echo esc($lot['id']); endif;?>">
                <?php if (isset($lot['name'])) : echo esc($lot['name']); endif;?>
            </a>
        </h3>
        <div class="lot__state">
            <div class="lot__rate">
                <span class="lot__amount">Стартовая цена </span>
                <span class="lot__cost">
                    <?php if (isset($lot['price'])) : echo esc($lot['price']); endif;?>
                </span>
            </div>
            <div class="lot__timer timer <?php if (isset($lot['end_time'])) : echo end_sale_timer_hour(esc($lot['end_time'])); endif;?>">
                <?php if (isset($lot['end_time'])) : echo end_lot(esc($lot['end_time'])); endif;?>
            </div>
        </div>
    </div>
</li>