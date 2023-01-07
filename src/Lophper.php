<?php

namespace Lophper;

class Lophper
{
    private readonly Request $request;
    private readonly Response $response;
    private readonly AbstractLogger $logger;
    public function __construct(Request $request, LoggerFactory $loggerFactory, Sender $sender, int $now)
    {
        $this->request = $request;
        $this->logger = $loggerFactory->getLogger($request->cycle, $now);
        $this->response = $request->isFresh ? new LastModifiedResponse($sender, $now) : new NotModifiedResponse($sender);

    }
    public function exec()
    {
        $this->response->send();
        $this->logger->log($this->request->event);
    }
}
