<?php

namespace App\Controllers;

class BaseController
{
    protected function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function errorResponse($message, $statusCode = 400, $errors = null)
    {
        $response = [
            'error' => true,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        if ($errors !== null) {
            $response['errors'] = $errors;
        }
        
        $this->jsonResponse($response, $statusCode);
    }
    
    protected function successResponse($message, $data = null, $statusCode = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        $this->jsonResponse($response, $statusCode);
    }
    
    protected function getRequestData()
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        
        if (strpos($contentType, 'application/json') !== false) {
            $json = file_get_contents('php://input');
            return json_decode($json, true);
        }
        
        return $_POST;
    }
}