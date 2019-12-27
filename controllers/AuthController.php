<?php
namespace app\controllers;

use app\models\SignupForm;
use app\models\Tag;
use app\models\User;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Article;
use app\models\Category;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use app\modules\admin\controllers\ArticleController;



class AuthController extends Controller
{

    public function actionLogin()
    {
        //if (!Yii::$app->user->isGuest) {
        //    return $this->goHome();
        //}
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionTest()
    {
        $user = User::findOne(1);
        Yii::$app->user->login($user);

    }

    public function actionRegister()
    {
        $model = new SignupForm();

        if(Yii::$app->request->post())
            {
               $model->load(Yii::$app->request->post());
               if($model->signup()) {
                   return $this->redirect(['auth/login']);
               }
            }

        return $this->render('signup',['model'=>$model]);

    }

}
