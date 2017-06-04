<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 12/5/17
 * Time: 2:43 PM
 */
namespace AppBundle\Command;


use AppBundle\Business\ItemBusinessModel;
use AppBundle\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportCommand extends ContainerAwareCommand
{
    private $base_url = "http://www.chemistwarehouse.com.au/Shop-OnLine/";

    /**
     * set up configure information
     */
    protected function configure()
    {
        $this->setName('crawler:export')->setDescription('export chemist warehouse item information from db');
    }

    /**
     * get item business model
     *
     * @return ItemBusinessModel
     */
    public function getItemBusinessModel()
    {
        return $this->getContainer()->get("item_business");
    }

    /**
     * convert item object to array
     *
     * @param Item $item
     * @return array
     */
    public function ItemToArrary(Item $item)
    {
        $result = array();
        $result ['id'] = $item->getId();
        //$result ['product_image'] = $item->getProductImage();
        $result ['brand'] = $item->getBrandName();
        $result ['product_name'] = $item->getProductName();
        $result ['price'] = "$" . $item->getPrice();
        $result ['save'] = "$" . $item->getSave();
        $result ['reference_id'] = $item->getReferenceId();
        $result ['timestamp'] = $item->getTimestamp()->format('Y-m-d H:i:s');
        return $result;
    }

    /**
     *  export price list to csv format
     */
    public function exportCSV()
    {
        $item_bm = $this->getItemBusinessModel();
        $items = $item_bm->loadAllItems();
        $fp = fopen('CW_Price_List_' . date("y_m_d") . '.csv', 'w');
        fputcsv($fp, array('ID', 'Brand Name', 'Product Name', 'Price', 'Save', 'Reference Id', 'Update Time'));
        foreach ($items as $item) {
            $fields = $this->ItemToArrary($item);
            fputcsv($fp, $fields);
        }
    }

    /**
     * execute command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->exportCSV();
        } catch
        (\Exception $e) {
            $output->writeln("Fail create." . $e->getMessage());
        }
    }
}