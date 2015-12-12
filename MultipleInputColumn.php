<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace esoftkz\multipleinput;

use yii\base\Model;
use esoftkz\multipleinput\components\BaseColumn;

/**
 * Class MultipleInputColumn
 * @package esoftkz\multipleinput
 */
class MultipleInputColumn extends BaseColumn
{


    /**
     * Returns element's name.
     *
     * @param int|null $index current row index
     * @param bool $withPrefix whether to add prefix.
     * @return string
     */
    public function getElementName($index, $withPrefix = true)
    {
		
        if (is_null($index)) {
            $index = '{multiple_index_in}';			
        }
		
		
		
		$elementName = '[' . $index . '][' . $this->name . ']';
		
			
        $prefix = $withPrefix ? $this->getModel()->formName() : '';
        return  $prefix . $elementName;
    }
	
	 public function getValue($index)
     {
		
        if (is_null($index)) {
            return 0;			
        }else{
			 return  $index;
		}	
     }

	 public function getPhoneId()
     {			
       return $this->getModel()->phone_id;
     }

    

    public function getFirstError($index)
    {
        return $this->getModel()->getFirstError($this->name);
    }
	
	/**
     * Ensure that model is an instance of yii\base\Model.
     *
     * @param $model
     * @return bool
     */
    protected function ensureModel($model)
    {
        return $model instanceof Model;
    }

    /**
     * @inheritdoc
     */
    public function setModel($model)
    {
        $currentModel = $this->getModel();

        // If model is null and current model is not empty it means that widget renders a template
        // In this case we have to unset all model attributes
        if ($model === null && $currentModel !== null) {
            foreach ($currentModel->attributes() as $attribute) {
                $currentModel->$attribute = null;
            }
        } else {
            parent::setModel($model);
        }
    }


}