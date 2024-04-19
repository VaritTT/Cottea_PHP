<?php

class Category_process {
    use Model;
    protected $table = 'categories';
    public function getAllCategory() {
        return $this->findAll();
    }
}
