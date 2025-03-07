<?php

namespace IronFlow\Kernel;

class Application
{
   protected $basePath;
   protected $container;
   protected $serviceProviders = [];
   protected $booted = false;

   public function __construct($basePath = null)
   {
      $this->basePath = $basePath;
      $this->container = new Container\Container();

      $this->registerBaseBindings();
   }

   protected function registerBaseBindings()
   {
      $this->container->singleton('app', $this);
      $this->container->singleton(Container\Container::class, function () {
         return $this->container;
      });
   }

   public function register(ServiceProvider $provider)
   {
      $provider->register();

      $this->serviceProviders[] = $provider;

      if ($this->booted) {
         $this->bootProvider($provider);
      }

      return $this;
   }

   public function boot()
   {
      if ($this->booted) {
         return;
      }

      foreach ($this->serviceProviders as $provider) {
         $this->bootProvider($provider);
      }

      $this->booted = true;

      return $this;
   }

   protected function bootProvider(ServiceProvider $provider)
   {
      $provider->boot();
   }

   // Autres méthodes d'application...
}
