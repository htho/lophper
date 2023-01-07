<?php

namespace Lophper;

trait FormatsHttpDate {
    private function httpDateFormat(int $time): string {
        return gmdate("D, d M Y H:i:s ", $time) . "GMT";
    }
}