<?php
$arrSold = array(0 => "Not Sell", 1 => "Main Site", 2 => "Battery Site", 3 => "AC Adapters Site");

$specUSB = array(0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6);
$specSerial = array(0 => 0, 1 => 1, 2 => 2);
$specYesNo = array(0 => "NO", 1 => "YES");
$specVgaOB = array(1 => "Shared", 2 => "Dedicated");
$specSoundOB = array(0 => "Onboard", 1 => "PCI");
$specNet = array(0 => "NONE", 1 => "10/100", 2 => "10/100/1000 Gigabit");
$specWarranty = array(1 => "3 Months", 2 => "6 Months", 3 => "1 YEAR", 4 => "2 YEARS", 5 => "3 YEARS");

$arrSpecCategs = array(1 => "CPU", 2 => "Display", 3 => "RAM", 4 => "Video Card", 5 => "HD"
												, 6 => "Sound Card", 7 => "Optical", 8 => "Operating System");

$arrSpecCategsD = array(1 => "CPU Type", 3 => "RAM Type", 4 => "VGA Chipset", 5 => "HD Type"
												, 6 => "Sound Card", 7 => "Optical", 8 => "Operating System", 9 => "CPU Speed"
												, 10 => "RAM Amount", 11 => "HD Size", 12 => "VGA Slot", 13 => "VGA Memory"
												, 14 => "Network", 15 => "Extras");

$pathprodpics = "pics/";
$pathmfpics = "pics/";


$_STATE = [
    
    1	=> [ "Name" => "Waiting To Send", "Color"=>"black"],
    2	=> [ "Name" => "Sent To China", "Color"=>"green"],
    3	=> [ "Name" => "Sent to UK", "Color"=>"green"],
    7	=> [ "Name" => "Returned Fixed", "Color"=>"darkgreen"],
    9	=> [ "Name" => "Returned Unfixed", "Color"=>"red"],
];


$_RECORD = [
    
    1	=> "Ordered",
    2	=> "Received",    
];


$_ADVERTISED = [
    
    1	=> ["Name" => "Listed", "Color" => "green"],
    2	=> ["Name" => "Listed (Ebay Only)", "Color" => "green"],
    3	=> ["Name" => "Listed (Ebay UK+DE)", "Color" => "green"],
    4	=> ["Name" => "Sold", "Color" => "green"],
];

$_STATUSES = [
    0 => ["Name" => "NO CHANGE", "Color" => "#000000"],
    1 => ["Name" => "Awaiting Diagnosis", "Color" => "#FF8600"],
    2 => ["Name" => "Repair Needed", "Color" => "#A923DC" ],
    3 => ["Name" => "Parts Needed", "Color" => "#EB5256" ],
    4 => ["Name" => "Parts Ordered", "Color" => "#1CC6E3" ],
    5 => ["Name" => "Motherboard Unfixed", "Color" => "#1603FC" ],
    6 => ["Name" => "Needs Cleanup/OS", "Color" => "#05E600" ],
    7 => ["Name" => "Ready to Sell", "Color" => "#0C7E14" ],
    8 => ["Name" => "To Be Stripped", "Color" => "#000000" ],
    9 => ["Name" => "Dispatched", "Color" => "#9E9E9E" ],
    11 => ["Name" => "Out-of-house Repair", "Color" => "#B46932" ],

    17 => ["Name" => "Action Requested", "Color" => "maroon"],
    18 => ["Name" => "Action Completed", "Color" => "maroon"],

    16 => ["Name" => "Sold/Awaiting Despatch", "Color" => "#111"],
    24 => ["Name" => "Stripped", "Color" => "red"]
];

$_ACSTATUSES = [
    0 => ["Name" => "NO CHANGE", "Color" => "#000000"],
    1 => ["Name" => "Awaiting Diagnosis", "Color" => "#FF8600"],
    2 => ["Name" => "Repair Needed", "Color" => "#A923DC" ],
    3 => ["Name" => "Needs Parts", "Color" => "#EB5256" ],
    4 => ["Name" => "Parts Ordered", "Color" => "#1CC6E3" ],
	5 => ["Name" => "Motherboard Unfixed", "Color" => "#1603FC" ],
    22 => ["Name" => "Ready To Sell - Grade A", "Color" => "#05E600" ],
    6 => ["Name" => "Ready To Sell - Grade B", "Color" => "#05E600" ],
    7 => ["Name" => "Ready to Sell - New / New Other", "Color" => "#0C7E14" ],
    8 => ["Name" => "Write Off", "Color" => "#000000" ],
    11 => ["Name" => "Out-of-house Repair", "Color" => "#B46932" ],
    16 => ["Name" => "Sold", "Color" => "#111"],
    24 => ["Name" => "Stripped", "Color" => "pink"],
    29 => ["Name"=> "Sent to FBA", "Color"=> "#123321"],
    43 => ["Name" => "Return To Supplier", "Color"=>"red"],
    44 => ["Name" => "Sent To Supplier", "Color"=>"#151515"]
];

$_NPSTATUSES = [
    0 => ["Name" => "NO CHANGE", "Color" => "#000000"],
    1 => ["Name" => "Returned By Customer", "Color" => "#FF8600"],
    2 => ["Name" => "Repair Needed", "Color" => "#A923DC" ],
    3 => ["Name" => "Tested Faulty - Return To Supplier", "Color" => "#EB5256" ],
    4 => ["Name" => "Used Internally", "Color" => "#1CC6E3" ],
	5 => ["Name" => "Motherboard Unfixed", "Color" => "#1603FC" ],
    22 => ["Name" => "Ready To Sell - Grade A", "Color" => "#05E600" ],
    6 => ["Name" => "Ready To Sell - Grade B", "Color" => "#05E600" ],
    7 => ["Name" => "Ready to Sell - New / New Other", "Color" => "#0C7E14" ],
    8 => ["Name" => "Write Off", "Color" => "#000000" ],
    11 => ["Name" => "Out-of-house Repair", "Color" => "#B46932" ],
    16 => ["Name" => "Sold", "Color" => "#111"],
    24 => ["Name" => "Stripped", "Color" => "pink"],
    29 => ["Name"=> "Sent to FBA", "Color"=> "#123321"],
    43 => ["Name" => "Return To Supplier", "Color"=>"red"],
    44 => ["Name" => "Sent To Supplier", "Color"=>"#151515"]
];



$_STOCKCONFIG = [];
$_STOCKCONFIG["orange"] =       [ "title" => "STOCK AWAITING DIAGNOSIS", "status" => [ 0, 2, 3, 6, 8 ], "comment_status"=>true ];
$_STOCKCONFIG["purple"] =       [ "title" => "FAULTY STOCK AWAITING REPAIR", "status" => [ 0, 3, 6, 8, 11 ], "comment_status"=>true ];
$_STOCKCONFIG["red"] =          [ "title" => "FAULTY STOCK AWAITING PARTS", "status" => [ 0, 2, 4, 6, 8, 11 ], "comment_status"=>true ];
$_STOCKCONFIG["lightblue"] =    [ "title" => "FAULTY STOCK, PARTS ORDERED", "status" => [ 0, 2, 3, 5, 6, 8 ], "comment_status"=>true ];
$_STOCKCONFIG["darkblue"] =     [ "title" => "MOTHERBOARD RETURNED UNFIXED", "status" => [ 0, 2, 3, 6, 8 ], "comment_status"=>true ];
$_STOCKCONFIG["lightgreen"] =   [ "title" => "ITEM REPAIRED, AWAITING CLEANUP/OS INSTALL", "status" => [ 0, 2, 3, 7, 11 ], "comment_status"=>true ];
$_STOCKCONFIG["darkgreen"] =    [ "title" => "READY TO SELL STOCK", "status" => [ 0, 2, 3, 6, 8 ], "comment_status"=>true ];
$_STOCKCONFIG["sold"] =         [ "title" => "SOLD STOCK", "status" => [], "comment_status"=>false ];
$_STOCKCONFIG["black"] =        [ "title" => "WRITTEN OFF STOCK", "status" => [ 0, 2, 3, 6, 11, 24], "comment_status"=>true ];
$_STOCKCONFIG["stripped"] =     [ "title" => "STRIPPED", "status" => [ 0,8 ], "comment_status"=>true ];
$_STOCKCONFIG["gray"] =         [ "title" => "ITEM DISPATCHED", "status" => [ 0, 3, 6, 8, 11 ], "comment_status"=>true ];
$_STOCKCONFIG["action"] =       [ "title" => "ACTIONS REQUESTED", "status" => [], "comment_status"=>true ];
$_STOCKCONFIG["actioncmp"] =    [ "title" => "REQUESTED ACTIONS COMPLETED", "status" => [0, 2, 3, 5, 6, 8, 11, 24], "comment_status"=>true ];
$_STOCKCONFIG["brown"] =        [ "title" => "OUT-OF-HOUSE REPAIR", "status" => [ 0, 2, 3, 5, 6, 8 ], "comment_status"=>true ];
$_STOCKCONFIG["search"] =       [ "title" => "SEARCH RESULTS", "status" => [], "comment_status"=>false ];

$_ASTOCKCONFIG = [];
$_ASTOCKCONFIG["orange"] =       [ "title" => "STOCK AWAITING DIAGNOSIS", "status" => [ 0, 2, 3, 6, 22,8 ], "comment_status"=>true ];
$_ASTOCKCONFIG["purple"] =       [ "title" => "FAULTY STOCK AWAITING REPAIR", "status" => [ 0, 3, 6, 8, 11 ], "comment_status"=>true ];
$_ASTOCKCONFIG["red"] =          [ "title" => "FAULTY STOCK AWAITING PARTS", "status" => [ 0, 2, 4, 6, 8, 11 ], "comment_status"=>true ];
$_ASTOCKCONFIG["lightblue"] =    [ "title" => "FAULTY STOCK, PARTS ORDERED", "status" => [ 0, 2, 3, 5, 6, 8 ], "comment_status"=>true ];
$_ASTOCKCONFIG["lightgreen"] =   [ "title" => "READY TO SELL (GRADE B)", "status" => [ 0, 2, 3, 7, 11 ], "comment_status"=>true ];
$_ASTOCKCONFIG["green"] =        [ "title" => "READY TO SELL (GRADE A)", "status" => [ 0, 2, 3, 7, 11 ], "comment_status"=>true ];
$_ASTOCKCONFIG["darkgreen"] =    [ "title" => "READY TO SELL (New)", "status" => [ 0, 2, 3, 6, 8 ], "comment_status"=>true ];
$_ASTOCKCONFIG["sold"] =         [ "title" => "SOLD STOCK", "status" => [], "comment_status"=>false ];
$_ASTOCKCONFIG["black"] =        [ "title" => "WRITTEN OFF STOCK", "status" => [ 0, 2, 3, 6, 11, 24], "comment_status"=>true ];
$_ASTOCKCONFIG["senttofba"] =    [ "title" => "Sent to FBA", "status" => [ 0, 2, 3, 6, 8 ], "comment_status"=>true ];
$_ASTOCKCONFIG["brown"] =        [ "title" => "OUT-OF-HOUSE REWORK", "status" => [ 0, 2, 3, 5, 6, 8 ], "comment_status"=>true ];
$_ASTOCKCONFIG["rts"] =    [ "title" => "Return To Supplier", "status" => [ 0, 2, 3, 6, 8 ], "comment_status"=>true ];
$_ASTOCKCONFIG["rfs"] =    [ "title" => "Sent To Supplier", "status" => [ 0, 2, 3, 6, 8 ], "comment_status"=>true ];
$_ASTOCKCONFIG["search"] =       [ "title" => "SEARCH RESULTS", "status" => [], "comment_status"=>false ];


$_NPSTOCKCONFIG = [];
$_NPSTOCKCONFIG["orange"] =       [ "title" => "RETURNED BY CUSTOMER", "status" => [ 0, 2, 3, 6, 22,8 ], "comment_status"=>true ];
$_NPSTOCKCONFIG["darkgreen"] =    [ "title" => "READY TO SELL (New)", "status" => [ 0, 2, 3, 6, 8 ], "comment_status"=>true ];
$_NPSTOCKCONFIG["sold"] =         [ "title" => "SOLD STOCK", "status" => [], "comment_status"=>false ];
$_NPSTOCKCONFIG["brown"] =        [ "title" => "OUT-OF-HOUSE WORK", "status" => [ 0, 2, 3, 5, 6, 8 ], "comment_status"=>true ];
$_NPSTOCKCONFIG["red"] =          [ "title" => "FAULTY - RETURN TO SUPPLIER", "status" => [ 0, 2, 4, 6, 8, 11 ], "comment_status"=>true ];
$_NPSTOCKCONFIG["lightblue"] =    [ "title" => "USED INTERNALLY", "status" => [ 0, 2, 3, 5, 6, 8 ], "comment_status"=>true ];


$_RMACSTOCKCONFIG = [];
$_RMACSTOCKCONFIG["orange"] =       [ "title" => "STOCK AWAITING DIAGNOSIS", "status" => [ 0, 2, 3, 6, 53,8 ], "comment_status"=>true ];
$_RMACSTOCKCONFIG["purple"] =       [ "title" => "REPLACED FOR CUSTOMER WITHOUT RETURN", "status" => [ 0, 3, 6, 8, 54 ], "comment_status"=>true ];
$_RMACSTOCKCONFIG["yellow"] =       [ "title" => "FAULTY STOCK AWAITING PARTS", "status" => [ 0, 3, 6, 8, 54 ], "comment_status"=>true ];
$_RMACSTOCKCONFIG["red"] =          [ "title" => "FAULTY STOCK AWAITING PARTS", "status" => [ 0, 2, 4, 6, 8, 54 ], "comment_status"=>true ];
$_RMACSTOCKCONFIG["darkgreen"] =    [ "title" => "CONFIRMED OK", "status" => [ 0, 2, 3, 6, 8 ], "comment_status"=>true ];
$_RMACSTOCKCONFIG["dakgray"] =        [ "title" => "RESOLVED", "status" => [ 0, 2, 3, 6, 54, 55], "comment_status"=>true ];
$_RMACSTOCKCONFIG["maroon"] =    [ "title" => "RETURNED REFUSED BY SUPPLIER", "status" => [ 0, 2, 3, 6, 8 ], "comment_status"=>true ];
$_RMACSTOCKCONFIG["indigo"] =    [ "title" => "CREDITED BY SUPPLIER", "status" => [ 0, 2, 3, 6, 8 ], "comment_status"=>true ];
$_RMACSTOCKCONFIG["cyan"] =    [ "title" => "REJECTED BY SUPPLIER", "status" => [ 0, 2, 3, 6, 8 ], "comment_status"=>true ];
$_RMACSTOCKCONFIG["pink"] =    [ "title" => "COURIER CLAIM", "status" => [ 0, 2, 3, 6, 8 ], "comment_status"=>true ];
$_RMACSTOCKCONFIG["azure"] =    [ "title" => "RETURNED TO SUPPLIER", "status" => [ 0, 2, 3, 6, 8 ], "comment_status"=>true ];
$_RMACSTOCKCONFIG["search"] =       [ "title" => "SEARCH RESULTS", "status" => [], "comment_status"=>false ];



$_RMACSTATUSES = [
    0 => ["Name" => "NO CHANGE", "Color" => "#000000"],
    1 => ["Name" => "Awaiting Diagnosis", "Color" => "#FF8600"],
    2 => ["Name" => "Replaced for Customer Without Return", "Color" => "purple" ],    
    3 => ["Name" => "Faulty Needing Parts", "Color" => "#EB5256" ],    
    4 => ["Name" => "Returned to Supplier", "Color" => "azure" ],
	5 => ["Name" => "Credited by Supplier", "Color" => "indigo" ],    
    6 => ["Name" => "Courier Claim", "Color" => "pink" ],
    7 => ["Name" => "Confirmed OK", "Color" => "darkgreen" ],
    8 => ["Name" => "Rejected by Supplier", "Color" => "cyan" ],
    54 => ["Name" => "Return Refused by Supplier", "Color"=>"maroon"],
    53 => ["Name" => "Faulty Needing Parts", "Color" => "yellow" ],
    55 => ["Name" => "Resolved", "Color"=>"darkgray"]
];