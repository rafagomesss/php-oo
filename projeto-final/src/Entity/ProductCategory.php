<?php
namespace Code\Entity;

use Code\DB\Entity;

class ProductCategory extends Entity
{
    protected $table = 'products_categories';
    protected $timestamps = false;

    public function sync(int $productId, array $data)
    {
        $this->delete(['product_id' => $productId]);

        foreach ($data as $valor) {
            $saveManyToMany = [
                'product_id' => $productId,
                'category_id' => $valor
            ];
            $this->insert($saveManyToMany);
        }
    }
}