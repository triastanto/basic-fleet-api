<?php

namespace App\Enums;

enum Transition : int
{
    case APPROVE = 1;
    case DRIVE_TO_DEST = 2;
    case END_TRIP = 3;
    case REJECT = 4;
}