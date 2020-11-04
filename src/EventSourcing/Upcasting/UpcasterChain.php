<?php

namespace Morebec\Orkestra\EventSourcing\Upcasting;

class UpcasterChain implements UpcasterInterface
{
    /**
     * @var array
     */
    private $upcasters;

    public function __construct(iterable $upcasters)
    {
        $this->upcasters = [];
        foreach ($upcasters as $upcaster) {
            $this->upcasters[] = $upcaster;
        }
    }

    public function upcast(UpcastableMessage $message): array
    {
        return $this->doUpcast($this->upcasters, $message);
    }

    public function supports(UpcastableMessage $message): bool
    {
        foreach ($this->upcasters as $upcaster) {
            if ($upcaster->supports($message)) {
                return true;
            }
        }

        return false;
    }

    private function doUpcast(array $chain, UpcastableMessage $message): array
    {
        if (empty($chain)) {
            return [$message];
        }

        $head = \array_slice($chain, 0, 1);
        $tail = \array_slice($chain, 1);

        $messages = $head[0]->upcast($message);

        $result = [];
        foreach ($messages as $key => $msg) {
            $result = array_merge($result, $this->doUpcast($tail, $msg));
        }

        return $result;
    }
}
