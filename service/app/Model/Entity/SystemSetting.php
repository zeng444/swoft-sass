<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;



/**
 * 租客系统配置
 * Class SystemSetting
 *
 * @since 2.0
 *
 * @Entity(table="system_setting")
 */
class SystemSetting extends BaseEntity
{
    use EntityTrait;

    /**
     * 
     * @Id(incrementing=false)
     * @Column()
     *
     * @var int
     */
    private $tenantId;

    /**
     * 坐席数
     *
     * @Column()
     *
     * @var int
     */
    private $allowedUsers;

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
     * @param int $allowedUsers
     *
     * @return self
     */
    public function setAllowedUsers(int $allowedUsers): self
    {
        $this->allowedUsers = $allowedUsers;

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
    public function getTenantId(): ?int
    {
        return $this->tenantId;
    }

    /**
     * @return int
     */
    public function getAllowedUsers(): ?int
    {
        return $this->allowedUsers;
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
