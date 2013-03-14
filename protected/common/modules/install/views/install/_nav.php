<?php
/**
 * @var $prev
 * @var $next
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */
?>

<div>

    <?php
    if ($prev === true) :
        echo \CHtml::link(
            '<i class="icon-arrow-left-2" style="font-size: 23px; color: orange;"></i>',
            \Yii::app()->createAbsoluteUrl('/install',array('btn' => 'prev')),
            array('class' => 'button big bg-color-grayDark')
        );
    endif;

    if ($next === true) :
        echo \CHtml::link(
            '<i class="icon-arrow-right-2" style="font-size: 23px; color: orange;"></i>',
            \Yii::app()->createAbsoluteUrl('/install',array('btn' => 'next')),
            array('class' => 'button big bg-color-grayDark')
        );

    endif;
    ?>

</div>