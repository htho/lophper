<?php

namespace Lophper;

/**
 * @note StaticAccess is explicitely allowed in phpmd-ruleset.xml
 */
enum ECycles: string
{
    case ONCE = "o";
    case ALWAYS = "a";
    case DAILY = "d";
    case MONTHLY = "m";
}
