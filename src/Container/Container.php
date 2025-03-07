<?php

namespace IronFlow\Kernel\Container;

class Container
{
   protected $bindings = [];
   protected $instances = [];

   public function bind($abstract, $concrete)
   {
      $this->bindings[$abstract] = $concrete;
   }

   public function singleton($abstract, $concrete)
   {
      $this->instances[$abstract] = $concrete;
   }

   public function make($abstract)
   {
      if (isset($this->instances[$abstract])) {
         return $this->instances[$abstract];
      }

      if (isset($this->bindings[$abstract])) {
         return $this->build($this->bindings[$abstract]);
      }

      throw new \Exception("No binding found for {$abstract}");
   }

   protected function build($concrete)
   {
      if ($concrete instanceof \Closure) {
         return $concrete($this);
      }

      $reflector = new \ReflectionClass($concrete);

      if (!$reflector->isInstantiable()) {
         throw new \Exception("Class {$concrete} is not instantiable");
      }

      $constructor = $reflector->getConstructor();

      if (is_null($constructor)) {
         return new $concrete;
      }

      $parameters = $constructor->getParameters();
      $dependencies = $this->resolveDependencies($parameters);

      return $reflector->newInstanceArgs($dependencies);
   }

   protected function resolveDependencies($parameters)
   {
      $dependencies = [];

      foreach ($parameters as $parameter) {
         $dependency = $parameter->getClass();

         if ($dependency === null) {
            $dependencies[] = $this->resolveNonClass($parameter);
         } else {
            $dependencies[] = $this->make($dependency->name);
         }
      }

      return $dependencies;
   }

   protected function resolveNonClass($parameter)
   {
      if ($parameter->isDefaultValueAvailable()) {
         return $parameter->getDefaultValue();
      }

      throw new \Exception("Cannot resolve the dependency {$parameter->name}");
   }
}