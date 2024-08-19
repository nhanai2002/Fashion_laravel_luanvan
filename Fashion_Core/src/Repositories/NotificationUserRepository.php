<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\INotificationUserRepository;

class NotificationUserRepository extends BaseRepository implements INotificationUserRepository {
    public function getModel(){
        return \FashionCore\Models\NotificationUser :: class;
    }

}