<?php

/**
 * Class Container
 *
 * Simple DI Container
 */
class Container implements ArrayAccess
{

    /** @var object[] storage for components */
    private $registry = array();

    /** @var array for detecting circular dependency */
    private $creating = array();

    /** @var array closures */
    private $closures = array();

    /**
     * Register component
     *
     * @param $name
     * @param Closure $componentName
     * @return $this
     * @throws Exception
     */
    public function registerComponent($name, Closure $componentName)
    {
        if(!is_string($name) || !$name)
            throw new Exception('Component name must be non-empty string.');

        if(isset($this->registry[$name]))
            throw new Exception("Component with this name '$name' already exist");

        $this->closures[$name] = $componentName;
        return $this;
    }

    /**
     * Remove component from registry
     *
     * @param $name
     */
    public function removeComponent($name)
    {
        unset($this->registry[$name]);
    }

    /**
     * Get component by name
     *
     * @param $name
     * @return object
     * @throws Exception
     */
    public function get($name)
    {
        if(!isset($this->closures[$name]))
            throw new Exception("There is no component with such a name ($name).");

        if(!isset($this->registry[$name]))
            $this->registry[$name] = $this->createComponent($name);
        return $this->registry[$name];

    }

    /**
     * Creates a instance of component (when is needed)
     *
     * @param $className
     * @return mixed
     * @throws Exception
     */
    private function createComponent($className) {
        if(!is_string($className) || !$className)
            throw new Exception('Component name must be non-empty string.');

        if(isset($this->creating[$className]))
            throw new Exception('Circular dependency detected.');

        $this->creating[$className] = TRUE;
        $component = call_user_func_array($this->closures[$className], array($this));
        unset($this->creating[$className]);

        return $component;
    }


    /* ARRAY AND OBJECT ACCESS */

    public function offsetSet($offset, $value) {
        $this->registerComponent($offset, $value);
    }

    public function offsetExists($offset) {
        return isset($this->registry[$offset]);
    }

    public function offsetUnset($offset) {
        $this->removeComponent($offset);
    }

    public function offsetGet($offset) {
        return $this->get($offset);
    }

    public function &__get ($key) {
        return $this->get($key);
    }

    public function __set($key,$value) {
        $this->registerComponent($key, $value);
    }

    public function __isset ($key) {
        return isset($this->registry[$key]);
    }

    public function __unset($key) {
        $this->removeComponent($key);
    }

}
