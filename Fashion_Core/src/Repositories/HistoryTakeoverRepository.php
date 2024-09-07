<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\IHistoryTakeoverRepository;

class HistoryTakeoverRepository extends BaseRepository implements IHistoryTakeoverRepository {
    public function getModel(){
        return \FashionCore\Models\HistoryTakeover :: class;
    }



}