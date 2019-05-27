
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->
        <?php if (isset($category)): ?>
            <?php foreach ($category as $cat): ?>  
                <li class="promo__item promo__item--<?php echo $cat['scode'];?>">
                    <a class="promo__link" href="alllots.php?category=<?php echo $cat['id']; ?>"><?php echo $cat['name'];?></a>
                </li>               
            <?php endforeach; ?>
        <?php endif; ?> 
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
        <?php if (isset($lots)): ?>   
            <?php 
            foreach ($lots as $lot):
                print(include_template('lot_prev.php', ['lot' => $lot]));
            endforeach; 
            ?>
        <?php endif; ?>    
        </ul>
    </section>