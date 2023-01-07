<?php

namespace Lophper;

class Lophper
{
    private readonly Request $request;
    private readonly Response $response;
    private readonly Logger $logger;
    public function __construct(Request $request, Logger $logger, Sender $sender, int $now)
    {
        $this->request = $request;
        $this->logger = $logger;
        $needsLog = $logger->needsLog();
        $this->response = $needsLog ? new LastModifiedResponse($sender, $now) : new NotModifiedResponse($sender);
    }
    public function exec()
    {
        $this->response->send();
        if ($this->logger->needsLog()) {
            $this->logger->log($this->request->event);
        }
    }
}
