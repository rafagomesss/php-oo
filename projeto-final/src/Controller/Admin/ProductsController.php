<?php
namespace Code\Controller\Admin;

use Ausi\SlugGenerator\SlugGenerator;
use Code\DB\Connection;
use Code\Entity\Product;
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

            if (isset($images['name']) && $images['name']) {
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

            Flash::add('success', 'Produto criado com sucesso!');
            return header('Location: ' . HOME . '/admin/products');
        }
        return (new View('admin' . DS . 'products' . DS . 'new.phtml'))->render();
    }

    public function edit($id = null)
    {
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
            $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

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

            Flash::add('success', 'Produto atualizado com sucesso!');
            return header('Location: ' . HOME . '/admin/products');
        }

        $view = (new View('admin' . DS . 'products' . DS . 'edit.phtml'));
        $view->product = (new Product(Connection::getInstance()))->find($id);

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