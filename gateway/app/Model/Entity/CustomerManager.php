<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;


/**
 * 客服经理
 * Class CustomerManager
 *
 * @since 2.0
 *
 * @Entity(table="customer_manager")
 */
class CustomerManager extends BaseEntity
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
     *
     *
     * @Column()
     *
     * @var string|null
     */
    private $name;

    /**
     *
     *
     * @Column()
     *
     * @var string|null
     */
    private $mobile;

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
     * @param string|null $mobile
     *
     * @return self
     */
    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

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
     * @return string|null
     */
    public function getName(): ?string

    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getMobile(): ?string

    {
        return $this->mobile;
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
