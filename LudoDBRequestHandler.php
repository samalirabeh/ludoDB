<?php
/**
 * Request handler class for Front End Controller. This class will handle requests sent
 * by Views and pass them to the correct LudoDBObjects.
 * User: Alf Magne Kalleland
 * Date: 13.01.13
 * Time: 16:24
 */
class LudoDBRequestHandler
{
    /**
     * @var LudoDBObject
     */
    protected $model;
    protected $action;
    protected $cacheInstance;
    private $validRequests = array();
    private $success = true;
    private $message = "";
    private $code = 200;

    public function __construct()
    {

    }

    public function handle($request)
    {
        $request = $this->getParsed($request);
        try {
            $this->validRequests = $this->getValidServices($request);

            $this->model = $this->getModel($request, $this->getArguments($request));
            $this->action = $this->getAction($request);

            $this->model->validate($this->action, $request['data']);

            switch ($this->action) {
                case 'read':
                    if (!$this->model->getId() && $this->model instanceof LudoDBModel) {
                        throw new Exception('Object not found', 404);
                    }
                    return $this->toJSON($this->getValues());
                case 'save':
                    return $this->toJSON($this->model->save($request['data']));
                case 'delete':
                    return $this->toJSON($this->model->delete($request['data']));
            }
        } catch (Exception $e) {
            $this->message = $e->getMessage();
            $this->code = $e->getCode();
            $this->success = false;
            return $this->toJSON(array());
        }
        return "";
    }

    private function getParsed($request)
    {
        if (is_string($request)) $request = array('request' => $request);
        if (!isset($request['data'])) $request['data'] = array();
        return $request;
    }

    private function toJSON(array $data)
    {
        $ret = array(
            'success' => $this->success,
            'message' => $this->message,
            'code' => $this->code,
            'response' => $data
        );
        if (LudoDB::isLoggingEnabled()) {
            $ret['log'] = array(
                'time' => LudoDB::getElapsed(),
                'queries' => LudoDB::getQueryCount()
            );
        }
        return json_encode($ret);
    }

    protected function getArguments(array $request)
    {
        $ret = array();
        $tokens = explode("/", $request['request']);
        for ($i = 1, $count = count($tokens); $i < $count; $i++) {
            if ($i < $count - 1 || !in_array($tokens[$i], $this->validRequests)) {
                $ret[] = $tokens[$i];
            }
        }
        return $ret;
    }

    /**
     * @param array $request
     * @param array $args
     * @return null|object
     * @throws LudoDBClassNotFoundException
     */
    protected function getModel(array $request, $args = array())
    {
        $className = $this->getClassName($request);
        if (isset($className)) {
            $cl = $this->getReflectionClass($className);
            if (empty($args)) {
                return $cl->newInstance();
            } else {
                return $cl->newInstanceArgs($args);
            }
        }
        throw new LudoDBClassNotFoundException('Invalid request for: ' . $request['request'], 400);
    }

    private function getValidServices(array $request){
        $className = $this->getClassName($request);
        if(isset($className)){
            return $this->getReflectionClass($className)->getStaticPropertyValue("validServices");
        }
        return array();
    }

    private function getReflectionClass($className){
        $cl = new ReflectionClass($className);
        if (!$cl->isSubclassOf('LudoDBModel') && !$cl->isSubclassOf('LudoDBCollection')) {
            throw new LudoDBClassNotFoundException('Invalid request for: ' . $className, 400);
        }
        return $cl;
    }

    /**
     * @param $request
     * @return string|null
     */
    private function getClassName($request)
    {
        $tokens = explode("/", $request['request']);
        return class_exists($tokens[0]) ? $tokens[0] : null;
    }

    protected function getAction($request)
    {
        $tokens = explode("/", $request['request']);
        $action = $tokens[count($tokens) - 1];
        return in_array($action, $this->validRequests) ? $action : 'read';
    }

    public function getValues()
    {
        $data = null;
        $caching = $this->model->cacheEnabled();
        if ($caching) {
            if ($this->ludoDBCache()->hasValue()) {
                $data = $this->ludoDBCache()->getCache();
            }
        }
        if (!isset($data)) {
            $data = $this->model->getValues();
            if ($caching && $this->model->getJSONKey()) {
                $this->ludoDBCache()->setCache($data)->commit();
            }
        }
        return $data;
    }

    /**
     * @return LudoDBCache
     */
    protected function ludoDBCache()
    {
        if (!isset($this->cacheInstance)) {
            $this->cacheInstance = new LudoDBCache($this->model);
        }
        return $this->cacheInstance;
    }

    public function clearCacheObject()
    {
        $this->cacheInstance = null;
    }
}