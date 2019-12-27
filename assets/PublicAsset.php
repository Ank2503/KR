<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class PublicAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    "markup/public/css/bootstrap.min.css",
    "markup/public/css/font-awesome.min.css",
    "markup/public/css/animate.min.css",
    "markup/public/css/owl.carousel.css",
    "markup/public/css/owl.theme.css",
    "markup/public/css/owl.transitions.css",
    "markup/public/css/style.css",
    "markup/public/css/responsive.css"
    ];
    public $js = [
       // "markup/public/js/jquery-1.11.3.min.js",
        "markup/public/js/bootstrap.min.js",
        "markup/public/js/owl.carousel.min.js",
        "markup/public/js/jquery.stickit.min.js",
        "markup/public/js/menu.js",
        "markup/public/js/scripts.js"
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
