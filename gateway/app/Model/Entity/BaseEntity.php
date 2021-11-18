<?php declare(strict_types=1);

namespace App\Model\Entity;


use Swoft\Db\Eloquent\Model;

class BaseEntity extends Model
{

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    protected const CREATED_AT = 'createdAt';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    protected const UPDATED_AT = 'updatedAt';

}
