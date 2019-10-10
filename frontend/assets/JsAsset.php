<?php


namespace frontend\assets;


use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class JsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/site.css',
    ];
    public $js = [
        'js/cl_form_script.js',
        //"js/alert.js"
    ];

    public $depends = [
        JqueryAsset::class,
        AppAsset::class,
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',

    ];

}