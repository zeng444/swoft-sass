<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;



/**
 * 
 * Class UserMenu
 *
 * @since 2.0
 *
 * @Entity(table="user_menu")
 */
class UserMenu extends BaseEntity
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
     * 开通menuId
     *
     * @Column()
     *
     * @var int
     */
    private $menuId;

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
     * @param int $menuId
     *
     * @return self
     */
    public function setMenuId(int $menuId): self
    {
        $this->menuId = $menuId;

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
     * @return int
     */
    public function getMenuId(): ?int
    
    {
        return $this->menuId;
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
