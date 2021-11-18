<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;


/**
 * 服务列表
 * Class Service
 *
 * @since 2.0
 *
 * @Entity(table="service")
 */
class Service extends BaseEntity
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
     * 状态
     *
     * @Column()
     *
     * @var int
     */
    private $isAvailable;

    /**
     * 服务器
     *
     * @Column()
     *
     * @var int
     */
    private $serverId;

    /**
     * 服务名称
     *
     * @Column()
     *
     * @var string|null
     */
    private $name;

    /**
     * 服务代号
     *
     * @Column()
     *
     * @var string|null
     */
    private $code;

    /**
     * 服务tag，逗号隔开
     *
     * @Column()
     *
     * @var string|null
     */
    private $tag;

    /**
     * 服务地址
     *
     * @Column()
     *
     * @var string|null
     */
    private $host;

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
     * @param int $serverId
     *
     * @return self
     */
    public function setServerId(int $serverId): self
    {
        $this->serverId = $serverId;

        return $this;
    }

    /**
     * @param string|null $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string|null $code
     *
     * @return self
     */
    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param string|null $tag
     *
     * @return self
     */
    public function setTag(?string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @param string|null $host
     *
     * @return self
     */
    public function setHost(?string $host): self
    {
        $this->host = $host;

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
    public function getServerId(): ?int
    {
        return $this->serverId;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return string|null
     */
    public function getTag(): ?string
    {
        return $this->tag;
    }

    /**
     * @return string|null
     */
    public function getHost(): ?string
    {
        return $this->host;
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
