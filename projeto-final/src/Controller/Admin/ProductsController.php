<?php
namespace Code\Controller\Admin;

use Ausi\SlugGenerator\SlugGenerator;
use Code\DB\Connection;
use Code\Entity\Category;
use Code\Entity\Product;
use Code\Entity\ProductCategory;
use Code\Entity\ProductImage;
use Code\Security\Validator\Sanitizer;
use Code\Security\Validator\Validator;
use Code\Session\Flash;
use Code\Upload\Upload;
use Code\View\View;

class ProductsController
{
    public function index()
    {
        $view = new View('admin' . DS . 'products' . DS . 'index.phtml');
        $view->products = (new Product(Connection::getInstance()))->findAll();

        return $view->render();
    }

    public function new()
    {

        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
            $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            try {
                $categories = $data['categories'];
                $images = $_FILES['images'];

                $data = Sanitizer::sanitizeData($data, Product::$filters);

                if(!Validator::validateRequiredFields($data)) {
                    Flash::add('warning', 'Preencha todos os campos!');
                    return header('Location: ' . HOME . '/admin/products/new');
                }

                $data['slug'] = (new SlugGenerator())->generate($data['name']);
                $data['price'] = str_replace('.', '', $data['price']);
                $data['price'] = str_replace(',', '.', $data['price']);

                $data['is_active'] = $data['is_active'] == 'A' ? 1 : 0;

                $product = new Product(Connection::getInstance());

                if(!$productId = $product->insert($data)) {
                    Flash::add('error', 'Erro ao criar produto!');
                    return header('Location: ' . HOME . '/admin/products/new');
                }

                if (isset($images['name'][0]) && $images['name'][0]) {

                    if (!Validator::validateImagesFile($images)) {
                        Flash::add('error', 'Imagens enviadas não são válidas!');
                        return header('Location: ' . HOME . '/admin/products/new');
                    }

                    $upload = new Upload();
                    $upload->setFolder(UPLOAD_PATH . DS . 'products' . DS);
                    $images = $upload->doUpload($images);

                    foreach ($images as $image) {
                        $imagesData = [];
                        $imagesData['product_id'] = $productId;
                        $imagesData['image'] = $image;

                        $productImage = new ProductImage(Connection::getInstance());
                        $productImage->insert($imagesData);
                    }
                }

                if (count($categories)) {
                    foreach ($categories as $category) {
                        $productCategory = new ProductCategory(Connection::getInstance());
                        $productCategory->insert([
                            'product_id' => $productId,
                            'category_id' => $category
                        ]);
                    }
                }

                Flash::add('success', 'Produto criado com sucesso!');
                return header('Location: ' . HOME . '/admin/products');
            } catch (\Exception $e) {
                if (APP_DEBUG) {
                    Flash::add('warning', $e->getMessage());
                    return header('Location: ' . HOME . '/admin/products/new');
                }
                Flash::add('warning', 'Erro ao salvar produto na loja!');
                return header('Location: ' . HOME . '/admin/products/new');
            }
        }

        $view = new View('admin' . DS . 'products' . DS . 'new.phtml');
        $view->categories = (new Category(Connection::getInstance()))->findAll();

        return $view->render();
    }

    public function edit($id = null)
    {

        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
            try {
                $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $images = $_FILES['images'];

                $categories = $data['categories'];

                $data = Sanitizer::sanitizeData($data, Product::$filters);

                if(!Validator::validateRequiredFields($data)) {
                    Flash::add('warning', 'Preencha todos os campos!');
                    return header('Location: ' . HOME . '/admin/products/edit/' . $id);
                }

                $data['id'] = (int) $id;
                $data['price'] = str_replace('.', '', $data['price']);
                $data['price'] = str_replace(',', '.', $data['price']);

                $data['is_active'] = $data['is_active'] == 'A' ? 1 : 0;

                $product = new Product(Connection::getInstance());

                if(!$product->update($data)) {
                    Flash::add('error', 'Erro ao atualizar produto!');
                    return header('Location: ' . HOME . '/admin/products/edit/' . $id);
                }

                $productCategory = new ProductCategory(Connection::getInstance());
                $productCategory->sync($id, $categories);

                if (isset($images['name'][0]) && $images['name'][0]) {

                    if (!Validator::validateImagesFile($images)) {
                        Flash::add('error', 'Imagens enviadas não são válidas!');
                        return header('Location: ' . HOME . '/admin/products/edit/' . $id);
                    }

                    $upload = new Upload();
                    $upload->setFolder(UPLOAD_PATH . DS . 'products' . DS);
                    $images = $upload->doUpload($images);

                    foreach ($images as $image) {
                        $imagesData = [];
                        $imagesData['product_id'] = $id;
                        $imagesData['image'] = $image;

                        $productImage = new ProductImage(Connection::getInstance());
                        $productImage->insert($imagesData);
                    }
                }

                Flash::add('success', 'Produto atualizado com sucesso!');
                return header('Location: ' . HOME . '/admin/products');
            } catch (\Exception $e) {
                if (APP_DEBUG) {
                    Flash::add('warning', $e->getMessage());
                    return header('Location: ' . HOME . '/admin/products/edit/' . $id);
                }
                Flash::add('warning', 'Erro ao atualizar produto na loja!');
                return header('Location: ' . HOME . '/admin/products/edit/' . $id);
            }
        }

        $view = (new View('admin' . DS . 'products' . DS . 'edit.phtml'));
        $view->product = (new Product(Connection::getInstance()))->getProductsWithImages($id);

        $view->productCategories = (new ProductCategory(Connection::getInstance()))->where(['product_id' => $id]);
        $view->productCategories = array_map(function($line) {
            return $line['category_id'];
        }, $view->productCategories);

        $view->categories = (new Category(Connection::getInstance()))->findAll();

        return $view->render();
    }

    public function remove($id = null)
    {
        try{
            $post = new Product(Connection::getInstance());

            if(!$post->delete($id)) {
                Flash::add('error', 'Erro ao realizar remoção do produto!');
                return header('Location: ' . HOME . '/admin/products');
            }

            Flash::add('success', 'Produto removido com sucesso!');
            return header('Location: ' . HOME . '/admin/products');

        } catch (\Exception $e) {
            if(APP_DEBUG) {
                Flash::add('error', $e->getMessage());
                return header('Location: ' . HOME . '/admin/products');
            }
            Flash::add('error', 'Ocorreu um problema interno, por favor contacte o admin.');
            return header('Location: ' . HOME . '/admin/products');
        }
    }


}