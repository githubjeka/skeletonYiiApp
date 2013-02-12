<h1>Установка завершена</h1>
<div class="progress-bar">
    <div class="bar bg-color-pink" style="width: 30%"></div>
    <div class="bar bg-color-yellow" style="width: 30%"></div>
    <div class="bar bg-color-green" style="width: 40%"></div>
</div>
<div class="body-text">
    <p>Для быстрого ознокомления возможностей CMS используйте следующие данные администратора </p>
    <p>(Мы настоятельно рекомендуем их поменять.): </p>
    <p class="indent">Логин по умолчанию: <strong>admin</strong></p>
    <p class="indent">Пароль по умолчанию: <strong>adminсms</strong></p>
</div>
<?php
echo CHtml::link('Войти',Yii::app()->createUrl('/users'));
?>