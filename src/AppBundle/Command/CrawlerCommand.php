<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 12/5/17
 * Time: 10:09 AM
 */

namespace AppBundle\Command;


use AppBundle\Business\ItemBusinessModel;
use AppBundle\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CrawlerCommand extends ContainerAwareCommand
{
    private $base_url = "http://www.chemistwarehouse.com.au/Shop-OnLine/";

    /**
     * set up configure information
     */
    protected function configure()
    {
        $this->setName('crawler:fetch')->setDescription('fetch the latest chemist warehouse item information');
    }

    /**
     * get String between two keywords and return the array
     *
     * @param $string
     * @param $start
     * @param $end
     * @return array
     */
    public function getMutipleStringBetween($string, $start, $end)
    {
        $string = ' ' . $string;
        $start_pos = 0;
        $array = array();
        while (($ini = strpos($string, $start, $start_pos)) !== false) {
            $start_pos = $ini + 1;
            $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;
            $array[] = substr($string, $ini, $len);
        }
        return $array;
    }

    /**
     * get One String between two keywords
     *
     * @param $string
     * @param $start
     * @param $end
     * @return string
     */
    public function getOneStringBetween($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    /**
     * convert enum to array
     *
     * @param $class
     * @return array
     */
    public function EnumToArray($class)
    {
        $reflect = new \ReflectionClass ($class);
        $constants = $reflect->getConstants();
        return $constants;
    }

    /**
     * fetch price between current price and save price
     *
     * @param $price_strings
     * @param $type
     * @return array|null
     */
    public function fetchPrice($price_strings, $type)
    {
        $array = array();
        if ($type == "Price") {
            foreach ($price_strings as $price_string) {
                $price = $this->getOneStringBetween($price_string, "$", "  ");
                $array[] = $price;
            }
        } else if ($type == "Save") {
            foreach ($price_strings as $price_string) {
                $price = $this->getOneStringBetween($price_string, "SAVE  $", "  ") ? $this->getOneStringBetween($price_string, "SAVE  $", "\n    ") : "0.00";
                $array[] = $price;
            }
        } else {
            $array = null;
        }
        return $array;
    }

    /**
     * save item into db
     *
     * @param $product_names
     * @param $product_images
     * @param $prices
     * @param $saves
     * @param $reference_ids
     * @param $brand_name
     * @return null
     */
    public function saveItem($product_names, $product_images, $prices, $saves, $reference_ids, $brand_name)
    {
        $item_bm = $this->getItemBusinessModel();
        $key = -1;
        foreach ($product_names as $product_name) {
            $key++;
            $item = new Item();
            $item->setPrice($prices[$key]);
            $item->setProductName($product_name);
            $item->setSave($saves[$key]);
            $item->setProductImage($product_images[$key]);
            $item->setReferenceId($reference_ids[$key]);
            $item->setBrandName($brand_name);
            $item_bm->newItem($item);
            echo "Reference ID " . $reference_ids[$key] . ": " . $product_name . " has been saved in DB. \n";
        }
        return null;
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
     *
     * get item detals
     *
     * @param $page_content
     * @return null
     *
     */
    public function getItemDetails($page_content, $brand_name)
    {
        $product_images = array();
        $reference_ids = array();
        $product_names = $this->getMutipleStringBetween($page_content, "title=\" ", "\" >");
        $product_fetch_images = $this->getMutipleStringBetween($page_content, "<img src=\"", "\" alt=");
        // fetch product image and download image
        foreach ($product_fetch_images as $product_image) {
            if (strpos($product_image, "hero")) {
//                $root = $this->getContainer()->get("kernel")->getRootDir() . '/../web';
//                $relative_path = "price" . DIRECTORY_SEPARATOR . $brand_name . DIRECTORY_SEPARATOR . uniqid() . ".jpg";
//                $full_path = $root . DIRECTORY_SEPARATOR . $relative_path;
//                if (!file_exists(dirname($full_path))) {
//                    mkdir(dirname($full_path), 0755, true);
//                }
//                file_put_contents($full_path, file_get_contents($product_image));
//                $product_images[] = $relative_path;
                $product_images[] = $product_image;
            }
        }
        $prices_strings = $this->getMutipleStringBetween($page_content, "Price' >", "  </div>");
        $prices = $this->fetchPrice($prices_strings, "Price");
        $saves = $this->fetchPrice($prices_strings, "Save");
        $reference_fetch_ids = $this->getMutipleStringBetween($page_content, "/buy/", "/");
        foreach ($reference_fetch_ids as $reference_id) {
            if (is_numeric($reference_id)) {
                $reference_ids[] = $reference_id;
            }
        }
        $this->saveItem($product_names, $product_images, $prices, $saves, $reference_ids, $brand_name);
        echo "Totally " . count($product_names) . " items has been saved. \n";
        return null;
    }

    /**
     * execute command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $brands = $this->EnumToArray("AppBundle\Enum\BrandList");
        try {
            foreach ($brands as $brand) {
                $brand_name = array_search($brand, $this->EnumToArray("AppBundle\Enum\BrandList"));
                $full_url = $this->base_url . $brand . "/" . $brand_name . "?size=1000";
                $page_content = file_get_contents($full_url);
                $this->getItemDetails($page_content, $brand_name);
            }
        } catch (\Exception $e) {
            $output->writeln("Fail create." . $e->getMessage());
        }
    }
}