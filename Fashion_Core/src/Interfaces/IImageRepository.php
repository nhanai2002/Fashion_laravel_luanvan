<?php

namespace FashionCore\Interfaces;

interface IImageRepository extends IRepository {
    public function getImage($id);
}