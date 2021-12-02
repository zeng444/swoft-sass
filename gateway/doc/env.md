

### 配置说明

- .env

```
SERVER_DOMAIN 申明服务对应数据库配置的服务器，用于load服务下属的服务给网关
```

### 系统租客识别

- 系统通过App\Rpc\Client\Contract\Balancer，载入了业务网关下可用的服务，配置来源于lightning_center.service中的服务信息
- 系统通过App\Rpc\Client\Contract\Extender，定制了JSON RPC协议中携带的信息，定制了tenantId、db、appId、appSecret参数发送到服务中去，实现了RPC权限认证和租户的服务数据库定位


| 字段 |  类型 | 说明 |
|------|-----|-----|
| tenantId | int  | 租户Id |
| db  | string  | 租户所属数据库 |
| appId  | string  | 服务权限验证ID |
| appSecret | string  | 服务权限验证KEY |


### 访问租客服务

- 说明

租客凭证决定如何访问服务，但特殊资源需要指定访问服务，如资源类，像系统菜单批量更新，可以采用 App\Common\Remote\ServiceRpc实现，特别注意得是只有当当前不存在凭证时才能调用此方法


- 方法


```
(\Swoft::getBean(ServiceRpc::class))->handle(Closure $callback, string $serviceCode = '', string $dbName = ''): void
```

- 参数

|参数 | 类型 | 说明 |
|----|-----|-------|
|callback | Closure   |  回调函数，内部的服务调用会自动向本网关所有服务发送    |
|serviceCode | string     |  指定向某台代号的服务调用，默认所有服务   |
|dbName | string     |   指定向某台代号下的指定服务的指定数据库发送，默认所有数据库  |

- 示例



```php
    /**
     * @Reference("biz.pool")
     * @var AclRouteInterface
     */
    protected $aclRouteService;

    /**
     * 批量请求指定服务的aclRouteService::build方法
     * Author:Robert
     *
     * @param string $serviceCode
     * @param string $database
     * @return bool
     */
    public function test(string $serviceCode, string $database): bool
    {
        $aclRouteService = $this->aclRouteService;
        $result = (\Swoft::getBean(ServiceRpc::class))->handle(static function (string $serviceCode, string $dbName) use ($aclRouteService) {
            return $aclRouteService->build($data)
        }, $serviceCode, $database);
     }
```




### 系统快捷方法

#### 获取当前登录用户

```
currentUserId(): int
```

#### 获取当前登录用户分组

```
currentGroupId()
```

#### 获取当前租户

```
currentTenantId(): int
```

#### 获取当前用户会话

```
session()
```

#### 获取当前用户扩展信息

```
session()->->getExtendedData(): array
```

或

```
sessionExtData(): array
```

#### 判断当前用户是否为超级管理员

```
currentIsSuper(): bool
```

#### 判断当前用户数据读取权限

```
currentReader(): string
```

| 权限名称  | 权限说明   |
|----------|------------|
| PERSONAL | 自己的数据  |
| GROUP    | 小组成员数据|
| FULL     | 所有数据    |





