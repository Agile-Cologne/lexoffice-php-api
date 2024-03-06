<?php
$lexoffice->test_set_profile('net', false, 'ORIGIN');

test_start('check voucher booking id - germany sell before oss');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(0, 'de', strtotime('2021-06-27'), false, true, true);
    test_finished($request === '8f8664a8-fd86-11e1-a21f-0800200c9a66');
}
catch (lexoffice_exception $e) {
    test($e->getMessage());
    test_finished(false);
}

test_start('check voucher booking id - romania sell after oss - 19%');
try {
    $request = $lexoffice->get_needed_voucher_booking_id(19, 'ro', strtotime('2021-12-27'), false, true, false);
    test_finished($request === 'd73b880f-c24a-41ea-a862-18d90e1c3d82');
}
catch (lexoffice_exception $e) {
    test($e->getMessage());
    test_finished(false);
}

$lexoffice->test_clear_profile();