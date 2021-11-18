<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;


/**
 *
 * Class TenantService
 *
 * @since 2.0
 *
 * @Entity(table="tenant_service")
 */
class TenantService extends BaseEntity
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
     * 租户Id
     *
     * @Column()
     *
     * @var int
     */
    private $tenantId;

    /**
     * 服务器ID
     *
     * @Column()
     *
     * @var int|null
     */
    private $serverId;

    /**
     * 服务ID
     *
     * @Column()
     *
     * @var int
     */
    private $serviceId;

    /**
     * 数据库
     *
     * @Column()
     *
     * @var string|null
     */
    private $dbName;

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
     * @param int|null $serverId
     *
     * @return self
     */
    public function setServerId(?int $serverId): self
    {
        $this->serverId = $serverId;

        return $this;
    }

    /**
     * @param int $serviceId
     *
     * @return self
     */
    public function setServiceId(int $serviceId): self
    {
        $this->serviceId = $serviceId;

        return $this;
    }

    /**
     * @param string|null $dbName
     *
     * @return self
     */
    public function setDbName(?string $dbName): self
    {
        $this->dbName = $dbName;

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
     * @return int|null
     */
    public function getServerId(): ?int
    {
        return $this->serverId;
    }

    /**
     * @return int
     */
    public function getServiceId(): ?int
    {
        return $this->serviceId;
    }

    /**
     * @return string|null
     */
    public function getDbName(): ?string
    {
        return $this->dbName;
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
