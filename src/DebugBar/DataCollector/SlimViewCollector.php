<?php namespace DebugBar\DataCollector;

class SlimViewCollector extends ConfigCollector
{
    public function __construct(array $data = array())
    {
        parent::__construct($data, 'view');
    }

    public function getName()
    {
        return 'view';
    }
}
