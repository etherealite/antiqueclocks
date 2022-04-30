<?php
namespace Curios\Wordpress;

use \Closure;


/**
 * Run a function in the earliest possible Wordpress context
 * 
 * 
 */
class RequestContext {

    public const STANDARD = 'standard';
    public const ADMIN = 'admin';
    public const REST = 'rest';
    public const CLI = 'cli';
    public const CRON = 'cron';
    public const ADMIN_AJAX = 'admin_ajax';

    private ?Closure $callback;


    public function __construct(?Closure $callback = null)
    {
        $this->callback = $callback;
    }

    public function runSync(): void
    {
        $this->process();
    }

    public function runHooked(): void
    {
        if(!did_action('muplugins_loaded')){
            add_action('muplugins_loaded', [$this, 'process']);
        }
    }

    public function process(): void
    {
        $context = $this->detect();
        if ($this->callback) {
            ($this->callback)($context);
        }   
    }

    public function detect(): string
    {
        assert(function_exists('is_admin'));
        assert(function_exists('wp_doing_cron'));
        assert(function_exists('wp_doing_ajax'));

        if (wp_doing_cron()){
            return $this::CRON;
        }
        if (defined('REST_REQUEST')) {
            return $this::REST;
        }
        // if (wp_doing_ajax()) {
        //     return $this::ADMIN_AJAX;
        // }
        if (is_admin()) {
            return $this::ADMIN;
        }
        if (defined('WP_CLI') && WP_CLI) {
            return $this::CLI;
        }
        return $this::STANDARD;
    }
}