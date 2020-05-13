<?php
namespace sunnnnn\nifty\auth\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use sunnnnn\nifty\auth\components\Controller;
use sunnnnn\nifty\auth\models\AuthRoute;

class RouteController extends Controller{
	
	public function actionIndex(){
		$searchModel = new AuthRoute();
		$searchModel->load(Yii::$app->request->queryParams);
		$condition = $andFilter = [];
		if(!empty($searchModel->keywords)){
			$andFilter[] = ['or', ['like', 'name', $searchModel->keywords], ['like', 'route', $searchModel->keywords]];
		}
		$dataProvider  = AuthRoute::adpSearch($condition, $andFilter);
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionAdd(){
	    $model = new AuthRoute();
	    if(Yii::$app->request->isPost){
	        $model->load(Yii::$app->request->post());
	        $model->add_time = time();
	        $model->edit_time = 0;
	        if ($model->save()) {
	            $this->checkCache();
	            return $this->response('@');
	        }else{
	            $errors = $model->getErrors();
	            if(!empty($errors)){
	                foreach($errors as $error){
	                    $error = is_array($error) ? array_pop($error) : $error;
	                    return $this->response(false, $error);
	                    break;
	                }
	            }
	        }
	    }
	    return $this->render('form', ['model' => $model]);
	}
	
	public function actionEdit(){
	    $model = AuthRoute::findOne(['id' => $this->getGetValue('id')]);
	    if(empty($model)){
	        throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
	    }
	    if(Yii::$app->request->isPost){
	        $model->load(Yii::$app->request->post());
	        $model->edit_time = time();
	        if($model->save()){
	            $this->checkCache();
	            return $this->response('@');
	        }else{
	            $errors = $model->getErrors();
	            if(!empty($errors)){
	                foreach($errors as $error){
	                    $error = is_array($error) ? array_pop($error) : $error;
	                    return $this->response(false, $error);
	                    break;
	                }
	            }
	        }
	    }
	    return $this->render('form', ['model' => $model]);
	}
	
	public function actionDelete(){
		$model = AuthRoute::findOne(['id' => $this->getPostValue('id')]);
		if(empty($model)){
			throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
		}
		
		if($model->delete()){
			$this->checkCache();
			$this->response(true);
    	}else{
    		$errors = $model->getErrors();
    		if(!empty($errors)){
    			foreach($errors as $error){
    				$error = is_array($error) ? array_pop($error) : $error;
    				$this->response(false, $error);
    				break;
    			}
    		}
    	}
	}

	public function actionGii(){
        return $this->render('gii', ['data' => [], 'columns' => [
            'route' => '路由',
            'desc' => '描述',
        ]]);
    }

    public function actionGiiPreview(){
        $keywords = Yii::$app->request->post('keywords', '');
        $classPath = trim($keywords);
        $data = [];

        try{
            if(empty($classPath)) throw new \Exception('请输入类名或路径');

            $first = substr($classPath, 0, 1);
            if('@' == $first){
                //防止有时结尾有'/'有时没有，统一去掉，获取文件时再加上
                $classPath = rtrim($classPath, '/');
                //获取物理路径，加上末尾的'/'
                $path = Yii::getAlias($classPath.'/');
                if(!is_dir($path)) throw new \Exception('请输入正确的路径');
                $files = Yii::$app->helper->file()->getFiles($path);
                if(empty($files)) throw new \Exception('未找到任何文件');
                //获取命名空间路径
                $namespace = ltrim($classPath, '@');
                foreach($files as $file){
                    $_namespace = explode('/', $namespace);
                    $_files = explode('.', $file);

                    $_namespace[] = $_files[0];

                    $_class = implode('\\', $_namespace);
                    if(!method_exists($_class, 'className')) continue;

                    $ref = new \ReflectionClass($_class::className());
                    $doc = $ref->getDocComment();
                    if(!empty($doc)){
                        $route = $this->getDocComment($doc, '@permission-route');
                        $desc = $this->getDocComment($doc, '@permission-desc');
                        if(!empty($route)){
                            $data[] = [
                                'route' => $route,
                                'desc' => $desc ?? ''
                            ];
                        }
                    }

                    $methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);
                    if(!empty($methods)){
                        foreach($methods as $method){
                            $doc = $method->getDocComment();
                            if(!empty($doc)){
                                $route = $this->getDocComment($doc, '@permission-route');
                                $desc = $this->getDocComment($doc, '@permission-desc');
                                if(!empty($route)){
                                    $data[] = [
                                        'route' => $route,
                                        'desc' => $desc ?? ''
                                    ];
                                }
                            }
                        }
                    }
                }
            }else{

                if(!method_exists($classPath, 'className')) throw new \Exception('请输入正确的类名');

                $ref = new \ReflectionClass($classPath::className());
                $doc = $ref->getDocComment();
                if(!empty($doc)){
                    $route = $this->getDocComment($doc, '@permission-route');
                    $desc = $this->getDocComment($doc, '@permission-desc');
                    if(!empty($route)){
                        $data[] = [
                            'route' => $route,
                            'desc' => $desc ?? ''
                        ];
                    }
                }

                $methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);
                if(!empty($methods)){
                    foreach($methods as $method){
                        $doc = $method->getDocComment();
                        if(!empty($doc)){
                            $route = $this->getDocComment($doc, '@permission-route');
                            $desc = $this->getDocComment($doc, '@permission-desc');
                            if(!empty($route)){
                                $data[] = [
                                    'route' => $route,
                                    'desc' => $desc ?? ''
                                ];
                            }
                        }
                    }
                }
            }

            return $this->response(true, '', ['data' => $data]);
        }catch(\Exception $e){
            return $this->response(false, $e->getMessage());
        }
    }

    public function actionGiiGenerate(){
	    $data = [];
        $selections = Yii::$app->request->post('selections', []);
        if(empty($selections)){
            return $this->response(false, '无数据');
        }

        foreach($selections as $key => $permission){
            $route = trim($permission['route']);
            $count = AuthRoute::find()->where(['route' => $route])->count('id');
            if(empty($count)){
                $data[] = [
                    'name' => trim($permission['desc']),
                    'route' => $route,
                    'add_time' => time(),
                    'edit_time' => 0
                ];
            }
        }

        $count = 0;
        if(!empty($data)){
            $count = Yii::$app->db->createCommand()->batchInsert(AuthRoute::tableName(), [
                'name', 'route', 'add_time', 'edit_time'
            ], $data)->execute();
        }

        return $this->response(true, "成功添加{$count}条数据");
    }

    private function getDocComment($str, $tag = ''){
        if (empty($tag)) return $str;

        $matches = [];
        preg_match("/".$tag."(.*)(\\r\\n|\\r|\\n)/U", $str, $matches);

        if (isset($matches[1])){
            return trim($matches[1]);
        }

        return '';
    }
	
	private function checkCache(){
		if(Yii::$app->cache->exists(AuthRoute::CACHE_ROUTES_All)){
			Yii::$app->cache->delete(AuthRoute::CACHE_ROUTES_All);
		}
	}
}