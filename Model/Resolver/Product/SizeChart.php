<?php
/**
 * MageINIC
 * Copyright (C) 2023 MageINIC <support@mageinic.com>
 *
 * NOTICE OF LICENSE
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see https://opensource.org/licenses/gpl-3.0.html.
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category MageINIC
 * @package MageINIC_SizeChartGraphQl
 * @copyright Copyright (c) 2023 MageINIC (https://www.mageinic.com/)
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MageINIC <support@mageinic.com>
 */

namespace MageINIC\SizeChartGraphQl\Model\Resolver\Product;

use MageINIC\SizeChart\Api\SizeChartRepositoryInterface as RepositoryInterface ;
use MageINIC\SizeChart\Model\SizeChartFactory;
use Magento\Framework\Api\SearchCriteriaBuilder as SearchCriteria;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use MageINIC\SizeChart\ViewModel\SizeChart as Model;

class SizeChart implements ResolverInterface
{
    /**
     * @var SizeChartFactory
     */
    protected SizeChartFactory $sizeChartFactory;

    /**
     * @var RepositoryInterface
     */
    private RepositoryInterface $sizeChartRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var SearchCriteria
     */
    private SearchCriteria $searchCriteria;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param SizeChartFactory $sizeChartFactory
     * @param SearchCriteria $searchCriteria
     * @param RepositoryInterface $sizeChartRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        SizeChartFactory           $sizeChartFactory,
        SearchCriteria             $searchCriteria,
        RepositoryInterface        $sizeChartRepository,
        StoreManagerInterface      $storeManager,
    ) {
        $this->sizeChartFactory = $sizeChartFactory;
        $this->sizeChartRepository = $sizeChartRepository;
        $this->productRepository = $productRepository;
        $this->searchCriteria = $searchCriteria;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function resolve(
        Field       $field,
        $context,
        ResolveInfo $info,
        array       $value = null,
        array       $args = null
    ) {
        $data = [];
        try {
            $product = $value['model'];

            $storeId = $this->storeManager->getStore()->getId();
            $products = $this->productRepository->getById($product->getId());
            $attributeId = $products->getData('mageinic_size_chart');
            $searchCriteria = $this->searchCriteria
                ->addFilter('store_id', $storeId)
                ->addFilter('status', 1)
                ->addFilter('sizechart_id', $attributeId)
                ->setPageSize(1)
                ->setCurrentPage(1)
                ->create();
            $sizechartDetails = $this->sizeChartRepository->getList($searchCriteria);
            $data = $sizechartDetails->getItems();
            foreach ($sizechartDetails->getItems() as $sizeChart) {
                $data['title'] = $sizeChart->getTitle();
                $data['sizes'] = $sizeChart->getSizes();
                $data['bust'] = $sizeChart->getBust();
                $data['waist'] = $sizeChart->getWaist();
                $data['hip'] = $sizeChart->getHip();
                $data['content'] = $sizeChart->getContent();
            }
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }

        return $data;
    }
}
