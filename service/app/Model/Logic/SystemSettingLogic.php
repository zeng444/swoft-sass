<?php declare(strict_types=1);

namespace App\Model\Logic;

use App\Model\Entity\SystemSetting;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Redis\Redis;
use RuntimeException;

/**
 * 系统配置
 * Author:Robert
 *
 * Class SystemSettingLogic
 * @Bean()
 * @package App\Model\Logic
 */
class SystemSettingLogic
{

    private const SETTING_CACHE_EXPIRE = 86300;

    /**
     * Author:Robert
     *
     * @param int $tenantId
     * @return string
     */
    private function settingCacheKey(int $tenantId): string
    {
        return env('APP_EN_NAME','saas').':setting:'.$tenantId;
    }

    /**
     * Author:Robert
     *
     * @param string $key
     * @param string $val
     * @param int|null $tenantId
     * @return bool
     * @throws RuntimeException
     */
    public function set(string $key, string $val, int $tenantId = null): bool
    {
        $tenantId = $tenantId ?: currentTenantId();
        SystemSetting::where('tenantId', $tenantId)->update([$key => $val]);
        $cacheKey = $this->settingCacheKey($tenantId);
        Redis::hSet($cacheKey, $key, $val) && Redis::expire($cacheKey, self::SETTING_CACHE_EXPIRE);
        return true;
    }

    /**
     * Author:Robert
     *
     * @param string $key
     * @param string $default
     * @param int|null $tenantId
     * @return string
     * @throws RuntimeException
     */
    public function get(string $key, string $default = '', int $tenantId = null): string
    {
        $tenantId = $tenantId ?: currentTenantId();
        $cacheKey = $this->settingCacheKey($tenantId);
        if (Redis::hExists($cacheKey, $key)) {
            return (string)Redis::hGet($cacheKey, $key);
        }
        $val = SystemSetting::where('tenantId', $tenantId)->value($key);
        if ($val === null) {
            return $default;
        }
        Redis::hSet($cacheKey, $key, (string)$val) && Redis::expire($cacheKey, self::SETTING_CACHE_EXPIRE);
        return (string)$val;
    }

    /**
     * Author:Robert
     *
     * @param int|null $tenantId
     * @return array
     */
    public function getAll(int $tenantId = null): array
    {
        $tenantId = $tenantId ?: currentTenantId();
        $cacheKey = $this->settingCacheKey($tenantId);
        $config = Redis::hGetAll($cacheKey);
        if($config){
            return $config;
        }
        $config =  SystemSetting::where('tenantId', $tenantId)->first(['allowedUsers','dailySmsLimit','dailyVoiceLimit','voiceLoopTime'])->toArray();
        if ($config && (!Redis::hMSet($cacheKey, $config) || !Redis::expire($cacheKey, self::SETTING_CACHE_EXPIRE))) {
            throw new RuntimeException('设置变量失败');
        }
        return $config;
    }

    /**
     * Author:Robert
     *
     * @param int|null $tenantId
     * @return bool
     */
    public function clean(int $tenantId = null): bool
    {
        $tenantId = $tenantId ?: currentTenantId();
        Redis::del($this->settingCacheKey($tenantId));
        return true;
    }

    /**
     * 重置或者覆盖
     * Author:Robert
     *
     * @param array $config
     * @param int|null $tenantId
     * @return bool
     * @throws RuntimeException
     */
    public function initDefault(array $config, int $tenantId = null): bool
    {
        $tenantId = $tenantId ?: currentTenantId();
        $date = date('Y-m-d H:i:s');
        if (!SystemSetting::insertOrUpdate(array_merge($config, [
            'tenantId' => $tenantId,
            'createdAt' => $date,
            'updatedAt' => $date,
        ]), true, ['createdAt'])) {
            throw new RuntimeException('设置变量失败');
        }
        $cacheKey = $this->settingCacheKey($tenantId);
        if (!Redis::hMSet($cacheKey, $config) || !Redis::expire($cacheKey, self::SETTING_CACHE_EXPIRE)) {
            throw new RuntimeException('设置变量失败');
        }
        return true;
    }

}
