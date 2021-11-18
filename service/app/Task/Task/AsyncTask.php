<?php declare(strict_types=1);


namespace App\Task\Task;

use App\Common\Async\Task as CommonAsyncTask;
use Swoft\Task\Annotation\Mapping\Task;
use Swoft\Task\Annotation\Mapping\TaskMapping;

/**
 * Class SyncTask
 *
 * @since 2.0
 *
 * @Task(name="async")
 */
class AsyncTask
{

    /**
     * @TaskMapping(name="call")
     * @param string $className
     * @param string $methodName
     * @param array $params
     * @return mixed
     * @author Robert
     */
    public function call(string $className, string $methodName, array $params = [])
    {
        return CommonAsyncTask::call($className, $methodName, $params);
    }


}
