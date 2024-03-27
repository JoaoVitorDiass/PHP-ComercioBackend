<?php

namespace Comercio\Api\utils\observer;

interface Subject {
    public function attach(Observer $observer);
    public function detach(Observer $observer);
    public function notify();
}