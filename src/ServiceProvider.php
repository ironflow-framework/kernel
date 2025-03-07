<?php

namespace IronFlow\Kernel;

interface ServiceProvider
{
   /**
    * Enregistre les services dans le conteneur.
    *
    * @return void
    */
   public function register();

   /**
    * Initialise les services après l'enregistrement.
    *
    * @return void
    */
   public function boot();
}
