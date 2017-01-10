<?php

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Config;

/**
 * App configuration file.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class AppConfig extends ConfigFile
{
    private static $instance;

    /**
     * @param array $prop
     *
     * @return AppConfig
     */
    public static function getInstance($prop = null)
    {
        if (!self::$instance) {
            self::$instance = new self($prop);
        }

        return self::$instance;
    }

    public function name()
    {
        return 'app.php';
    }

    public function hooks()
    {
        return \Novosga\Util\Arrays::value($this->values(), 'hooks', []);
    }

    /**
     * Invoke a app hook.
     *
     * @param string $name
     * @param array  $args
     *
     * @return AppConfig
     */
    public function hook($name, $args)
    {
        $hook = \Novosga\Util\Arrays::value($this->hooks(), $name);
        if (is_callable($hook)) {
            if (!is_array($args)) {
                $args = [$args];
            }
            call_user_func_array($hook, $args);
        }

        return $this;
    }
}
