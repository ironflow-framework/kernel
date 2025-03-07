<?php

if (!function_exists('array_flatten')) {
   /**
    * Flatten a multi-dimensional array into a single level.
    *
    * @param  array  $array
    * @return array
    */
   function array_flatten(array $array): array
   {
      $result = [];
      array_walk_recursive($array, function($a) use (&$result) { $result[] = $a; });
      return $result;
   }
}

if (!function_exists('str_contains')) {
   /**
    * Determine if a given string contains a given substring.
    *
    * @param  string  $haystack
    * @param  string  $needle
    * @return bool
    */
   function str_contains(string $haystack, string $needle): bool
   {
      return strpos($haystack, $needle) !== false;
   }
}

if (!function_exists('array_get')) {
   /**
    * Get an item from an array using "dot" notation.
    *
    * @param  array  $array
    * @param  string  $key
    * @param  mixed  $default
    * @return mixed
    */
   function array_get(array $array, string $key, $default = null)
   {
      if (is_null($key)) {
         return $array;
      }

      if (array_key_exists($key, $array)) {
         return $array[$key];
      }

      foreach (explode('.', $key) as $segment) {
         if (is_array($array) && array_key_exists($segment, $array)) {
            $array = $array[$segment];
         } else {
            return $default;
         }
      }

      return $array;
   }
}