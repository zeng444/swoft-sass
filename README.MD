## 业务网关

### 配置说明

- .env

```
SERVER_DOMAIN 申明服务对应数据库配置的服务器，用于load下属的服务给网关
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


- HTTP反向代理的配置

nginx.conf需要传递IP和SCHEME信息

```
server {
    listen 80;
    server_name cat.cn;
    location / {
        proxy_pass http://swoft-cat:18306;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header REQUEST_SCHEME $scheme;
    }
}

```

### 访问租客服务

- 说明

租客凭证决定如何访问服务，但特殊资源需要指定访问服务，如资源类，像系统菜单批量更新，可以采用 App\Common\Remote\ServiceRpc实现，特别注意的是只有当当前不存在凭证时才能调用此方法


- 方法


```php
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


### 访问租客服务(TenantId)

- 说明

租客凭证决定如何访问服务，但特殊资源需要通过租客ID，访问对应的服务，如资源类，像系统菜单批量更新，可以采用 App\Common\Remote\TenantRpc实现，特别注意的是只有当前不存在凭证时才能调用此方法


- 方法


```php
(\Swoft::getBean(TenantRpc::class))->handle(Closure $callback, int $tenantId = null): void
```

- 参数

|参数 | 类型 | 说明 |
|----|-----|-------|
|callback | Closure   |  回调函数，内部的服务调用会自动向本网关所有服务发送    |
|tenantId | string     |  指定租客ID的调用，默认所有租客   |



- 示例



```php
//接受微信通知并回调到指定租客服务
$tenantId = $request->intut("tenantId");

\Swoft::getBean(TenantRpc::class)->handle(static function ($tenantId) use ($paymentLogic, $paymentHeader, $raw) {

$paymentLogic = \Swoft::getBean(PaymentLogic::class);
$paymentLogic->notify($raw, $paymentHeader);

}, $tenantId);
```


### 系统快捷方法

#### 获取当前登录用户

```php
currentUserId(): int
```

#### 获取当前登录用户分组

```php
currentGroupId(): int
```

#### 获取当前租户

```php
currentTenantId(): int
```

#### 获取当前用户会话

```php
session()
```

#### 获取当前用户扩展信息

```php
session()->->getExtendedData(): array
```

或

```php
sessionExtData(): array
```

#### 判断当前用户是否为超级管理员

```php
currentIsSuper(): bool
```

#### 判断当前用户数据读取权限

```php
currentReader(): string
```

| 权限名称  | 权限说明   |
|----------|------------|
| PERSONAL | 自己的数据  |
| GROUP    | 小组成员数据|
| FULL     | 所有数据    |


## 服务端

### 数据库租户策略

- 通过服务拦截器，获取了从应用层底层发送的租客条件（tenantId）
- 通过App\Common\Db\DbSelector对象，实现了不同租客（tenantId）,切换各自数据库的过程
- 通过App\Common\Db\MySqlConnection的Mysql链接对象，对ORM和DAO等方式生产的SQL进行了解析，并自动应用了租客条件（tenantId）的SQL CURD操作补足
- 实际编写代码时，数据库CURD操作时，不需要编写tenantId相关条件，如果依然编写了tenantId条件，则会以你编写的为准，但需要注意的是inner查询，涉及到多个表，注入查询的是主表的tenantId作为查询条件
- 公共资源表，即tenantId始终为0的表，编写SQL时，操作CURD时候，需要设置tenantId使用明文作为条件，值取使用0，但已知问题：使用条件找到模型修改再保存，会自动注入tenantId查询条件，导致保存失败，这个是由于ORM保存时生成条件是以ID作为条件，是因为规则未指定tenantId时，强行注入tenantId造成的，解决方案是直接where条件保存，不要先查询再保存，或者使用“停止注入的闭包函数”手动控制

```php
### 问题
$model = Menu::where([
    ['id', 3],
    ['tenantId', 0]
]);
/** @var Menu $menu */
$menu = $model->first();
$menu->setSort(100);
$menu->save();
//生成SQL错误强制注入了当前的tenantId在保存条件时
//UPDATE `menu` SET `sort` = 100, `updatedAt` = '2022-04-19 12:02:54' WHERE `id` = 3 AND tenantId = 1

### 改写为下面方式更新来解决

Menu::where([
    ['id', 3],
    ['tenantId',0]
])->update(['sort'=> 100]);
```
#### 停止注入的闭包函数

- 对于tenantId始终等于0的数据表使用

```php
$result = \App\Common\Db\MySqlConnection::noTenant( static function ( $currentTenantId ) {
$model = Menu::where([
    ['id', 3],
    ['tenantId', 0]
]);
/** @var Menu $menu */
$menu = $model->first();
$menu->setSort(100);
return $menu->save();
});
```


### 系统快捷方法

#### 获取当前请求服务的租户id


```php
currentTenantId(): int
```

#### 获取当前运行得服务代号

```php
currentServiceCode(): string
```

#### 获取当前数据库

```php
currentDB(): string
```

### 异步调用



- 单个


```php
#任务投递
\App\Common\Async\Task::async(OrderLogic::class, 'add', [$line])
```

```php
#协程任务投递
\App\Common\Async\Task::co(OrderLogic::class, 'add', [$line]);
```

- 持久化异步调用

```php
#启动进程池
php bin/swoft process:start
```

```php
#持久化调用
App\Common\Async\Client::async(OrderLogic::class, 'add', [$line]);
```

- 批量

```php
#协程任务投递
\App\Common\Async\Task::cos([
[OrderLogic::class, 'add', [$line]]
]);
```


### 同步调用

```php
\App\Common\Caller\Client::call($className, $methodName, $params)
```

### 对象存储

- 创建对象


```php
\Swoft::getBean(App\Common\Oss\Client::class)-->upload(['excel202135/2499618269.png'], 'excel', 'WEEK', true)；
```

- 获取对象

```php
\Swoft::getBean(App\Common\Oss\Client::class)->pull('excel202135/2499618269.png',alias('@runtime/caches/1.png'));
```


- 删除对象

```php
\Swoft::getBean(App\Common\Oss\Client::class)->delete(["excel/202135/1329041768.png"]);
```

### Excel读取（自适应xlsx、csv等格式）

> 支持格式 'Xlsx', 'Xls', 'Xml', 'Ods', 'Ods', 'Slk', 'Gnumeric', 'Html', 'Csv'


```php
use App\Common\Excel\Reader;

/** @var Reader $excelReader */
$excelReader = \Swoft::getBean(Reader::class);
$excelReader->loadFile($dist);
$excelReader->setSheet(0);
$excelReader->setStartRow(2);
$excelReader->setRule('F',Reader::DATETIME_ROLE);
$excelReader->setRule('H',Reader::STRING_ROLE);
$excelReader->setRules([
    'F' => Reader::DATETIME_ROLE,
    'H' => Reader::STRING_ROLE
]);
```

```php
$excelReader->forEach(static function ($line, $isEnd, $row) {
    print_r([
        $row, $line
    ]);
});
```

```php
$excelReader->chunk(static function ($rows, $isEnd) {
    print_r(rows);
},20);
```

### Excel写入（自适应xlsx、csv等格式）

> 支持格式 'Xlsx', 'Csv', 'Xls', 'Ods', 'Html', 'Tcpdf', 'Dompdf', 'Mpdf'

```php
$writer = new \App\Common\Excel\Writer();
$writer->setTitle('批次到处任务');
$writer->setHeader(['序号', '车牌号', '车主姓名', '联系电话', '询价状态', '失败原因']);
foreach ($rows as $row) {
    $writer->writeRow($row);
}
$dist = 'runtime/20210930.xlsx';
$writer->save($dist, 'Xlsx');
```

- 通过setRule强制转换列值属性，解决时间读取等问题

|    Rule规则   |    说明      |
|-------|----------|
| Reader::DATETIME_ROLE | 转换为日期时间|
| Reader::DATE_ROLE|转换为日期|
| Reader::TIME_ROLE|转换为时间|
| Reader::INTEGER_ROLE|转换为整形|
| Reader::STRING_ROLE|转换为字符串|
| Reader::DOUBLE_ROLE|转换浮点|

### 大内存运行

- 临时调整内存运行，完成后恢复默认设置

```php
App\Common\Memory\Handle::run(static function ($originalMemory, $memory) {
//大内存操作
},'200M');
```




