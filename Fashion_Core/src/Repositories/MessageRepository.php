<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\IMessageRepository;

class MessageRepository extends BaseRepository implements IMessageRepository {
    public function getModel(){
        return \FashionCore\Models\Message :: class;
    }



}