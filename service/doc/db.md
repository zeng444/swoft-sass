
## 业务数据库规范

### 数据库名

**lightning**

### 设计规范

#### 表字段约定

- 表中必须包含租户Id即tenantId字段，服务框架底层会根据租户凭证自动在数据库增删改查命令中注入tenantId条件

- 租户公共表，比如菜单表menu，tenantId默认填写0即可，当tenantId小于等于0时，会停止对数据库命令应用tenantId条件注入

#### 数据表分区

- 业务主表应用表分区，主键必须设置为 id + tenantId联合字段主键索引
- 分区类型根据tenantId进行key分区，分区数必须为质数，推荐31、41、53、61、71、83、97


```sql
ALTER TABLE `order` PARTITION BY KEY (tenantId) PARTITIONS 41;
```

#### 数据表变更

根据系统架构设计原则，一个业务服务下可能存在多个数据库服务器，及每个数据库服务器存在多个平行分库，所以数据库变更操作需要一次性向所有数据库发送命令，使用 “运维脚本”中“网关命令”中“ 向网关下服务和数据库发送SQL脚本”来实现

```sh
# 在指定网关下运行，对网关下所有数据库服务及分库发送命令
php bin/swoft ops:sql /var/www/swoft/1.sql
```


## 租户管理运营数据库规范

### 数据库名

**lightning_center**
