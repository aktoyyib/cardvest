<?php

return [
    'userName' => env('THETELLER_USERNAME'),
    'apiKey' => env('THETELLER_API_KEY'),
    'baseUrl' => env('THETELLER_BASE_URL'),
    'merchantID' => env('THETELLER_MERCHANT_ID'),
    'passCode' => env('THETELLER_PASSCODE'),


    'banks' => [
        // Add mobile money as banks too
        // The payment service understands the processing
        array('code' => 'MTN', 'name' => 'MTN MOBILE WALLET'),
        array('code' => 'ATL', 'name' => 'AIRTEL MOBILE WALLET'),
        array('code' => 'VDF', 'name' => 'VODAFONE MOBILE WALLET'),
        array('code' => 'TGO', 'name' => 'TIGO MOBILE WALLET'),

        // Below represents actual banks
        // The first 3 char tells it bank transfer
        // The last 3 char tells the actual bank
        array('code' => 'GIP.SCH', 'name' => 'STANDARD CHARTERED BANK'),
        array('code' => 'GIP.ABG', 'name' => 'ABSA BANK GHANA LIMITED'),
        array('code' => 'GIP.GCB', 'name' => 'GCB BANK LIMITED'),
        array('code' => 'GIP.NIB', 'name' => 'NATIONAL INVESTMENT BANK'),
        array('code' => 'GIP.ADB', 'name' => 'AGRICULTURAL DEVELOPMENT BANK'),
        array('code' => 'GIP.UMB', 'name' => 'UNIVERSAL MERCHANT BANK'),
        array('code' => 'GIP.RBL', 'name' => 'REPUBLIC BANK LIMITED'),
        array('code' => 'GIP.ZEN', 'name' => 'ZENITH BANK GHANA LTD'),
        array('code' => 'GIP.ECO', 'name' => 'ECOBANK GHANA LTD'),
        array('code' => 'GIP.CAL', 'name' => 'CAL BANK LIMITED'),
        array('code' => 'GIP.PRD', 'name' => 'PRUDENTIAL BANK LTD'),
        array('code' => 'GIP.STB', 'name' => 'STANBIC BANK'),
        array('code' => 'GIP.GTB', 'name' => 'GUARANTY TRUST BANK'),
        array('code' => 'GIP.UBA', 'name' => 'UNITED BANK OF AFRICA'),
        array('code' => 'GIP.ACB', 'name' => 'ACCESS BANK LTD'),
        array('code' => 'GIP.CBG', 'name' => 'CONSOLIDATED BANK GHANA'),
        array('code' => 'GIP.FNB', 'name' => 'FIRST NATIONAL BANK'),
        array('code' => 'GIP.UNL', 'name' => 'UNITY LINK'),
        array('code' => 'GIP.FDL', 'name' => 'FIDELITY BANK LIMITED'),
        array('code' => 'GIP.SIS', 'name' => 'SERVICES INTEGRITY SAVINGS & LOANS'),
        array('code' => 'GIP.BOA', 'name' => 'BANK OF AFRICA'),
        array('code' => 'GIP.DFL', 'name' => 'DALEX FINANCE AND LEASING COMPANY'),
        array('code' => 'GIP.FBO', 'name' => 'FIRST BANK OF NIGERIA'),
        array('code' => 'GIP.GHL', 'name' => 'GHL Bank'),
        array('code' => 'GIP.BOG', 'name' => 'BANK OF GHANA'),
        array('code' => 'GIP.FAB', 'name' => 'FIRST ATLANTIC BANK'),
        array('code' => 'GIP.SSB', 'name' => 'SAHEL - SAHARA BANK (BSIC)'),
        array('code' => 'GIP.GMY', 'name' => 'G-MONEY'),
        array('code' => 'GIP.APX', 'name' => 'ARB APEX BANK LIMITED'),
    ]
];
