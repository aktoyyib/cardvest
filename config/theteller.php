<?php

return [
    'userName' => env('THETELLER_USERNAME'),
    'apiKey' => env('THETELLER_API_KEY'),
    'baseUrl' => env('THETELLER_BASE_URL'),
    'merchantID' => env('THETELLER_MERCHANT_ID'),
    'passCode' => env('THETELLER_PASSCODE'),


    'banks' => [
        array('code' => 'SCH', 'name' => 'STANDARD CHARTERED BANK'),
        array('code' => 'ABG', 'name' => 'ABSA BANK GHANA LIMITED'),
        array('code' => 'GCB', 'name' => 'GCB BANK LIMITED'),
        array('code' => 'NIB', 'name' => 'NATIONAL INVESTMENT BANK'),
        array('code' => 'ADB', 'name' => 'AGRICULTURAL DEVELOPMENT BANK'),
        array('code' => 'UMB', 'name' => 'UNIVERSAL MERCHANT BANK'),
        array('code' => 'RBL', 'name' => 'REPUBLIC BANK LIMITED'),
        array('code' => 'ZEN', 'name' => 'ZENITH BANK GHANA LTD'),
        array('code' => 'ECO', 'name' => 'ECOBANK GHANA LTD'),
        array('code' => 'CAL', 'name' => 'CAL BANK LIMITED'),
        array('code' => 'PRD', 'name' => 'PRUDENTIAL BANK LTD'),
        array('code' => 'STB', 'name' => 'STANBIC BANK'),
        array('code' => 'GTB', 'name' => 'GUARANTY TRUST BANK'),
        array('code' => 'UBA', 'name' => 'UNITED BANK OF AFRICA'),
        array('code' => 'ACB', 'name' => 'ACCESS BANK LTD'),
        array('code' => 'CBG', 'name' => 'CONSOLIDATED BANK GHANA'),
        array('code' => 'FNB', 'name' => 'FIRST NATIONAL BANK'),
        array('code' => 'UNL', 'name' => 'UNITY LINK'),
        array('code' => 'FDL', 'name' => 'FIDELITY BANK LIMITED'),
        array('code' => 'SIS', 'name' => 'SERVICES INTEGRITY SAVINGS & LOANS'),
        array('code' => 'BOA', 'name' => 'BANK OF AFRICA'),
        array('code' => 'DFL', 'name' => 'DALEX FINANCE AND LEASING COMPANY'),
        array('code' => 'FBO', 'name' => 'FIRST BANK OF NIGERIA'),
        array('code' => 'GHL', 'name' => 'GHL Bank'),
        array('code' => 'BOG', 'name' => 'BANK OF GHANA'),
        array('code' => 'FAB', 'name' => 'FIRST ATLANTIC BANK'),
        array('code' => 'SSB', 'name' => 'SAHEL - SAHARA BANK (BSIC)'),
        array('code' => 'GMY', 'name' => 'G-MONEY'),
        array('code' => 'APX', 'name' => 'ARB APEX BANK LIMITED'),
    ]
];