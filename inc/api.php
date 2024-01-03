<?php

add_action( 'rest_api_init', function () {
  register_rest_route( 'smartsheet/v1', '/sheets', array(
    'methods' => 'GET',
    'callback' => 'mdhsb_smartsheet_get_sheets',
    'permission_callback' => '__return_true'
  ));
  register_rest_route( 'smartsheet/v1', '/sheets/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'mdhsb_smartsheet_get_sheet',
    'permission_callback' => '__return_true'
  ));
  register_rest_route( 'smartsheet/v1', '/rows/id=(?P<id>\d+)/page=(?P<page>[a-z0-9 .\-]+)', array(
    'methods' => 'GET',
    'callback' => 'mdhsb_smartsheet_get_rows',
    'permission_callback' => '__return_true'
  ));

  register_rest_route( 'smartsheet/v1', '/search/sheets/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'mdhsb_smartsheet_search_sheet',
    'permission_callback' => '__return_true'
  ));
  
  register_rest_route('smartsheet/v1', '/county-search', array(
    'methods' => \WP_REST_Server::READABLE,
    'permission_callback' => '__return_true',
    'callback' => 'mdhsb_smartsheet_county_search',
  ));


  register_rest_route('sharepoint/v1', '/table', array(
    'methods' => \WP_REST_Server::READABLE,
    'permission_callback' => '__return_true',
    'callback' => 'mdhsb_sp_get_table'
  ));
  register_rest_route('sharepoint/v1', '/columns', array(
    'methods' => \WP_REST_Server::READABLE,
    'permission_callback' => '__return_true',
    'callback' => 'mdhsb_sp_get_columns'
  ));
});