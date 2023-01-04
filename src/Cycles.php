<?php

namespace Lophper;

enum Cycles: string
{
    case ONCE = "o";
    case ALWAYS = "a";
    case DAILY = "d";
    case MONTHLY = "m";
}
