<?php

namespace FashionCore\Repositories;

use FashionCore\Interfaces\INotificationRepository;

class NotificationRepository extends BaseRepository implements INotificationRepository {
    public function getModel(){
        return \FashionCore\Models\Notification :: class;
    }

    public function getNotifications(){
        return $this->model->orderByDesc('created_at')->get();
    }


}