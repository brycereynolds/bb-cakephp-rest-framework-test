<?php

App::uses('Hash', 'Utility');

/**
 * Library of array functions for manipulating and extracting data
 * from arrays or 'sets' of data.
 *
 * `Hash` provides an improved interface, more consistent and
 * predictable set of features over `Set`.  While it lacks the spotty
 * support for pseudo Xpath, its more fully featured dot notation provides
 * similar features in a more consistent implementation.
 *
 * @package       Cake.Utility
 */
class BBHash extends Hash {

    public static function extractFirst($data, $path){
        $data = self::extract($data, $path);
        return isset($data[0]) ? $data[0] : $data;
    }


    public static function expand($data, $separator = '.') {
        $result = array();
        foreach ($data as $flat => $value) {
            $keys = explode($separator, $flat);
            $keys = array_reverse($keys);
            $child = array(
                $keys[0] => $value
            );
            array_shift($keys);
            foreach ($keys as $k) {
                $child = array(
                    $k => $child
                );
            }
            $result = self::merge($result, $child);
        }
        return $result;
    }

    /** Slight change to merge. Removed the auto add of keys if the current key is an int. Instead assume the key is the key we want */
    public static function merge(array $data, $merge) {
        $args = func_get_args();
        $return = current($args);

        while (($arg = next($args)) !== false) {
            foreach ((array)$arg as $key => $val) {
                if (!empty($return[$key]) && is_array($return[$key]) && is_array($val)) {
                    $return[$key] = self::merge($return[$key], $val);
                }  else {
                    $return[$key] = $val;
                }
            }
        }
        return $return;
    }

    /**
     * Our special combine adds a $useAutoIncrement value as well so that you do not have to have
     * specific keys.
     * @param string $keyPath A dot-separated string.
     * @param string $valuePath A dot-separated string.
     * @param string $groupPath A dot-separated string.
     * @return array Combined array
     * @link http://book.cakephp.org/2.0/en/core-utility-libraries/hash.html#Hash::combine
     */
    public static function combine(array $data, $keyPath, $valuePath = null, $groupPath = null, $useAutoIncrement = false) {
        if (empty($data)) {
            return array();
        }

        if (is_array($keyPath)) {
            $format = array_shift($keyPath);
            $keys = self::format($data, $keyPath, $format);
        } else {
            $keys = self::extract($data, $keyPath);
        }
        if (empty($keys)) {
            return array();
        }

        if (!empty($valuePath) && is_array($valuePath)) {
            $format = array_shift($valuePath);
            $vals = self::format($data, $valuePath, $format);
        } elseif (!empty($valuePath)) {
            $vals = self::extract($data, $valuePath);
        }

        $count = count($keys);
        for ($i = 0; $i < $count; $i++) {
            $vals[$i] = isset($vals[$i]) ? $vals[$i] : null;
        }

        if ($groupPath !== null) {
            $group = self::extract($data, $groupPath);
            if (!empty($group)) {
                $c = count($keys);
                for ($i = 0; $i < $c; $i++) {
                    if (!isset($group[$i])) {
                        $group[$i] = 0;
                    }
                    if (!isset($out[$group[$i]])) {
                        $out[$group[$i]] = array();
                    }
                    if($useAutoIncrement){
                        $out[$group[$i]][] = $vals[$i];
                    }else{
                        $out[$group[$i]][$keys[$i]] = $vals[$i];
                    }
                    
                }
                return $out;
            }
        }
        if (empty($vals)) {
            return array();
        }
        return array_combine($keys, $vals);
    }

}
