<?php

namespace app\controllers;

use app\models\CommentForm;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\ContactForm;
use app\models\Article;
use app\models\Category;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // build a DB query to get all articles with status = 1
        $query = Article::find();

        // get the total number of articles (but do not fetch the article data yet)
        $count = $query->count();

        // create a pagination object with the total count
        $pagination = new Pagination(['totalCount' => $count,'pageSize'=>1]);

        // limit the query using the pagination and retrieve the articles
        $articles = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();


        $not_popular = Article::find()->orderBy('viewed asc')->limit(3)->all();
        $recent = Article::find()->orderBy('date desc')->limit(4)->all();
        $categories = Category::find()->all();
        //sort($category);
        return $this->render('index',[
            'articles'=>$articles,
            'pagination'=>$pagination,
            'not_popular'=>$not_popular,
            'recent'=>$recent,
            'categories'=>$categories
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */


    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionSinglepost($id)
    {
        $article = Article::findOne($id);
        $tags = ArrayHelper::map($article->tags, 'id', 'title');
        $not_popular = Article::find()->orderBy('viewed asc')->limit(3)->all();
        $recent = Article::find()->orderBy('date desc')->limit(4)->all();
        $categories = Category::find()->all();
        $comments = $article->getArticleComments();
        $commentForm=new CommentForm();
        $article->singlePostCount();


        return $this->render('singlepost',[
            'article'=>$article,
            'tags',$tags,
            'not_popular'=>$not_popular,
            'recent'=>$recent,
            'categories'=>$categories,
            'comments'=>$comments,
            'commentForm'=>$commentForm
        ]);
    }
    public function actionCategory($id)
    {
        // build a DB query to get all articles with status = 1
        $query = Article::find()->where(['category_id'=>$id]);

        // get the total number of articles (but do not fetch the article data yet)
        $count = $query->count();

        // create a pagination object with the total count
        $pagination = new Pagination(['totalCount' => $count,'pageSize'=>10]);

        // limit the query using the pagination and retrieve the articles
        $articles = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $not_popular = Article::find()->orderBy('viewed asc')->limit(3)->all();
        $recent = Article::find()->orderBy('date desc')->limit(4)->all();
        $categories = Category::find()->all();
        return $this->render('category',[
            'articles'=>$articles,
            'pagination'=>$pagination,
            'not_popular'=>$not_popular,
            'recent'=>$recent,
            'categories'=>$categories

            ]);
    }


    public function actionComment($id)
    {


        $model = new CommentForm();
        if(Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());
            if($model->saveComment($id))
            {
                Yii::$app->getSession()->setFlash('comment','Your comment will be added soon');
                return $this->redirect(['site/singlepost','id'=>$id]);
            }
        }
    }

    public function actionTag($id)
    {
        // build a DB query to get all articles with status = 1
        $query = Article::find()->where(['category_id'=>$id]);

        // get the total number of articles (but do not fetch the article data yet)
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count,'pageSize'=>10]);
        $articles = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        $data=Category::getArticleByTag($id);
        $not_popular = Article::find()->orderBy('viewed asc')->limit(3)->all();
        $recent = Article::find()->orderBy('date desc')->limit(4)->all();
        $categories = Category::getAll();
        return $this->render('tag', [
            'articles'=>$data['articles'],
            'pagination'=>$data['pagination'],
            'not_popular'=>$not_popular,
            'recent'=>$recent,
            'categories'=>$categories

            ]);
    }





}
