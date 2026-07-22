<?php
/**
 * Retrieves image statistics from the Openverse API.
 *
 * @return array|false Returns an associative array containing the JSON response from the API,
 *                    or false on failure.
 */
function getImageStatsFromOpenverseAPI() {
    $apiUrl = 'https://api.openverse.org/v1/images/stats/?format=json';

    $ch = curl_init($apiUrl);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        error_log('cURL error: ' . curl_error($ch));
        curl_close($ch);
        return false;
    }

    curl_close($ch);

    if ($httpCode >= 200 && $httpCode < 300) {
        $responseData = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $responseData;
        } else {
            error_log('JSON decode error: ' . json_last_error_msg());
            return false;
        }
    } else {
        error_log('HTTP error: ' . $httpCode . ' Response: ' . $response);
        return false;
    }
}

// Example usage:
$imageStats = getImageStatsFromOpenverseAPI();

// Output the result for demonstration
header('Content-Type: application/json');
echo json_encode($imageStats, JSON_PRETTY_PRINT);

