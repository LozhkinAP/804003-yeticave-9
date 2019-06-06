   <nav class="nav">
    <ul class="nav__list container">

        <?php require_once 'list-categories.php'; ?> 

    </ul>
  </nav>

  <form class="form container <?php if (isset($errors)) : if (count($errors)) : echo 'form--invalid'; endif; endif; ?>" action="registration.php" method="post" autocomplete="off" enctype="multipart/form-data"> <!-- form--invalid -->

    <h2>Регистрация нового аккаунта</h2>

    <div class="form__item <?php if(isset($errors['email'])) : echo 'form__item--invalid'; endif; ?>"> <!-- form__item--invalid -->
      <label for="email">E-mail <sup>*</sup></label>
      <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?php if(isset($reginfo['email'])) : echo esc($reginfo['email']); endif;?>">
      <span class="form__error"><?php if(isset($errors['email'])) : echo esc($errors['email']); endif; ?></span>
    </div>

    <div class="form__item <?php if(isset($errors['password'])) : echo 'form__item--invalid'; endif; ?>">
      <label for="password">Пароль <sup>*</sup></label>
      <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?php if(isset($reginfo['password'])) : echo esc($reginfo['password']); endif;?>">
      <span class="form__error"><?php if(isset($errors['password'])) : echo esc($errors['password']); endif; ?></span>
    </div>

    <div class="form__item <?php if(isset($errors['name'])) : echo 'form__item--invalid'; endif; ?>">
      <label for="name">Имя <sup>*</sup></label>
      <input id="name" type="text" name="name" placeholder="Введите имя" value="<?php if(isset($reginfo['name'])) : echo esc($reginfo['name']); endif;?>">
      <span class="form__error"><?php if(isset($errors['name'])) : echo esc($errors['name']); endif; ?></span>
    </div>

    <div class="form__item form__item--file <?php if(isset($errors['file'])) : echo 'form__item--invalid'; endif; ?>">
      <label>Аватар <sup>*</sup></label>
      <div class="form__input-file">
        <input class="visually-hidden" type="file" id="ava-img" value="" name="img">
        <label for="ava-img">
          Добавить
        </label>
      </div>
      <span class="form__error"><?php if(isset($errors['file'])) : echo esc($errors['file']); endif; ?></span>
    </div>


    <div class="form__item <?php if(isset($errors['message'])) : echo 'form__item--invalid'; endif; ?>">
      <label for="message">Контактные данные <sup>*</sup></label>
      <textarea id="message" name="message" placeholder="Напишите как с вами связаться" ><?php if(isset($reginfo['message'])) : echo esc($reginfo['message']); endif;?></textarea>
      <span class="form__error"><?php if(isset($errors['message'])) : echo esc($errors['message']); endif; ?></span>
    </div>

    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>

    <a class="text-link" href="login.php">Уже есть аккаунт</a>

  </form>


