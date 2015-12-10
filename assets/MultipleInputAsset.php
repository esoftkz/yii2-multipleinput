<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace esoftkz\multipleinput\assets;

use yii\web\AssetBundle;

/**
 * Class MultipleInputAsset
 * @package esoftkz\multipleinput\assets
 */
class MultipleInputAsset extends AssetBundle
{
    public $css = [
        'css/multiple-input.css'
    ];

    public $js = [];

    public $depends = [
        'yii\web\JqueryAsset'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/src/';
        $this->js = [
            YII_DEBUG ? 'js/jquery.multipleInput.js' : 'js/jquery.multipleInput.js'
        ];
        parent::init();
    }


} 