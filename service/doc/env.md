### 数据库租户策略

- 通过服务拦截器，获取了从应用层底层发送的租客条件（tenantId）
- 通过App\Common\Db\DbSelector对象，实现了不同租客（tenantId）,切换各自数据库的过程
- 通过App\Common\Db\MySqlConnection的Mysql链接对象，对ORM和DAO等方式生产的SQL进行了解析，并自动应用了租客条件（tenantId）的SQL CURD操作补足
- 实际编写代码时，数据库CURD操作时，不需要编写tenantId相关条件的，如果依然编写了tenantId条件，则会已你编写的为准，但需要注意的是inner查询，涉及到多个表，注入查询的是主表的tenantId作为查询条件
- 公共资源表编写SQL时，tenantId使用明文作为条件，值取使用0

### 系统快捷方法

#### 获取当前请求服务的租户id


```
currentTenantId()
```

#### 获取当前运行得服务代号

```
currentServiceCode()
```

#### 获取当前数据库

```
currentDB()
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

```
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

```
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

```
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
