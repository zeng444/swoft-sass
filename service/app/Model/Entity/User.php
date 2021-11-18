<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;



/**
 * 
 * Class User
 *
 * @since 2.0
 *
 * @Entity(table="user")
 */
class User extends BaseEntity
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
    private $isAvailable;

    /**
     * 
     *
     * @Column()
     *
     * @var int
     */
    private $isDeleted;

    /**
     * OAA
     *
     * @Column()
     *
     * @var int
     */
    private $isSuper;

    /**
     * 租户
     *
     * @Column()
     *
     * @var int
     */
    private $tenantId;

    /**
     * 账户
     *
     * @Column()
     *
     * @var string
     */
    private $account;

    /**
     * 姓名
     *
     * @Column()
     *
     * @var string
     */
    private $nickname;

    /**
     * 手机号
     *
     * @Column()
     *
     * @var string
     */
    private $mobile;

    /**
     * 密码
     *
     * @Column(hidden=true)
     *
     * @var string
     */
    private $password;

    /**
     * 角色
     *
     * @Column()
     *
     * @var int
     */
    private $roleId;

    /**
     * 分组
     *
     * @Column()
     *
     * @var int|null
     */
    private $groupId;

    /**
     * 最后登录时间
     *
     * @Column()
     *
     * @var string|null
     */
    private $latestLoginAt;

    /**
     * 
     *
     * @Column()
     *
     * @var int|null
     */
    private $latestLoginVer;

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
     * @param int $isAvailable
     *
     * @return self
     */
    public function setIsAvailable(int $isAvailable): self
    {
        $this->isAvailable = $isAvailable;

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
     * @param string $account
     *
     * @return self
     */
    public function setAccount(string $account): self
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @param string $nickname
     *
     * @return self
     */
    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * @param string $mobile
     *
     * @return self
     */
    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * @param string $password
     *
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param int $roleId
     *
     * @return self
     */
    public function setRoleId(int $roleId): self
    {
        $this->roleId = $roleId;

        return $this;
    }

    /**
     * @param int|null $groupId
     *
     * @return self
     */
    public function setGroupId(?int $groupId): self
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * @param string|null $latestLoginAt
     *
     * @return self
     */
    public function setLatestLoginAt(?string $latestLoginAt): self
    {
        $this->latestLoginAt = $latestLoginAt;

        return $this;
    }

    /**
     * @param int|null $latestLoginVer
     *
     * @return self
     */
    public function setLatestLoginVer(?int $latestLoginVer): self
    {
        $this->latestLoginVer = $latestLoginVer;

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
    public function getIsAvailable(): ?int
    
    {
        return $this->isAvailable;
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
    public function getIsSuper(): ?int
    
    {
        return $this->isSuper;
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
    public function getAccount(): ?string
    
    {
        return $this->account;
    }

    /**
     * @return string
     */
    public function getNickname(): ?string
    
    {
        return $this->nickname;
    }

    /**
     * @return string
     */
    public function getMobile(): ?string
    
    {
        return $this->mobile;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getRoleId(): ?int
    
    {
        return $this->roleId;
    }

    /**
     * @return int|null
     */
    public function getGroupId(): ?int
    
    {
        return $this->groupId;
    }

    /**
     * @return string|null
     */
    public function getLatestLoginAt(): ?string
    
    {
        return $this->latestLoginAt;
    }

    /**
     * @return int|null
     */
    public function getLatestLoginVer(): ?int
    
    {
        return $this->latestLoginVer;
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
