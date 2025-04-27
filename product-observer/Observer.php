<?php

interface Observer {

    public function update(
            int $id,
            String $title,
            String $description,
            String $category,
            float $price,
            int $stock,
            String $image,
            String $action);
}
