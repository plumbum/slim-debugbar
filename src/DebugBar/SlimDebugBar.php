<?php namespace DebugBar;

use Slim\Slim;
use DebugBar\DataCollector\ConfigCollector;
use DebugBar\DataCollector\MemoryCollector;
use DebugBar\DataCollector\RequestDataCollector;
use DebugBar\DataCollector\TimeDataCollector;
use DebugBar\DataCollector\SlimEnvCollector;
use DebugBar\DataCollector\SlimLogCollector;
use DebugBar\DataCollector\SlimResponseCollector;
use DebugBar\DataCollector\SlimRouteCollector;
use DebugBar\DataCollector\SlimViewCollector;

class SlimDebugBar extends DebugBar
{
    public function __construct()
    {
        $this->addCollector(new TimeDataCollector());
        $this->addCollector(new RequestDataCollector());
        $this->addCollector(new MemoryCollector());
    }

    public function initCollectors(Slim $slim)
    {
        $l_this = $this;
        $this->addCollector(new SlimLogCollector($slim));
        $this->addCollector(new SlimEnvCollector($slim));
        $slim->hook('slim.after.router', function() use ($slim, $l_this)
        {
            $setting = $l_this->prepareRenderData($slim->container['settings']);
            $data = $l_this->prepareRenderData($slim->view->all());
            $l_this->addCollector(new SlimResponseCollector($slim->response));
            $l_this->addCollector(new ConfigCollector($setting));
            $l_this->addCollector(new SlimViewCollector($data));
            $l_this->addCollector(new SlimRouteCollector($slim));
        });
    }

    public function prepareRenderData(array $data = array())
    {
        $tmp = array();
        foreach ($data as $key => $val) {
            if (is_object($val)) {
                $val = "Object (". get_class($val) .")";
            }
            $tmp[$key] = $val;
        }
        return $tmp;
    }
}