
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->

            <?php foreach ($category as $cat): ?>  
                <li class="promo__item promo__item--<?php echo $cat['scode'];?>">
                    <a class="promo__link" href="pages/all-lots.html"><?php echo $cat['name'];?></a>
                </li>               
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            
            <?php 
            foreach ($lots as $lot):
                print(include_template('lotprev.php', ['lot' => $lot]));
            endforeach; 
            ?>
            
        </ul>
    </section>