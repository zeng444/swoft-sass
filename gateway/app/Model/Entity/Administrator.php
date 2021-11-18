<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;


/**
 *
 * Class Administrator
 *
 * @since 2.0
 *
 * @Entity(table="administrator")
 */
class Administrator extends BaseEntity
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
     * @Column(name="wechat_openid", prop="wechatOpenid")
     *
     * @var string|null
     */
    private $wechatOpenid;

    /**
     * 角色
     *
     * @Column(name="role_id", prop="roleId")
     *
     * @var int
     */
    private $roleId;

    /**
     * 用户名
     *
     * @Column()
     *
     * @var string
     */
    private $name;

    /**
     *
     *
     * @Column()
     *
     * @var string|null
     */
    private $nickname;

    /**
     * 用户密码
     *
     * @Column(hidden=true)
     *
     * @var string|null
     */
    private $password;

    /**
     * 封号
     *
     * @Column(name="is_block", prop="isBlock")
     *
     * @var int|null
     */
    private $isBlock;

    /**
     *
     *
     * @Column(name="is_deleted", prop="isDeleted")
     *
     * @var int|null
     */
    private $isDeleted;

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
     * @param string|null $wechatOpenid
     *
     * @return self
     */
    public function setWechatOpenid(?string $wechatOpenid): self
    {
        $this->wechatOpenid = $wechatOpenid;

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
     * @param string|null $nickname
     *
     * @return self
     */
    public function setNickname(?string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * @param string|null $password
     *
     * @return self
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param int|null $isBlock
     *
     * @return self
     */
    public function setIsBlock(?int $isBlock): self
    {
        $this->isBlock = $isBlock;

        return $this;
    }

    /**
     * @param int|null $isDeleted
     *
     * @return self
     */
    public function setIsDeleted(?int $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

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
     * @return string|null
     */
    public function getWechatOpenid(): ?string
    {
        return $this->wechatOpenid;
    }

    /**
     * @return int
     */
    public function getRoleId(): ?int
    {
        return $this->roleId;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return int|null
     */
    public function getIsBlock(): ?int
    {
        return $this->isBlock;
    }

    /**
     * @return int|null
     */
    public function getIsDeleted(): ?int
    {
        return $this->isDeleted;
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
