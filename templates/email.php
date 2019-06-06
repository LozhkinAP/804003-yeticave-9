        <h1>Поздравляем с победой</h1>
        <p>Здравствуйте, <?php if (isset($userWin['name'])) : echo esc($userWin['name']); endif;?></p>
        <p>Ваша ставка для лота 
        	<a href="lot.php?id=<?php if (isset($lid)) : echo esc($lid); endif;?>">
        		<?php if (isset($lotName['name'])) : echo esc($$lotName['name']); endif;?>
        	</a> победила.</p>
        <p>Перейдите по ссылке 
        	<a href="best.php">Мои ставки</a>,
        чтобы связаться с автором объявления 
    	</p>
        <small>Интернет Аукцион "YetiCave"</small>