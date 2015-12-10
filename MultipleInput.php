<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace esoftkz\multipleinput;

use Yii;
use yii\base\Model;
use yii\widgets\InputWidget;
use yii\bootstrap\Widget;
use yii\db\ActiveRecord;
use esoftkz\multipleinput\renderers\TableRenderer2;


/**
 * Widget for rendering multiple input for an attribute of model.
 *
 * @author Eugene Tupikov <unclead.nsk@gmail.com>
 */
class MultipleInput extends InputWidget
{
    const POS_HEADER    = 0;
    const POS_ROW       = 1;

    /**
     * @var Model[]|ActiveRecord[]
     */
    public $models;

    /**
     * @var array columns configuration
     */
    public $columns = [];

    /**
     * @var integer inputs limit
     */
    public $limit;

    /**
     * @var array client-side attribute options, e.g. enableAjaxValidation. You may use this property in case when
     * you use widget without a model, since in this case widget is not able to detect client-side options
     * automatically.
     */
    public $attributeOptions = [];

    /**
     * @var array the HTML options for the `remove` button
     */
    public $removeButtonOptions;

    /**
     * @var array the HTML options for the `add` button
     */
    public $addButtonOptions;

    /**
     * @var bool whether to allow the empty list
     */
    public $allowEmptyList = false;

    /**
     * @var int minimum number of rows
     */
    public $min;

    /**
     * @var string position of add button. By default button is rendered in the row.
     */
    public $addButtonPosition = self::POS_ROW;


   

	
	/**
     * Initialization.
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
       
        parent::init();
    }

    
    
    
    /**
     * Run widget.
     */
    public function run()
    {
        return $this->createRenderer();
    }

    /**
     * @return TableRenderer
     */
    private function createRenderer()
    {
		
        $config = [
            'id'                => $this->options['id'],
            'columns'           => $this->columns,
            'data'              => $this->models,
            'columnClass'       => MultipleInputColumn::className(),
			'model' => $this->model,
			'model123' => $this->model,
			'min'               => $this->min,
			'addButtonPosition' => $this->addButtonPosition,
			'limit'             => $this->limit,
			'attributeOptions'  => $this->attributeOptions,

			'in' => true,
            'context'           => $this
        ];
		$render = new TableRenderer2($config);
		
		return $render->render();

       
    }
}