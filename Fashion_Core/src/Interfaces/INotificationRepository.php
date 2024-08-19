<?php

namespace FashionCore\Interfaces;

interface INotificationRepository extends IRepository {
    public function getNotifications();
}