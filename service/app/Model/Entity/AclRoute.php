<?php declare(strict_types=1);


namespace App\Model\Entity;

use Swoft\Db\Annotation\Mapping\Column;
use Swoft\Db\Annotation\Mapping\Entity;
use Swoft\Db\Annotation\Mapping\Id;



/**
 * 
 * Class AclRoute
 *
 * @since 2.0
 *
 * @Entity(table="acl_route")
 */
class AclRoute extends BaseEntity
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
    private $tenantId;

    /**
     * 
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
    private $route;

    /**
     * 
     *
     * @Column(name="key", prop="propertyKey")
     *
     * @var string|null
     */
    private $propertyKey;

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
     * @param string|null $route
     *
     * @return self
     */
    public function setRoute(?string $route): self
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @param string|null $propertyKey
     *
     * @return self
     */
    public function setPropertyKey(?string $propertyKey): self
    {
        $this->propertyKey = $propertyKey;

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
     * @return string
     */
    public function getName(): ?string
    
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getRoute(): ?string
    
    {
        return $this->route;
    }

    /**
     * @return string|null
     */
    public function getPropertyKey(): ?string
    
    {
        return $this->propertyKey;
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
