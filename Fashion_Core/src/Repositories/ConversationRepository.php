<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\IConversationRepository;

class ConversationRepository extends BaseRepository implements IConversationRepository {
    public function getModel(){
        return \FashionCore\Models\Conversation :: class;
    }

}