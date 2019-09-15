<?php

namespace app\controllers;

use app\models\UploadForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\UploadedFile;
use Zxing\QrReader;

class SiteController extends Controller
{
    public $layout = 'light';

    /**
     * {@inheritdoc}
     */


    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        if (Yii::$app->getUser()->isGuest) {
            $this->layout = 'guest';
        }
    }


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
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
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

    public function actionIsLogged()
    {
        $this->asJson([
            'auth' => !Yii::$app->getUser()->isGuest,
        ]);
    }

    public function actionScan()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $this->redirect(['site/profile',]);
        }

        return $this->render('scan', ['model' => $model]);
    }

    public function actionProfile()
    {
        return $this->render('profile');
    }

    public function actionButtpoints()
    {
        return $this->render('buttpoints');
    }

    public function actionAwarness()
    {
        return $this->render('education');
    }


    public function actionRewards() {
        $this->view->title = 'Rewards';
        return $this->renderContent('<img src="/light/coconut_1f965.png">');
    }

    public function actionEducation() {
        return $this->render('education');
    }

}
