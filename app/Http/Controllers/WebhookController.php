<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    /* 
        Webhook Automation
        This controller will handle all the logic to integrate or automate
        between Freshsales, Monday, and Xero
    */

    function automation(Request $request): JsonResponse
    {

        Log::info('Freshsales webhook hit');
        Log::info($request->all());

        /* 
            On the development environment, it is recommended to test it using a third party tunnelling tools called ngrok.
            See https://ngrok.com/docs/getting-started/ for reference
        */

        $rawDealsData = $request->all(); // Getting all the payload / data from Fresh Sales Webhook.

        $unescapedDealsData = array_map(function ($value) {

            return is_string($value) ? stripslashes($value) : $value;
        
        }, $rawDealsData);

        $dealClosedDate = $unescapedDealsData['deal_closed_date'];

        $formattedDealClosedDate = Carbon::createFromFormat('d-m-Y', $dealClosedDate);

        $mondayApiKey = env('MONDAY_API_KEY'); // Get the API Key that are stored in the .env files. Under no circumstances will this API Key to be exposed to the client.  

        /* 
            Create new board logic
        */

        // $createBoardQuery = <<< GQL

        // mutation {

        //     create_board (

        //     board_name: "", 

        //     board_kind: public

        //     workspace_id: 11202839

        // ) {

        //         id

        //     }

        // }

        // GQL;

        /* 
            Create new Group Logic
        */

        try {

            $dealName = Str::title($unescapedDealsData['deal_name']);

            $groupName = $dealName;

            // Extract the Group name from Deals Name

            preg_match('/(\d{1,2})\s+To\s+(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})/', $dealName, $matches);

            if ($matches) {

                $startDay = $matches[1]; // 16

                $endDay = $matches[2];   // 18

                $month = $matches[3];    // June

                $year = $matches[4];     // 2025


                // Build full dates

                $startDate = Carbon::createFromFormat('d F Y', "$startDay $month $year");

                $endDate = Carbon::createFromFormat('d F Y', "$endDay $month $year");
            }

            $createGroupQuery = <<< GQL
            
            mutation {
            
                create_group (
                
                    board_id: 9348513723,
                    
                    group_name: "$groupName", 
                    
                    ) {
                    
                        id
                    
                    }
                
                }

        GQL;

            $client = new Client();

            // Create Group

            $groupResponse = $client->post('https://api.monday.com/v2', [

                'headers' => [

                    'Authorization' => $mondayApiKey,

                    'Content-Type' => 'application/json',

                    'Accepts' => 'application/json'

                ],

                'body' => json_encode([

                    'query' => $createGroupQuery

                ])

            ]);

            $groupData = json_decode($groupResponse->getBody(), true);

            $groupId = $groupData['data']['create_group']['id'];

            // Create Items in a Group for the First 5 Rows

            $firstItemColumnValues = json_encode([

                'color_mkrt3w8s' => ['index' => 3],

                'date_mkrter2k' => $formattedDealClosedDate->format('Y-m-d')

            ]);

            $firstEscapedColumnValues = addslashes($firstItemColumnValues);

            $secondItemColumnValues = json_encode([

                'color_mkrt3w8s' => ['index' => 3],

                'date_mkrter2k' => $formattedDealClosedDate->format('Y-m-d')

            ]);

            $secondEscapedColumnValues = addslashes($secondItemColumnValues);

            $thirdItemColumnValues = json_encode([

                'color_mkrt3w8s' => ['index' => 3],

                'date_mkrter2k' => $formattedDealClosedDate->copy()->addDays(3)->format('Y-m-d')

            ]);

            $thirdEscapedColumnValues = addslashes($thirdItemColumnValues);

            $fourthItemColumnValues = json_encode([

                'color_mkrt3w8s' => ['index' => 3],

                'date_mkrter2k' => $formattedDealClosedDate->copy()->addDays(4)->format('Y-m-d')

            ]);

            $fourthEscapedColumnValues = addslashes($fourthItemColumnValues);

            $fifthItemColumnValues = json_encode([

                'color_mkrt3w8s' => ['index' => 3],

                'date_mkrter2k' => $formattedDealClosedDate->copy()->addDays(7)->format('Y-m-d')

            ]);

            $fifthEscapedColumnValues = addslashes($fifthItemColumnValues);

            $sixthItemColumnValues = json_encode([

                'color_mkrt3w8s' => ['index' => 3],

                'date_mkrter2k' => $startDate->copy()->subDays(60)->format('Y-m-d')

            ]);

            $sixthEscapedColumnValues = addslashes($sixthItemColumnValues);

            $seventhItemColumnValues = json_encode([

                'color_mkrt3w8s' => ['index' => 3],

                'date_mkrter2k' => $startDate->copy()->subDays(55)->format('Y-m-d')

            ]);

            $seventhEscapedColumnValues = addslashes($seventhItemColumnValues);

            $eighthItemColumnValues = json_encode([

                'color_mkrt3w8s' => ['index' => 3],

                'date_mkrter2k' => $startDate->copy()->subDays(50)->format('Y-m-d')

            ]);

            $eighthEscapedColumnValues = addslashes($eighthItemColumnValues);

            $ninthItemColumnValues = json_encode([

                'color_mkrt3w8s' => ['index' => 3],

                'date_mkrter2k' => $startDate->copy()->subDays(50)->format('Y-m-d')

            ]);

            $ninthEscapedColumnValues = addslashes($ninthItemColumnValues);

            $tenthItemColumnValues = json_encode([

                'color_mkrt3w8s' => ['index' => 3],

                'date_mkrter2k' => $startDate->copy()->subDays(30)->format('Y-m-d')

            ]);

            $tenthEscapedColumnValues = addslashes($tenthItemColumnValues);

            $eleventhItemColumnValues = json_encode([

                'color_mkrt3w8s' => ['index' => 3],

                'date_mkrter2k' => $startDate->copy()->subDays(27)->format('Y-m-d')

            ]);

            $eleventhEscapedColumnValues = addslashes($eleventhItemColumnValues);

            $twelfthItemColumnValues = json_encode([

                'color_mkrt3w8s' => ['index' => 3],

                'date_mkrter2k' => $startDate->copy()->subDays(26)->format('Y-m-d')

            ]);

            $twelfthEscapedColumnValues = addslashes($twelfthItemColumnValues);

            $thirteenthItemColumnValues = json_encode([

                'color_mkrt3w8s' => ['index' => 3],

                'date_mkrter2k' => $startDate->copy()->subDays(21)->format('Y-m-d')

            ]);

            $thirteenthEscapedColumnValues = addslashes($thirteenthItemColumnValues);

            $fourteenthItemColumnValues = json_encode([

                'color_mkrt3w8s' => ['index' => 3],

                'date_mkrter2k' => $startDate->copy()->subDays(20)->format('Y-m-d')

            ]);

            $fourteenthEscapedColumnValues = addslashes($fourteenthItemColumnValues);

            $fifteenthItemColumnValues = json_encode([

                'color_mkrt3w8s' => ['index' => 3],

                'date_mkrter2k' => $startDate->copy()->subDays(14)->format('Y-m-d')

            ]);

            $fifteenthEscapedColumnValues = addslashes($fifteenthItemColumnValues);

            $sixteenthItemColumnValues = json_encode([

                'color_mkrt3w8s' => ['index' => 3],

                'date_mkrter2k' => $startDate->copy()->subDays(14)->format('Y-m-d')

            ]);

            $sixteenthEscapedColumnValues = addslashes($sixteenthItemColumnValues);

            $mondayQuery = <<< GQL

                mutation{

                    item1: create_item(

                        board_id: 9348513723,

                        group_id: "$groupId",

                        item_name: "Booking confirmation - sent to customer",

                        column_values: "$firstEscapedColumnValues"

                    ) {

                        id

                        name

                    }
                        item2: create_item(

                        board_id: 9348513723,

                        group_id: "$groupId",

                        item_name: "DP Invoice 1 - sent to customer",

                        column_values: "$secondEscapedColumnValues"

                    ) {

                        id

                        name

                    }
                        item3: create_item(

                        board_id: 9348513723,

                        group_id: "$groupId",

                        item_name: "DP Invoice 1 REMINDER - sent to customer",

                        column_values: "$thirdEscapedColumnValues"

                    ) {

                        id

                        name

                    }
                        item4: create_item(

                        board_id: 9348513723,

                        group_id: "$groupId",

                        item_name: "DP Invoice 1 - await payment received",

                        column_values: "$fourthEscapedColumnValues"

                    ) {

                        id

                        name

                    }
                        item5: create_item(

                        board_id: 9348513723,

                        group_id: "$groupId",

                        item_name: "DP Invoice 1 - confirm payment to customer",

                        column_values: "$fifthEscapedColumnValues"

                    ) {

                        id

                        name

                    }
                        item6: create_item(

                        board_id: 9348513723,

                        group_id: "$groupId",

                        item_name: "GIF - sent to customer",

                        column_values: "$sixthEscapedColumnValues"

                    ) {

                        id

                        name

                    }
                        item7: create_item(

                        board_id: 9348513723,

                        group_id: "$groupId",

                        item_name: "GIF REMINDER - sent to customer",

                        column_values: "$seventhEscapedColumnValues"

                    ) {

                        id

                        name

                    }
                        item8: create_item(

                        board_id: 9348513723,

                        group_id: "$groupId",

                        item_name: "GIF  - received from customer",

                        column_values: "$eighthEscapedColumnValues"

                    ) {

                        id

                        name

                    }
                        item9: create_item(

                        board_id: 9348513723,

                        group_id: "$groupId",

                        item_name: "PASSPORT  - received from customer",

                        column_values: "$sixthEscapedColumnValues"

                    ) {

                        id

                        name

                    }
                        item10: create_item(

                        board_id: 9348513723,

                        group_id: "$groupId",

                        item_name: "BALANCE Invoice 2 - sent to customer",

                        column_values: "$tenthEscapedColumnValues"

                    ) {

                        id

                        name

                    }
                        item11: create_item(

                        board_id: 9348513723,

                        group_id: "$groupId",

                        item_name: "BALANCE Invoice 2 REMINDER - sent to customer",

                        column_values: "$eleventhEscapedColumnValues"

                    ) {

                        id

                        name

                    }
                        item12: create_item(

                        board_id: 9348513723,

                        group_id: "$groupId",

                        item_name: "BALANCE Invoice 2 - await payment received",

                        column_values: "$twelfthEscapedColumnValues"

                    ) {

                        id

                        name

                    }
                        item13: create_item(

                        board_id: 9348513723,

                        group_id: "$groupId",

                        item_name: "BALANCE Invoice 2 - confirm payment to customer",

                        column_values: "$thirteenthEscapedColumnValues"

                    ) {

                        id

                        name

                    }
                        item14: create_item(

                        board_id: 9348513723,

                        group_id: "$groupId",

                        item_name: "GUEST SHEET - create",

                        column_values: "$fourteenthEscapedColumnValues"

                    ) {

                        id

                        name

                    }
                        item15: create_item(

                        board_id: 9348513723,

                        group_id: "$groupId",

                        item_name: "GUEST SHEET - send to crew",

                        column_values: "$fifteenthEscapedColumnValues"

                    ) {

                        id

                        name

                    }
                        item16: create_item(

                        board_id: 9348513723,

                        group_id: "$groupId",

                        item_name: "GUEST REMINDER - send trip reminder",

                        column_values: "$sixteenthEscapedColumnValues"

                    ) {

                        id

                        name

                    }

                }
            GQL;

            $itemResponse = $client->post('https://api.monday.com/v2', [

                'headers' => [

                    'Authorization' => $mondayApiKey,

                    'Content-Type' => 'application/json',

                    'Accepts' => 'application/json'

                ],

                'body' => json_encode([

                    'query' => $mondayQuery

                ])

            ]);

            return response()->json([

                'status' => 'success',

                'message' => 'Group and items created successfully',

                'group' => $groupData,

                'items' => json_decode($itemResponse->getBody(), true),

            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {

            return response()->json([

                'status' => 'error',

                'message' => $e->getMessage()

            ], 500);
        }
    }
}
