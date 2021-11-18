<?php
namespace Application\Admin\Components\Mvc\Model\Behavior;

use Application\Core\Models\OperationLog;
use Phalcon\Mvc\Model\Behavior;
use Phalcon\Http\Request;
use Phalcon\Mvc\Model\BehaviorInterface;
use Phalcon\Mvc\ModelInterface as ModelInterface;


/**
 *
 * @author Robert
 *
 * Class Log
 */
class Log extends Behavior implements BehaviorInterface
{

    /**
     * @var
     */
    public $adminId;

    /**
     * @var
     */
    public $adminName;

    /**
     * @var array
     */
    public static $eventTypeMap = [
        'afterCreate'=>'CREATE',
        'afterDelete'=>'DELETE',
//        'afterUpdate'=>'UPDATE',
        'beforeUpdate'=>'UPDATE',
    ];


    /**
     * @param null $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        if (isset($options['id'])) {
            $this->adminId = $options['id'];
        }
        if (isset($options['name'])) {
            $this->adminName = $options['name'];
        }
    }



    /**
     *
     * @author Robert
     *
     * @param string $eventType
     * @param ModelInterface $model
     */
    public function notify($eventType, ModelInterface $model)
    {
        $this->writeLog($eventType, $model);
    }


    /**
     *
     * @author Robert
     *
     * @param $eventType
     * @param $model
     * @return bool
     */
    public function writeLog($eventType, $model)
    {
        if (!in_array($eventType, array_keys(self::$eventTypeMap))) {
            return true;
        }
        $request = new Request();
        $operationLog = new OperationLog();
        $operationLog->ip = $request->getClientAddress();
        $operationLog->action = isset($_GET['_url']) ? $_GET['_url'] : '';
        $operationLog->event = self::$eventTypeMap[$eventType];
        $operationLog->data = json_encode($model->toArray());
        $operationLog->updatedAt = date('Y-m-d H:i:s');
        $operationLog->createdAt = $operationLog->updatedAt;
        if ($this->adminId) {
            $operationLog->administrator_id = $this->adminId;
        }
        if ($this->adminName) {
            $operationLog->administrator_name = $this->adminName;
        }
        return $operationLog->save();

    }
}
