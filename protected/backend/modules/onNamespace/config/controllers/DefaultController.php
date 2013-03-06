<?php
namespace config\controllers;

class DefaultController extends \Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
}