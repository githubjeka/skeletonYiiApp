<?php
/**
 * ConfigController file.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

namespace composer\models;

class UploadForm extends \CFormModel
{
    public $archive;

     public function rules(){
        return array(
            array('archive', 'file', 'types'=>'zip','on'=>'uploadModule'),
        );
    }
}