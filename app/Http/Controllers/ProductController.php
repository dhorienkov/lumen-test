<?php
// Copyright © LoveCrafts Collective Ltd - All Rights Reserved

namespace App\Http\Controllers;

use App\Entities\Product;
use App\Entities\Stock;
use App\Repository\ProductRepository;
use App\Services\ProductManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;

class ProductController extends BaseController
{
    /**
     * @var ProductManager
     */
    private $productManager;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * ProductController constructor.
     * @param ProductManager $productManager
     * @param ProductRepository $productRepository
     */
    public function __construct(
        ProductManager $productManager,
        ProductRepository $productRepository
    )
    {
        $this->productManager = $productManager;
        $this->productRepository = $productRepository;
        $this->serializer = SerializerBuilder::create()->build();;
    }

    /**
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    public function create(Request $request)
    {
        try {
            $this->validate($request, [
                'code' => 'required',
                'name' => 'required',
                'description' => 'required',
            ]);
        } catch(ValidationException $ex) {
            throw new BadRequestException();
        }
        $result = $this->productManager->create(Product::fromArray($request->all()));

        return $this->serializer->serialize($result, 'json');
    }

    /**
     * @param $request
     * @param $id
     * @return Product
     */
    public function update(Request $request, $id)
    {
        /** @var Product $product */
        $product = \EntityManager::find('App\Entities\Product', $id);

        if(is_null($product)){
            throw new ResourceNotFoundException();
        }

        return $this->productManager->update($product, $request->all());
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function delete(Request $request, $id)
    {
        /** @var Product $product */
        $product = $this->productRepository->find($id);

        if(is_null($product)){
            throw new ResourceNotFoundException();
        }

        $product->setDeleted();

        try {
            \EntityManager::persist($product);
        } catch(\Throwable $exception) {
            throw new \RuntimeException($exception->getMessage());
        }
        \EntityManager::flush();

        return response()->json(['status' => 'OK']);
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function getAll(Request $request)
    {
        $limit = 100;
        $offset = 0;
        $filterValue = null;
        $orderDirection = null;
        $expand = false;

        if ($request->has('limit')) {
            $limit = $request->get('limit');
        }

        if ($request->has('offset')) {
            $offset = $request->get('offset');
        }

        if ($request->has('sort_by')) {
            $sort = explode('.', $request->has('sort_by'));
            if ($sort[0] == 'onHand' && in_array($sort[1], ['asc', 'desc'])) {
                $orderDirection = $sort[1];
            }
        }

        if ($request->has('onHand')) {
            $filterValue = $request->input('onHand');
        }

        if ($request->has('expand')) {
            $expand = true;
        }

        /** @var Product $product */
        $products = $this->productRepository
            ->getProductsByCriteria($filterValue, $orderDirection, $expand, $limit, $offset);

        return response()->json([
            'count' => count($products),
            'results' => $this->serializer->toArray($products),
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return string
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getById(Request $request, $id)
    {
        if ($request->has('expand') ) {
            $product = $this->productRepository->getDetailedProduct($id);
        } else {
            $product = $this->productRepository->find($id);
            if(is_null($product)){
                throw new ResourceNotFoundException();
            }
            $product = $this->serializer->toArray($product);
            //it could be handled by serializer in future
            unset($product['stock']);
        }
        /** @var Product $product */



        if(is_null($product)){
            throw new ResourceNotFoundException();
        }

        return $this->serializer->serialize($product, 'json');
    }

    /**
     * @param Request $request
     * @param $id
     * @return string
     * @throws \Exception
     */
    public function addStock(Request $request, $id)
    {
        /** @var Product $product */
        $product = $this->productRepository->find($id);

        try {
            $this->validate($request, [
                'onHand' => 'required',
                'productionDate' => 'required',
            ]);
        } catch(ValidationException $ex) {
            throw new BadRequestException();
        }

        if(is_null($product)){
            throw new ResourceNotFoundException();
        }
        /** @var Stock $existedStock */
        $existedStock = \EntityManager::getRepository('App\Entities\Stock')->findBy(['product' => $id]);
        $existedStock = !empty($existedStock) ? $existedStock[0] : null;

        if ($existedStock) {
            $existedStock->updateOnHand($request->input('onHand'));
            $existedStock->updateProductionDate(new \DateTime($request->input('productionDate')));
            $stock = $existedStock;
        } else {
            $stock = new Stock(
                $product,
                $request->input('onHand'),
                new \DateTime($request->input('productionDate'))
            );
        }

        try {
            if ($existedStock) {
                \EntityManager::merge($existedStock);
            } else {
                \EntityManager::persist($stock);
            }
        } catch(\Throwable $exception) {
            throw new \RuntimeException($exception->getMessage());
        }

        \EntityManager::flush();

        return $this->serializer->serialize($stock, 'json');
    }
}
