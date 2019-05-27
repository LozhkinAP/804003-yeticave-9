
<div class="container">
	<?php
	if (!empty($error)) {
		$error = ' Ошибка: '.$error;
	} else {
		$error = '';
	}
	echo $text.$error;
	?>
</div>
