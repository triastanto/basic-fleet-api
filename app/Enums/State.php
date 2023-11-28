<?php

namespace App\Enums;

enum State : int
{
    case WAITING_APPROVAL = 1;
    case APPROVED = 2;
    case ON_THE_WAY = 3;
    case RETURNED = 4;
}