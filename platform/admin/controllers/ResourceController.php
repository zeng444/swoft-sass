<?php

use Application\Core\Models\Server;
use Application\Admin\Components\Db\Criteria;
use Application\Core\Models\ServiceDatabase;
use Application\Core\Models\Service;
use Phalcon\Mvc\View;

class ResourceController extends ControllerBase
{

    /**
     * 图片上传
     * @author Robert
     *
     */
    public function uploadAction()
    {
        if ($this->request->hasFiles()) {
            $field = trim($this->request->get('field'));
            $size = trim($this->request->get('size'));
            $size = $size ? explode('x', $size) : [];
            $resizeInfo = [];
            if ($size) {
                list($resizeInfo['width'], $resizeInfo['height']) = $size;
            }
            $files = $this->request->getUploadedFiles();
            $data = [];
            foreach ($files as $file) {
                $result = \Application\Admin\Components\Upload\File::uploadImage($file, $resizeInfo);
                if ($result === false || $result['code'] != 0) {
                    $this->apiError($result['message']);
                } else {
                    array_push($data, [
                        'name' => $result['data']['subfile'],
                        'file' => $result['data']['fileName'],
                        'url' => $result['data']['image'],
                        'field' => $field,
                    ]);
                }
            }
            $this->apiSuccess(['data' => $data, 'field' => $field]);
        }
    }


    /**
     * 数据库选择器
     */
    public function databaseAction(): array
    {
        if ($this->request->isAjax()) {
            $serviceDatabases = ServiceDatabase::find();
            $data = [];
            foreach ($serviceDatabases as $model) {
                $server = Server::findFirst($model->serverId);
                $service = Service::findFirst($model->serviceId);
                if ($service) {
                    $data[] = [
                        'id' => $model->id,
                        'name' => ($server ? $server->name:'') . ' - '. $service->name . ' - [' . $model->database . ']'
                    ];
                }
            }
            $this->apiSuccess(['data' => $data]);
        }
    }

    /**
     * 选择器接口
     * @author Robert
     *
     */
    public function selectorAction()
    {
        if ($this->request->isAjax()) {
            $modelName = trim($this->request->get('model'));
            $attribute = trim($this->request->get('attribute'));
            if (!$modelName || !$attribute) {
                $this->apiError('参数错误');
            }
            if (!class_exists($modelName)) {
                $this->apiError($modelName . '模型错误');
            }
            if (!property_exists($modelName, $attribute)) {
                $this->apiError($attribute . '错误');
            }
            $conditions = isset($_REQUEST['conditions']) && $_REQUEST['conditions'] ? trim($_REQUEST['conditions']) : '';
            if ($conditions) {
                $conditions = json_decode($conditions, true);
                if (!is_array($conditions)) {
                    $this->apiError('参数错误');
                }
            } else {
                $conditions = [];
            }
            $query = Criteria::fromInput($this->di, $modelName, $conditions);
            $criteria = $query->getParams();
            $criteria['limit'] = 100;
            //支持对主键的直接搜索
            if (isset($conditions[$attribute]) && preg_match('/^\d+$/', $conditions[$attribute])) {
                $model = new $modelName();
                $primaryKeys = $model->getModelsMetaData()->getPrimaryKeyAttributes($model);
                if ($primaryKeys && sizeof($primaryKeys) == '1') {
                    $primaryKey = $primaryKeys[0];
                    if ($criteria['conditions']) {
                        $criteria['conditions'] = $criteria['conditions'] . ' or ' . $primaryKey . '=:pk:';
                    } else {
                        $criteria['conditions'] = $primaryKey . '=:pk:';
                    }
                    if (!isset($criteria['bind'])) {
                        $criteria['bind'] = [];
                    }
                    $criteria['bind']['pk'] = $conditions[$attribute];
                }
            }

            $models = $modelName::find($criteria);
            $data = [];
            foreach ($models as $key => $model) {
                $data[$key] = [
                    'id' => $model->id,
                    'name' => $model->$attribute
                ];
            }
            $this->apiSuccess(['data' => $data]);
        }
    }

    /**
     * 级联选择器接口
     * @author Robert
     *
     */
    public function cascaderAction()
    {
        if ($this->request->isAjax()) {
            $modelName = trim($this->request->get('model'));
            $attribute = trim($this->request->get('attribute'));
            $relationAttribute = trim($this->request->get('relation_attribute'));
            $relationAttribute = $relationAttribute ? $relationAttribute : 'parent_id';
            $parentId = $this->request->get('parent_id', 'int');
            if (!$modelName || !$attribute) {
                $this->apiError('参数错误');
            }
            if (!class_exists($modelName)) {
                $this->apiError($modelName . '模型错误');
            }
            if (!property_exists($modelName, $relationAttribute)) {
                $this->apiError($relationAttribute . '错误');
            }
            if (!property_exists($modelName, $attribute)) {
                $this->apiError($attribute . '错误');
            }
            $conditions = isset($_REQUEST['conditions']) && $_REQUEST['conditions'] ? trim($_REQUEST['conditions']) : '';
            if ($conditions) {
                $conditions = json_decode($conditions, true);
                if (!is_array($conditions)) {
                    $this->apiError('参数错误');
                }
            } else {
                $conditions = [];
            }
            $query = Criteria::fromInput($this->di, $modelName, $conditions);
            $criteria = $query->getParams();
            $criteria['conditions'] = isset($criteria['conditions']) ? $criteria['conditions'] : [];
            if (!$parentId) {
                if ($criteria['conditions']) {
                    $criteria['conditions'] .= ' and ' . $relationAttribute . ' is null';
                } else {
                    $criteria['conditions'] = $relationAttribute . ' is null';
                }
            } else {
                if ($criteria['conditions']) {
                    $criteria['conditions'] .= ' and ' . $relationAttribute . ' =:parent:';
                } else {
                    $criteria['conditions'] = $relationAttribute . ' =:parent:';
                }
                $criteria['bind']['parent'] = $parentId;
            }
            $criteria['limit'] = 100;
            $models = $modelName::find($criteria);
            $data = [];
            foreach ($models as $key => $model) {
                $data[$key] = [
                    'value' => $model->id,
                    'label' => $model->$attribute
                ];
            }
            $this->apiSuccess(['data' => $data]);
        }
    }

    /**
     * 编辑器接口
     */
    public function editorAction()
    {
        $cdnDomain = $this->getDi()->get('config')->application->imagePrefix;
        include_once ADMIN_PATH . 'public/vendor/ueditor/php/controller.php';
        $this->view->setRenderLevel(View::LEVEL_AFTER_TEMPLATE);
    }

}