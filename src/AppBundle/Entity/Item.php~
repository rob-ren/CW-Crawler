<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 12/5/17
 * Time: 9:54 AM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cw_item")
 */
class Item
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $price;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $save;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $product_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $brand_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $product_image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $reference_id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $timestamp;

    /**
     */
    public function __construct()
    {
        $this->timestamp = new \DateTime ();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Item
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set save
     *
     * @param string $save
     *
     * @return Item
     */
    public function setSave($save)
    {
        $this->save = $save;

        return $this;
    }

    /**
     * Get save
     *
     * @return string
     */
    public function getSave()
    {
        return $this->save;
    }

    /**
     * Set productName
     *
     * @param string $productName
     *
     * @return Item
     */
    public function setProductName($productName)
    {
        $this->product_name = $productName;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string
     */
    public function getProductName()
    {
        return $this->product_name;
    }

    /**
     * Set brandName
     *
     * @param string $brandName
     *
     * @return Item
     */
    public function setBrandName($brandName)
    {
        $this->brand_name = $brandName;

        return $this;
    }

    /**
     * Get brandName
     *
     * @return string
     */
    public function getBrandName()
    {
        return $this->brand_name;
    }

    /**
     * Set productImage
     *
     * @param string $productImage
     *
     * @return Item
     */
    public function setProductImage($productImage)
    {
        $this->product_image = $productImage;

        return $this;
    }

    /**
     * Get productImage
     *
     * @return string
     */
    public function getProductImage()
    {
        return $this->product_image;
    }

    /**
     * Set referenceId
     *
     * @param string $referenceId
     *
     * @return Item
     */
    public function setReferenceId($referenceId)
    {
        $this->reference_id = $referenceId;

        return $this;
    }

    /**
     * Get referenceId
     *
     * @return string
     */
    public function getReferenceId()
    {
        return $this->reference_id;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     *
     * @return Item
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}
