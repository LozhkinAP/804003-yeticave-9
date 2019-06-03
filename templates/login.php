   <nav class="nav">
    <ul class="nav__list container">

        <?php require_once 'list-categories.php'; ?> 

    </ul>
  </nav>

<form class="form container <?php if (isset($errors)) : if (count($errors)) : echo 'form--invalid'; endif; endif; ?>" action="login.php" method="post"> <!-- form--invalid -->
  <h2>Вход</h2>

  <div class="form__item <?php if(isset($errors['email'])) : echo 'form__item--invalid'; endif; ?>"> <!-- form__item--invalid -->
    <label for="email">E-mail <sup>*</sup></label>
    <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?php if(isset($reginfo['email'])) : echo esc($reginfo['email']); endif; ?>">
    <span class="form__error"><?php if(isset($errors['email'])) : echo esc($errors['email']); endif; ?></span><?php if(isset($errors['message'])) : echo esc($errors['message']); endif; ?>
  </div>

  <div class="form__item form__item--last <?php if(isset($errors['password'])) : echo 'form__item--invalid'; endif; ?>">
    <label for="password">Пароль <sup>*</sup></label>
    <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?php if(isset($loginInfo['password'])) : echo esc($loginInfo['password']); endif; ?>">
    <span class="form__error"><?php if(isset($errors['password'])) : echo esc($errors['password']); endif; ?></span>
  </div>

  <button type="submit" class="button">Войти</button>
</form>