<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;



/**
 * 
 * Class UserRole
 *
 * @since 2.0
 *
 * @Entity(table="user_role")
 */
class UserRole extends BaseEntity
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
     * 
     *
     * @Column()
     *
     * @var int
     */
    private $isSuper;

    /**
     * 
     *
     * @Column()
     *
     * @var int
     */
    private $isDeleted;

    /**
     * 租户
     *
     * @Column()
     *
     * @var int
     */
    private $tenantId;

    /**
     * 
     *
     * @Column()
     *
     * @var string
     */
    private $reader;

    /**
     * 角色名称
     *
     * @Column()
     *
     * @var string
     */
    private $name;

    /**
     * 用户数
     *
     * @Column()
     *
     * @var int
     */
    private $users;

    /**
     * 备注
     *
     * @Column()
     *
     * @var string|null
     */
    private $remark;

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
     * @param int $isSuper
     *
     * @return self
     */
    public function setIsSuper(int $isSuper): self
    {
        $this->isSuper = $isSuper;

        return $this;
    }

    /**
     * @param int $isDeleted
     *
     * @return self
     */
    public function setIsDeleted(int $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

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
     * @param string $reader
     *
     * @return self
     */
    public function setReader(string $reader): self
    {
        $this->reader = $reader;

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
     * @param string|null $remark
     *
     * @return self
     */
    public function setRemark(?string $remark): self
    {
        $this->remark = $remark;

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
    public function getIsSuper(): ?int
    {
        return $this->isSuper;
    }

    /**
     * @return int
     */
    public function getIsDeleted(): ?int
    {
        return $this->isDeleted;
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
    public function getReader(): ?string
    {
        return $this->reader;
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
    public function getRemark(): ?string
    {
        return $this->remark;
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
