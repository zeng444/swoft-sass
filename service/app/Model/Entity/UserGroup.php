<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;



/**
 * 
 * Class UserGroup
 *
 * @since 2.0
 *
 * @Entity(table="user_group")
 */
class UserGroup extends BaseEntity
{
    use EntityTrait;

    /**
     * 
     * @Id()
     * @Column()
     *
     * @var int
     */
    private $id;

    /**
     * 租户
     *
     * @Column()
     *
     * @var int
     */
    private $tenantId;

    /**
     * 分组名
     *
     * @Column()
     *
     * @var string
     */
    private $name;

    /**
     * 人数
     *
     * @Column()
     *
     * @var int
     */
    private $users;

    /**
     * 
     *
     * @Column()
     *
     * @var string|null
     */
    private $createdAt;

    /**
     * 
     *
     * @Column()
     *
     * @var string|null
     */
    private $updatedAt;


    /**
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param int $tenantId
     *
     * @return self
     */
    public function setTenantId(int $tenantId): self
    {
        $this->tenantId = $tenantId;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param int $users
     *
     * @return self
     */
    public function setUsers(int $users): self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @param string|null $createdAt
     *
     * @return self
     */
    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param string|null $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(?string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getTenantId(): ?int
    
    {
        return $this->tenantId;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getUsers(): ?int
    
    {
        return $this->users;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    
    {
        return $this->createdAt;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    
    {
        return $this->updatedAt;
    }

}
