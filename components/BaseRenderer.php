<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace esoftkz\multipleinput\components;

use Yii;
use yii\helpers\Html;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\base\Object;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\web\View;
use esoftkz\multipleinput\MultipleInput;
use esoftkz\multipleinput\TabularInput;
use esoftkz\multipleinput\assets\MultipleInputAsset;

/**
 * Class BaseRenderer
 * @package esoftkz\multipleinput\components
 */
abstract class BaseRenderer extends Object
{
    const POS_HEADER    = 0;
    const POS_ROW       = 1;

    /**
     * @var
     */
    public $id;

    /**
     * @var ActiveRecord[]|Model[]|array[] input data
     */
    public $data = null;

    /**
     * @var array
     */
    public $columns = [];

    /**
     * @var int inputs limit
     */
    public $limit;

    /**
     * @var int minimum number of rows.
     * @since 1.2.6 Use this option with value 0 instead of `allowEmptyList` with `true` value
     */
    public $min;

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
     * @var string
     */
    public $columnClass;

	

    /**
     * @var TabularInput|MultipleInput
     */
    protected $context;

    /**
     * @var string position of add button. By default button is rendered in the row.
     */
    public $addButtonPosition = self::POS_ROW;

	public $in = false;
	
    /**
     * @param $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    public function init()
    {
        parent::init();

        $this->prepareMinOption();
        $this->prepareLimit();
        $this->prepareColumnClass();
        $this->prepareButtonsOptions();
    }

    private function prepareColumnClass()
    {
        if (empty($this->columnClass)) {
            throw new InvalidConfigException('You must specify "columnClass"');
        }

        if (!class_exists($this->columnClass)) {
            throw new InvalidConfigException('Column class "' . $this->columnClass. '" does not exist');
        }
    }

    private function prepareMinOption()
    {
        // Set value of min option based on value of allowEmptyList for BC
        if (is_null($this->min)) {
            $this->min = $this->allowEmptyList ? 0 : 1;
        } else {
            if ($this->min < 0) {
                throw new InvalidConfigException('Option "min" cannot be less 0');
            }

            // Allow empty list in case when minimum number of rows equal 0.
            if ($this->min == 0 && !$this->allowEmptyList) {
                $this->allowEmptyList = true;
            }

            // Deny empty list in case when min number of rows greater then 0
            if ($this->min > 0 && $this->allowEmptyList) {
                $this->allowEmptyList = false;
            }
        }
    }

    private function prepareLimit()
    {
        if (is_null($this->limit)) {
            $this->limit = 999;
        }

        if ($this->limit < 1) {
            $this->limit = 1;
        }

        // Maximum number of rows cannot be less then minimum number.
        if (!is_null($this->limit) && $this->limit < $this->min) {
            $this->limit = $this->min;
        }
    }

    private function prepareButtonsOptions()
    {
        if (!isset($this->removeButtonOptions['class'])) {
            $this->removeButtonOptions['class'] = 'btn btn-danger';
        }

        if (!isset($this->removeButtonOptions['label'])) {
            $this->removeButtonOptions['label'] = Html::tag('i', null, ['class' => 'glyphicon glyphicon-remove']);
        }

        if (!isset($this->addButtonOptions['class'])) {
            $this->addButtonOptions['class'] = 'btn btn-default';
        }

        if (!isset($this->addButtonOptions['label'])) {
            $this->addButtonOptions['label'] = Html::tag('i', null, ['class' => 'glyphicon glyphicon-plus']);
        }
    }


    /**
     * Creates column objects and initializes them.
     */
    protected function initColumns()
    {
        foreach ($this->columns as $i => $column) {
            $definition = array_merge([
                'class' => $this->columnClass,
                'renderer' => $this
            ], $column);

          
            $column = Yii::createObject($definition);
            $this->columns[$i] = $column;
        }
	
		
    }

    public function render()
    {
        $this->initColumns();
        $content = $this->internalRender();
<<<<<<< HEAD
		
		$this->registerClientScript();
	
=======
		if($this->in !== true)
			$this->registerClientScript();
		else
			$this->registerClientScript2();
>>>>>>> origin/master
		
        return $content;
    }

    /**
     * @return mixed
     * @throws NotSupportedException
     */
    abstract protected function internalRender();

    /**
     * Register script.
     *
     */
    protected function registerClientScript()
    {
<<<<<<< HEAD
        $view = $this->context->getView();		
		MultipleInputAsset::register($view);
		
=======
        $view = $this->context->getView();
		
		MultipleInputAsset::register($view);

>>>>>>> origin/master
        $jsBefore = $this->collectJsTemplates();
		
        $template = $this->prepareTemplate();
		$template = str_replace(['ObjectsPhonesItems[0]'], "ObjectsPhonesItems[{multiple_index_in}]", $template);
		$template = str_replace(['objectsphonesitems-0-phone'], "objectsphonesitems-{multiple_index_in}-phone", $template);
		
<<<<<<< HEAD
		

=======
	
>>>>>>> origin/master
        $jsTemplates = $this->collectJsTemplates($jsBefore);
		if($this->in != true)
			$jsTemplates2 = $this->collectJsTemplates2($jsBefore);
		

        $options = Json::encode(
            [
                'id'                => $this->id,
                'template'          => $template,
                'jsTemplates'       => $jsTemplates,
				'jsTemplates2'       => $jsTemplates2,
                'limit'             => $this->limit,
                'min'               => $this->min,
                'attributeOptions'  => $this->attributeOptions,
				'in_flag' => $this->in == true ? true : false
            ]
        );

        $js = "jQuery('#{$this->id}').multipleInput($options);";
        $view->registerJs($js);
    }
	
	protected function registerClientScript2()
    {
        $view = $this->context->getView();
		
        $template = $this->prepareTemplate();
		$template = str_replace(['ObjectsPhonesItems[0]'], "ObjectsPhonesItems[{multiple_index_in}]", $template);
		$template = str_replace(['objectsphonesitems-0-phone'], "objectsphonesitems-{multiple_index_in}-phone", $template);
		$template= Json::encode($template);
	

        $js = "jQuery('#{$this->id}').data('template', '{$template}');";
        $view->registerJs($js);
    }

    /**
     * @return string
     */
    abstract protected function prepareTemplate();


    protected function collectJsTemplates($except = [])
    {
        $view = $this->context->getView();
        $output = [];
        if (is_array($view->js) && array_key_exists(View::POS_READY, $view->js)) {
            foreach ($view->js[View::POS_READY] as $key => $js) {
                if (array_key_exists($key, $except)) {
                    continue;
                }
				
                if (preg_match('/^[^{]+{multiple_index}.*$/m', $js) === 1) {
                    $output[$key] = $js;
                    unset($view->js[View::POS_READY][$key]);
                }
            }
        }
        return $output;
    }
	
	protected function collectJsTemplates2($except = [])
    {
		$view = $this->context->getView();
        $output = [];
        if (is_array($view->js) && array_key_exists(View::POS_READY, $view->js)) {
            foreach ($view->js[View::POS_READY] as $key => $js) {
                if (array_key_exists($key, $except)) {
                    continue;
                }
				
				if (preg_match('/^[^{]+{multiple_index_in}.*$/m', $js) === 1 ){
					$output[$key] = $js; 
					unset($view->js[View::POS_READY][$key]);
				}
				
				
            }
        }
        return $output;
		
	}
}