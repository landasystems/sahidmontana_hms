<?php

Yii::import('gii.generators.crud.CrudGenerator');

class BootstrapGenerator extends CrudGenerator
{
	public $codeModel = 'common.extensions.giiplus.bootstrap.BootstrapCode';
}
