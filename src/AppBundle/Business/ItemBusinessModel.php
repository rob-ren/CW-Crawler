<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 18/4/17
 * Time: 3:29 PM
 */

namespace AppBundle\Business;


use AppBundle\Business\AbstractBusinessModel;
use AppBundle\Entity\Item;

/**
 * Class ItemBusinessModel
 *
 * @package AppBundle\Business
 */
class ItemBusinessModel extends AbstractBusinessModel
{
    public function __construct($doctrine)
    {
        parent::__construct($doctrine);
        $this->entity = new Item();
    }

    /**
     * new item
     *
     * @param Item $item
     * @return Item
     */
    public function newItem(Item $item)
    {
        if ($old_item = $this->loadByReferenceID($item->getReferenceId())) {
            $old_item->setProductImage($item->getProductImage());
            $old_item->setProductName($item->getProductName());
            $old_item->setPrice($item->getPrice());
            $old_item->setSave($item->getSave());
            return $old_item;
        }
        $this->persistEntity($item);
        return $item;

    }

    /**
     * remove this item
     *
     * @param $item
     * @return $this
     */
    public function removeItem($item)
    {
        $this->removeEntity($item);
        return $this;
    }

    /**
     * load item by reference_id
     *
     * @param $reference_id
     * @return Item
     */
    public function loadByReferenceID($reference_id)
    {
        $criteria = array(
            'reference_id' => $reference_id
        );
        $item = $this->getRepository()->findOneBy($criteria);
        return $item;
    }

    /**
     * load all items
     *
     * @param int $max_result_size
     * @param int $page
     * @return array
     */
    public function loadAllItems($max_result_size = 10000, $page = 0)
    {
        $qb = $this->getQueryBuilder("AppBundle:Item", "item");
        $dql = "item.id >= :id";
        $qb->where($dql)->setParameter(":id", 1);
        $qb->setFirstResult($page * $max_result_size);
        $qb->setMaxResults($max_result_size);
        $query = $qb->getQuery();
        return $query->getResult();
    }
}