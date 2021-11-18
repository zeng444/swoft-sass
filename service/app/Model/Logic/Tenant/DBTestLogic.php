<?php declare(strict_types=1);


namespace App\Model\Logic\Tenant;

use App\Model\Entity\AclRoute;
use App\Model\Entity\User;
use App\Model\Entity\UserRole;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Db\DB;

/**
 * Class DBTestLogic
 * @Bean()
 * @author Robert
 * @package App\Model\Logic\Tenant
 */
class DBTestLogic
{

    //CREATE TABLE `test` (
    //`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    //`tenantId` int(10) unsigned NOT NULL,
    //`name` varchar(20) NOT NULL,
    //`cateId` int(10) unsigned NOT NULL,
    //`biz` enum('A') DEFAULT NULL,
    //`createdAt` timestamp NULL DEFAULT NULL,
    //PRIMARY KEY (`id`)
    //) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4

    /**
     * Author:Robert
     *
     * @param $sql
     * @return array
     */
    public function injectSQL($sql): array
    {
//        if ($sql) {
//            return [
//                DB::statement($sql),
//            ];
//        }
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'menuId' => 1,
                'name' => uniqid(),
                'route' => uniqid(),
                'key' => uniqid(),
                'createdAt' => date('Y-m-d H:i:s'),
                'updatedAt' => date('Y-m-d H:i:s'),
            ];
        }
        AclRoute::insertOrUpdate($data, false, ['createdAt']);
        AclRoute::insertOrUpdate($data, true, ['createdAt']);
        DB::statement('insert into test (name,cateId,biz) values(?,"2","A"),("'.uniqid().'",?,"A"),("'.uniqid().'","2","A")', [
            uniqid(),
            5,
        ]);
        DB::statement('delete from test where cateId >?', ['0']);
        DB::statement("INSERT INTO test (name,cateId,biz)values('".uniqid()."',?,?)", ['2', 'A']);

        DB::statement('select count(*) from test where name= ? and cateId >0 group by id  limit 0,10', [uniqid()]);
        DB::statement('select * from test');
        User::where('id', 1)->first(['nickname', 'mobile', 'createdAt']);
        (User::where('id', 1)->exists());
        DB::statement("INSERT INTO test SET name= '1',cateId=3,biz= ? ", ['A']);
        DB::statement("REPLACE INTO test SET name= '1',cateId='3',biz= ? ", ['A']);
        DB::statement("UPDATE test SET name= 'update1_".uniqid()."',cateId='3',biz= ? ", ['A']);
        DB::statement("UPDATE `test` SET `name`= 'update2_".uniqid()."',`cateId`='3',`biz`= ? ", ['A']);
        User::join(UserRole::tableName(), 'user.roleId', '=', 'user_role.id')->forPage(1, 2)->get();
        User::join(UserRole::tableName(), 'user.roleId', '=', 'user_role.id')->where('user.tenantId', "8")
            ->forPage(1, 2)->get();

        //ORM
        AclRoute::insert([
            'menuId' => 1,
            'name' => uniqid(),
            'route' => uniqid(),
            'key' => uniqid(),
            'createdAt' => date('Y-m-d H:i:s'),
            'updatedAt' => date('Y-m-d H:i:s'),
        ]);
        User::findMany([1, 2, 3]);
        User::whereIn('id', [1, 2, 3])->get();
        $model = User::forPage(1, 10)->whereDate('createdAt', '2021-08-19 21:17:42')->whereDay('createdAt', '15')
                     ->whereIn('id', [1, 2])->whereBetween('id', [1, 2])->whereIntegerInRaw('roleId', [1, 2])
            //            ->whereJsonContains('name', "22")
                     ->whereColumn('roleId', '=', 'id')
            //            ->groupBy('roleId')
                     ->orderByDesc('id')->offset(0)->limit(10);
        $result[] = $model->count();
        //        $result = $model->average('roleId');
        $result = $model->firstArray();
        $result[] = $model->get();
        //        User::join(UserRole::tableName(), 'user.roleId', '=', 'user_role.id')->forPage(1, 2)->get();
        //        User::leftJoin(UserRole::tableName(), 'user.roleId', '=', 'user_role.id')->forPage(1, 2)->exists();
        //
        //        User::join(UserRole::tableName(), 'user.roleId', '=', 'user_role.id')
        //            ->join(UserRoleRoute::tableName(), 'user_role_route.userRoleId', '=', 'user_role.id')->forPage(1, 2)->get();
        //        DB::statement($sql);
        //        if ($sql) {
        //            DB::statement($sql);
        //        }
        DB::statement("SELECT * FROM `user`,`user_role` WHERE `user`.roleId=user_role.id");
        return $result;
    }

}
