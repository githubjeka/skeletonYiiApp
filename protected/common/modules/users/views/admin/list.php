<?php

/** @var $model users\models\User */

echo CHtml::link(Yii::t('users','Create user'),array('/users/admin/create'));

$this->widget(
    'zii.widgets.grid.CGridView',
    array(
        'dataProvider' => $model->search(),
        'filter' => $model,
        'columns' => array(
            'id',
            'username',
            'email',
            'create_time',
            'login_time',
            array(
                'class' => 'CButtonColumn',
                'template' => '{delete}',
            ),
        ),
    )
);

