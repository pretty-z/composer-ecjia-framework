<?php


namespace Ecjia\Component\ApiServer\Route;


use Ecjia\Component\ApiServer\Contracts\ApiParserInterface;
use ecjia_error;

class ApiNamespaceParser implements ApiParserInterface
{

    /**
     * API所在的APP
     * @var string
     */
    protected $appModule;

    protected $apiPath;

    /**
     * API具体类
     * @var string
     */
    protected $className;

    public function __construct($appModule, $apiPath)
    {
        $this->appModule = $appModule;
        $this->apiPath = $apiPath;

        $this->parse($apiPath);
    }

    /**
     * 解析
     * @return mixed
     */
    protected function parse($apiPath)
    {
        $this->className = $apiPath;
    }

    /**
     * 获取完整的类名
     * @return string
     */
    public function getFullClassName()
    {
        return $this->className;
    }

    /**
     * 获取完整的文件名
     * @return string
     */
    public function getFullFileName()
    {
        $bundle = royalcms('app')->driver($this->appModule);

        $path = $bundle->getAbsolutePath();

        $class = str_replace($bundle->getNamespace(), '', $this->className);
        $class = trim($class, '/\\');
        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

        $path .= 'classes/' . $class . '.php';

        return $path;
    }

    /**
     * @return ecjia_error|mixed
     */
    public function getApihandler()
    {
        $this->getFullFileName();
        $class = $this->getFullClassName();

        if (class_exists($class)) {
            return new $class;
        }

        return new ecjia_error('api_not_instanceof', 'Api Error: ' . $this->getFullClassName() . ' does not exist.');
    }

}