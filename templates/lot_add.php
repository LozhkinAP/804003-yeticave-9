    <nav class="nav">
      <ul class="nav__list container">
          <?php require_once 'list-categories.php'; ?> 
      </ul>
    </nav>
    <form class="form form--add-lot container <?php if (isset($errors)) : if (count($errors)) : echo 'form--invalid'; endif; endif; ?>" action="add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
      <h2>Добавление лота</h2>
      <div class="form__container-two">
        <div class="form__item <?php if (isset($errors['lot-name'])) : echo 'form__item--invalid'; endif; ?>"> <!-- form__item--invalid -->
          <label for="lot-name">Наименование <sup>*</sup></label>
          <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?php if (isset($newLot['lot-name'])) : echo esc($newLot['lot-name']); endif;?>">
          <span class="form__error"><?php if (isset($errors['lot-name'])) : echo esc($errors['lot-name']); endif; ?></span>
        </div>
        <div class="form__item <?php if (isset($errors['category'])) : echo 'form__item--invalid'; endif; ?>">
          <label for="category">Категория <sup>*</sup></label>
          <select id="category" name="category">
            <option>Выберите категорию</option>
            <?php if(isset($category)): ?> 
                <?php foreach ($category as $cat): ?> 
                <option value="<?php echo $cat['name']; ?>"
                  <?php if (isset($_POST['category']) && isset($cat['name']) && ($cat['name'] === $_POST['category'])): ?>selected="selected"<?php endif; ?>>                 
                    <?php if (isset($cat['name'])) : echo esc($cat['name']); endif; ?>
                </option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
          <span class="form__error"><?php if (isset($errors['category'])) : echo esc($errors['category']); endif; ?></span>
        </div>
      </div>
      <div class="form__item form__item--wide <?php if (isset($errors['message'])) : echo 'form__item--invalid'; endif; ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите описание лота" value=""><?php if (isset($newLot['message'])) : echo esc($newLot['message']); endif;?></textarea>
        <span class="form__error"><?php if (isset($errors['message'])) : echo esc($errors['message']); endif; ?></span>
      </div>
      
      <div class="form__item form__item--file <?php if (isset($errors['file'])) : echo 'form__item--invalid'; endif; ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
          <input class="visually-hidden" type="file" id="lot-img" name="img" value="" >
          <label for="lot-img">
            Добавить
          </label>
        </div>
        <span class="form__error"><?php if (isset($errors['file'])) : echo esc($errors['file']); endif; ?></span>
      </div>

      <div class="form__container-three">
        <div class="form__item form__item--small <?php if (isset($errors['lot-rate'])) : echo 'form__item--invalid'; endif; ?>">
          <label for="lot-rate">Начальная цена <sup>*</sup></label>
          <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?php if (isset($newLot['lot-rate'])) : echo esc($newLot['lot-rate']); endif;?>">
          <span class="form__error">
            <?php if (isset($errors['lot-rate'])) : echo esc($errors['lot-rate']); endif; ?>
          </span>
        </div>
        <div class="form__item form__item--small <?php if (isset($errors['lot-step'])) : echo 'form__item--invalid'; endif; ?>">
          <label for="lot-step">Шаг ставки 
            <sup>*</sup>
          </label>
          <input id="lot-step" type="text" name="lot-step" placeholder="0"  value="<?php if (isset($newLot['lot-step'])) : echo esc($newLot['lot-step']); endif;?>">
          <span class="form__error">
            <?php if (isset($errors['lot-step'])) : echo esc($errors['lot-step']); endif; ?>
          </span>
        </div>
        <div class="form__item <?php if (isset($errors['lot-date'])) : echo 'form__item--invalid'; endif; ?>">
          <label for="lot-date">Дата окончания торгов 
            <sup>*</sup>
          </label>
          <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?php if (isset($newLot['lot-date'])) : echo esc($newLot['lot-date']); endif;?>">
          <span class="form__error">
            <?php if (isset($errors['lot-date'])) : echo esc($errors['lot-date']); endif; ?>
          </span>
        </div>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Добавить лот</button>
    </form>

  