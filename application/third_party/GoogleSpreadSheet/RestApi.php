<?php

// Google api 호출
include_once(ABS_PATH . "/vendor/autoload.php");

class RestApi
{
    protected $service;
    protected $spreadsheetId;

    function __construct($spreadsheetId)
    {
        $this->service = $this->getClient();
        $this->spreadsheetId = $spreadsheetId;
    }

    public function getClient()
    {
        // Get the API client and construct the service object.
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets API PHP Quickstart');
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
        $client->setAccessType('offline');
        $client->setAuthConfig(ABS_PATH.'/credentials.json');

        $service = new Google_Service_Sheets($client);

        return $service;
    }

    public function getList($range)
    {
        $service = $this->service;
        $response = $service->spreadsheets_values->get($this->spreadsheetId, $range);
        $values = $response->getValues();

        if (empty($values)) {
            print "No data found.\n";
        } else {
            return $values;
        }
    }
}
