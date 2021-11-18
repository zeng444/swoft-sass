<?php declare(strict_types=1);


namespace App\Model\Logic;

use App\Exception\LogicException;
use App\Model\Entity\Tenant;
use Swoft\Db\Eloquent\Model;
use Swoft\Rpc\Server\Annotation\Mapping\Service;

/**
 * Class TenantLogic
 * @author Robert
 * @Service()
 * @package App\Model\Logic
 */
class TenantLogic
{

    /**
     * @param string $name
     * @return Model|null
     * @throws LogicException
     * @author Robert
     */
    public function authorize(string $name): ?Tenant
    {
        // 用户验证成功则签发token
        $tenant = Tenant::where('name', $name)->first(['id', 'isAvailable', 'beginAt', 'endAt']);
        if (!$tenant) {
            throw new LogicException("商户不存在");
        }
        if($tenant->getIsAvailable()!='1'){
            throw new LogicException("商户已被禁用，请联系管理员");
        }
        $beginAt = $tenant->getBeginAt();
        $endAt = $tenant->getEndAt();
        $now = date('Y-m-d H:i:s');
        if ($beginAt && $now < $beginAt) {
            throw new LogicException("授权无效，您得授权已过期");
        }
        if ($endAt && $now > $endAt) {
            throw new LogicException("授权无效，您得授权已过期");
        }
        return $tenant;
    }

}
