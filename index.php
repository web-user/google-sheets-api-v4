<?php

/**
 * Connect to API
 *******************************************************/
require_once __DIR__ . '/vendor/autoload.php';



// Path to the service account key file
$googleAccountKeyFilePath = __DIR__ . '/assets/my-project-test1-fdd689d70f55.json';
putenv( 'GOOGLE_APPLICATION_CREDENTIALS=' . $googleAccountKeyFilePath );

// Documentation https://developers.google.com/sheets/api/
$client = new Google_Client();
$client->useApplicationDefaultCredentials();

// Areas to be accessed
// https://developers.google.com/identity/protocols/googlescopes
$client->addScope( 'https://www.googleapis.com/auth/spreadsheets' );

$service = new Google_Service_Sheets( $client );







//$myFile = "/assets/data.json";
//$arr_data = array(); // create empty array
//
//try {
//    //Get form data
//    $formdata = array(
//        'firstName'=> "Vnn",
//        'lastName'=> "to",
//        'email'=>"pttt@cv",
//        'mobile'=> "nnnn"
//    );
//
////prepare the data
//    $data = array();
//    $data = $formdata;
//
////format the data
//    $formattedData = json_encode($data);
//
////set the filename
//    $filename = 'members-data-ddd.json';
//
////open or create the file
//    $handle = fopen($filename,'w+');
//
////write the data into the file
//    fwrite($handle,$formattedData);
//
////close the file
//    fclose($handle);
//    chmod($filename, 0777);
//
//
//} catch (Exception $e) {
//    echo 'Caught exception: ',  $e->getMessage(), "\n";
//}




// Table ID
$spreadsheetId = '1gq5EPyxqNGbsEXep-BYFabE1NFI2KqAEqb9w2RYSFmc';

/**
 * Getting information about the table and sheets
 *******************************************************/
// https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets/get
$response = $service->spreadsheets->get($spreadsheetId);

//$worksheetFeed = $response->getWorksheetFeed();


// Table properties
$spreadsheetProperties = $response->getProperties();
$spreadsheetProperties->title; // Table name

foreach ($response->getSheets() as $sheet) {

    // Sheet properties
    $sheetProperties = $sheet->getProperties();
    $titlelist = $sheetProperties->title; // Sheet name

    $gridProperties = $sheetProperties->getGridProperties();
    $gridProperties->columnCount; // Количество колонок
    $gridProperties->rowCount; // Количество строк

}

//print_r($gridProperties);




// https://developers.google.com/sheets/api/reference/rest/v4/spreadsheets.values/update
$values = [
    ["",],
    ["name", "phone", "mail"],
    ["vasa", "123", "test@sdf.xcv"],

];
$body    = new Google_Service_Sheets_ValueRange( [ 'values' => $values ] );

// valueInputOption - определяет способ интерпретации входных данных
// https://developers.google.com/sheets/api/reference/rest/v4/ValueInputOption
// RAW | USER_ENTERED
$options = array( 'valueInputOption' => 'USER_ENTERED' );

//$service->spreadsheets_values->update( $spreadsheetId, 'Лист1', $body, $options );


$my_set = $service->spreadsheets_values->append($spreadsheetId, $titlelist, $body, $options);

/**
 * Getting the contents of the specified sheet
 *******************************************************/
// Диапазон данных, которые необходимо получить
// Примеры:
// "Лист 1" - вернёт всё содержимое листа с указанным названием
// "Лист 1!B2:D4" - вернёт данные, находящиеся в диапазоне B2:D4 на листе с названием "Лист 1"
$range = $titlelist;
//$response = $service->spreadsheets_values->get($spreadsheetId, $range);

$response2 = $service->spreadsheets_values->get($spreadsheetId, $range, ['valueRenderOption' => 'FORMATTED_VALUE']);






//print_r($response);
echo "<pre>";
print_r($my_set);
echo "</pre>";

try{


    // Get our spreadsheet
    $spreadsheet = (new Google\Spreadsheet\SpreadsheetService)
        ->getSpreadsheetFeed()
        ->getByTitle('Form data');

    print_r("vvv");

    // Get the first worksheet (tab)
    $worksheets = $spreadsheet->getWorksheetFeed()->getEntries();
    $worksheet = $worksheets[0];
    $listFeed = $worksheet->getListFeed();
    $listFeed->insert([
        'name' => "'". 'Igor',
        'phone' => "'". '2425-245-224545',
        'surname' => "'". 'Orlov',
        'city' => "'". 'Berlin',
        'age' => "'". '35',
        'date' => date_create('now')->format('Y-m-d H:i:s')
    ]);
}catch(Exception $e){
    echo $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile() . ' ' . $e->getCode;
}

/*  SEND TO GOOGLE SHEETS */