<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace esoftkz\multipleinput\renderers;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use esoftkz\multipleinput\components\BaseRenderer;
use esoftkz\multipleinput\components\BaseColumn;

/**
 * Class TableRenderer
 * @package esoftkz\multipleinput\renderers
 */ 
 
class TableRenderer2 extends BaseRenderer
{
	
	public $model;
	
	public $index;
    /**
     * @return mixed
     */
    protected function internalRender()
    {
        $content = [];

        $content[] = $this->renderBody();
        $content = Html::tag('table', implode("\n", $content), [
            'class' => 'multiple-input-list-in table table-condensed'
        ]);

        return Html::tag( 'div', $content, [
            'id' => $this->id,
            'class' => 'multiple-input-in'
        ]);
    }

    

    /**
     * Renders the body.
     *
     * @return string
     */
    protected function renderBody()
    {
        $rows = [];
        if (!empty($this->data) ) {
			$sensor = null;
            foreach ($this->data as $index => $item) {
				if($sensor != $item->phone_id){
					$sensor = $item->phone_id;
					$flag_button = true;
				}else{
					$flag_button = false;
				}
				$row = $this->renderRowContent($index, $item, $flag_button);
				if($row != false)
					$rows[] = $row;
				
				if(empty($this->model->id))
					break;
				
            }
        }		
        return Html::tag('tbody', implode("\n", $rows));
    }
 
    private function renderRowContent($index = null, $item = null, $flag_button = false)
    {
				
        $cells = [];
        $hiddenInputs = [];
		$flag = false;
			
		foreach ($this->columns as $column) {	
						
			if($item != null)
				$column->setModel($item);
			
			if(($this->model->id == $column->getPhoneId()) || (empty($this->model->id))  || ($index === null && $item === null)  ){
				if ($column->isHiddenInput()) {
					$hiddenInputs[] = $this->renderCellContent($column, $index);
				} else {
					$cells[] = $this->renderCellContent($column, $index);			
				}
				$flag = true;
			}
			
		}
		if($flag != true)
			return false;
		
		
		if($flag_button == true)	
			$cells[] = $this->renderActionColumn(0);
		else
			$cells[] = $this->renderActionColumn($index);

		if (!empty($hiddenInputs)) {
			$hiddenInputs = implode("\n", $hiddenInputs);
			$cells[0] = preg_replace('/^(<td[^>]+>)(.*)(<\/td>)$/s', '${1}' . $hiddenInputs . '$2$3', $cells[0]);
		}

		$content = Html::tag('tr', implode("\n", $cells), [
			'class' => 'multiple-input-list-in__item',
		]);
		
        return $content;
    }

    /**
     * Renders the cell content.
     *
     * @param BaseColumn $column
     * @param int|null $index
     * @return string
     */
    public function renderCellContent($column, $index)
    {
		
        $id    = $column->getElementId($index);
		
		$name  = $column->getElementName($index);
		
	
		
		if ($column->isHiddenInput()) {	
			if(empty($this->model->id)){
				$input = $column->renderInput($name, [
					'id' => $id,
					'value' => 0
				]);		
			}else{
				$input = $column->renderInput($name, [
					'id' => $id,
					'value' => $this->index
				]);		
			}
			
		}else{
				
			if(!is_null($index) && !empty($this->model->id)){
				$input = $column->renderInput($name, [
					'id' => $id,
					'value' => $column->getModel()->phone
				]);	
				
			}else{			
				$input = $column->renderInput($name, [
					'id' => $id,
					'value' => ''				
				]);
				
			}
				
		}
		
      

        if ($column->isHiddenInput()) {
            return $input;
        }

        $hasError = false;
        if ($column->enableError) {
            $error = $column->getFirstError($index);
            $hasError = !empty($error);
            $input .= "\n" . $column->renderError($error);
        }

        $wrapperOptions = [
            'class' => 'form-group field-' . $id
        ];

        if ($hasError) {
            Html::addCssClass($wrapperOptions, 'has-error');
        }
        $input = Html::tag('div', $input, $wrapperOptions);

        return Html::tag('td', $input, [
            'class' => 'list-cell__' . $column->name,
        ]);
    }


    /**
     * Renders the action column.
     *
     * @param null|int $index
     * @return string
     * @throws \Exception
     */
    private function renderActionColumn($index = null)
    {
        return Html::tag('td', $this->getActionButton($index), [
            'class' => 'list-cell__button',
        ]);
    }

    private function getActionButton($index)
    {
			
        if (is_null($index) || $this->min == 0) {
            return $this->renderRemoveButton();
        }
		
        $index += 1;
        if ($index < $this->min || $index == $this->limit) {
            return '';
        } elseif ($index == $this->min) {
            return $this->addButtonPosition == self::POS_ROW ? $this->renderAddButton() : '';
        } else {
            return $this->renderRemoveButton();
        }
    }

    private function renderAddButton()
    {
        $options = [
            'class' => 'btn multiple-input-list-in__btn js-input-plus-in',
        ];
        Html::addCssClass($options, $this->addButtonOptions['class']);
        return Html::tag('div', $this->addButtonOptions['label'], $options);
    }

    /**
     * Renders remove button.
     *
     * @return string
     * @throws \Exception
     */
    private function renderRemoveButton()
    {
        $options = [
            'class' => 'btn multiple-input-list-in__btn js-input-remove-in',
        ];
        Html::addCssClass($options, $this->removeButtonOptions['class']);
        return Html::tag('div', $this->removeButtonOptions['label'], $options);
    }

    /**
     * Returns template for using in js.
     *
     * @return string
     */
    protected function prepareTemplate()
    {
        return $this->renderRowContent();
    }
}