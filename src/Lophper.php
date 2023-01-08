<?php

namespace Lophper;

class Lophper
{
    private readonly Request $request;
    private readonly AbstractResponse $response;
    private readonly AbstractLogger $logger;
    private readonly string $event;

    public function __construct(Request $request, ResponseFactory $responseFactory, LoggerFactory $loggerFactory)
    {
        $this->request = $request;
        $this->logger = $loggerFactory->getLogger($request->cycle);
        $event = $this->request->event;
        
        if($this->request->isFresh) {
            $this->event = "$event-first";
            $this->response = $responseFactory->getLastModified();
        } else {
            $this->event = "$event-more";
            $this->response = $responseFactory->getNotModified();
        }

    }
    public function exec()
    {
        $this->logger->log($this->event);
        $this->response->send();
    }
}
