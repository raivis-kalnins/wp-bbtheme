<?php
if (!defined('ABSPATH')) exit;
if (function_exists('register_block_style')) {
    register_block_style('core/list', ['name' => 'tick-list', 'label' => __('Tick List', 'wp-theme')]);
    register_block_style('core/list', ['name' => 'contact-details-white', 'label' => __('Contact White', 'wp-theme')]);
    register_block_style('core/list', ['name' => 'no-bullets', 'label' => __('No bullets', 'wp-theme')]);
}
