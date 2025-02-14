<?php

if (!empty($oss_config) && $oss_config !== 'origin') {
    test('skip this testfile because not valid in your local test configuration');
    goto end_oss_origin;
}

test_start('test taxrates unknown country');
try {
    $request = $lexoffice->get_taxrates('ZZ', strtotime('2021-07-05'));
    if ($request['default'] === null) {
        test_finished(true);
    } else {
        var_dump($request);
        test_finished(false);
    }
}
catch (LexofficeException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test taxrates country DE');
try {
    $request = $lexoffice->get_taxrates('DE', strtotime('2021-07-05'));
    if (!empty($request) && $request['default'] == 19 && in_array(7, $request['reduced'])) {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (LexofficeException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test taxrates country nl - before oss');
try {
    $request = $lexoffice->get_taxrates('nl', strtotime('2021-06-05'));
    if (!empty($request) && $request['default'] == 19 && in_array(7, $request['reduced'])) {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (LexofficeException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test taxrates country nl - after oss');
try {
    $request = $lexoffice->get_taxrates('nl', strtotime('2021-07-05'));
    if (!empty($request) && $request['default'] == 21 && in_array(9, $request['reduced'])) {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (LexofficeException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test oss settings - DE');
try {
    $request = $lexoffice->is_oss_needed('DE', strtotime('2021-07-05'));
    if ($request === false) {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (LexofficeException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test oss settings - NL');
try {
    $request = $lexoffice->is_oss_needed('NL', strtotime('2021-07-05'));
    if ($request === 'origin') {
        test_finished(true);
    } else {
        var_dump($request);
        test_finished(false);
    }
}
catch (LexofficeException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test oss settings - NL - before oss');
try {
    $request = $lexoffice->is_oss_needed('NL', strtotime('2021-06-05'));
    if ($request === false) {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (LexofficeException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test oss settings - ZZ');
try {
    $request = $lexoffice->is_oss_needed('ZZ', strtotime('2021-07-05'));
    if ($request === false) {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (LexofficeException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test oss settings - GB');
try {
    $request = $lexoffice->is_oss_needed('GB', strtotime('2021-07-05'));
    if ($request === false) {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (LexofficeException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test oss voucher category - GB');
try {
    $request = $lexoffice->get_oss_voucher_category('GB', strtotime('2021-07-05'));
    test_finished(false);
}
catch (LexofficeException $e) {
    if ($e->getMessage() === 'lexoffice-php-api: no possible OSS voucher category id') {
        test_finished(true);
    } else {
        test_finished(false);
    }
}

test_start('test oss voucher category - DE');
try {
    $request = $lexoffice->get_oss_voucher_category('DE', strtotime('2021-07-05'));
    test_finished(false);
}
catch (LexofficeException $e) {
    if ($e->getMessage() === 'lexoffice-php-api: no possible OSS voucher category id') {
        test_finished(true);
    } else {
        test_finished(false);
    }
}

test_start('test oss voucher category - NL, physical');
try {
    $request = $lexoffice->get_oss_voucher_category('NL', strtotime('2021-07-05'), 1);
    if ($request === '7c112b66-0565-479c-bc18-5845e080880a') {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (LexofficeException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('test oss voucher category - NL, service');
try {
    $request = $lexoffice->get_oss_voucher_category('NL', strtotime('2021-07-05'), 2);
    if ($request === 'd73b880f-c24a-41ea-a862-18d90e1c3d82') {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (LexofficeException $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check Innergemeinschaftliche Lieferung');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(0, 'PT', strtotime('2021-07-05'), true, true);
    if ($request === '9075a4e3-66de-4795-a016-3889feca0d20') {
        test_finished(true);
    } else {
        test_finished(false);
    }
}
catch (LexofficeException $e) {
    test(print_r($e->get_error(), true));
    test_finished(false);
}

test_start('check Fernverkauf | vatid but not business, physical');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(23, 'PT', strtotime('2021-07-05'), true, false, true);
    test_finished(false);
}
catch (LexofficeException $e) {
    test_finished($e->getMessage() === 'lexoffice-php-api: invalid OSS taxrate for given country');
}

test_start('check Fernverkauf | vatid but not business, service');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(23, 'PT', strtotime('2021-07-05'), true, false, false);
    test_finished(false);
}
catch (LexofficeException $e) {
    test_finished($e->getMessage() === 'lexoffice-php-api: invalid OSS taxrate for given country');
}

end_oss_origin: