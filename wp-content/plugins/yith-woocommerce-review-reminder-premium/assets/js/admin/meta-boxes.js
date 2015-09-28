jQuery(function ($) {

    $('body')
        .on('click', 'button.do-send-email', function () {
            if (window.confirm(ywrr_meta_boxes.do_send_email)) {

                var items_to_review = {};

                $('#order_line_items tr').each(function (index, item) {
                    if ($(this).find('input[type=checkbox]').attr('checked')) {
                        items_to_review [$(this).data('order_item_id')] = $(this).data('order_item_id');
                    }
                });

                var data = {
                    action         : 'ywrr_send_request_mail',
                    order_id       : ywrr_meta_boxes.post_id,
                    order_date     : ywrr_meta_boxes.order_date,
                    items_to_review: JSON.stringify(items_to_review, null, '')
                };

                $.post(ywrr_meta_boxes.ajax_url, data, function (response) {
                    if (response === true) {
                        window.alert(ywrr_meta_boxes.after_send_email);
                    } else {
                        window.alert(response.error);
                    }
                });

            }
        })
        .on('click', 'button.do-reschedule-email', function () {
            if (window.confirm(ywrr_meta_boxes.do_reschedule_email)) {

                var items_to_review = {};

                $('#order_line_items tr').each(function (index, item) {
                    if ($(this).find('input[type=checkbox]').attr('checked')) {
                        items_to_review [$(this).data('order_item_id')] = $(this).data('order_item_id');
                    }
                });

                var data = {
                    action         : 'ywrr_reschedule_mail',
                    order_id       : ywrr_meta_boxes.post_id,
                    items_to_review: JSON.stringify(items_to_review, null, '')
                };

                $.post(ywrr_meta_boxes.ajax_url, data, function (response) {
                    if (response === true) {
                        window.alert(ywrr_meta_boxes.after_reschedule_email);
                    } else if (response === 'notfound') {
                        window.alert(ywrr_meta_boxes.not_found_reschedule);
                    } else {
                        window.alert(response.error);
                    }
                });

            }
        })
        .on('click', 'button.do-cancel-email', function () {
            if (window.confirm(ywrr_meta_boxes.do_cancel_email)) {

                var data = {
                    action  : 'ywrr_cancel_mail',
                    order_id: ywrr_meta_boxes.post_id
                };

                $.post(ywrr_meta_boxes.ajax_url, data, function (response) {
                    if (response === true) {
                        window.alert(ywrr_meta_boxes.after_cancel_email);
                    } else if (response === 'notfound') {
                        window.alert(ywrr_meta_boxes.not_found_cancel);
                    } else {
                        window.alert(response.error);
                    }
                });

            }
        })
        .on('click', 'button.do-send-test-email', function () {

            var template = $('#ywrr_mail_template').val() || 'base',
                email = $('#test_email').val(),
                re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (!re.test(email)) {

                window.alert(ywrr_meta_boxes.test_mail_wrong);

            } else {

                var data = {
                    action  : 'ywrr_send_test_mail',
                    email   : email,
                    template: template

                };

                $.post(ywrr_meta_boxes.ajax_url, data, function (response) {

                    if (response === true) {

                        window.alert(ywrr_meta_boxes.after_send_test_email);
                    } else {
                        window.alert(response.error);
                    }

                });

            }

        });


    $(document).ready(function ($) {

        $('select#ywrr_request_type').change(function () {

            var option = $('option:selected', this).val(),
                ywrr_request_number = $('#ywrr_request_number').parent().parent(),
                ywrr_request_criteria = $('#ywrr_request_criteria').parent().parent();

            switch (option) {
                case 'selection':
                    ywrr_request_number.show();
                    ywrr_request_criteria.show();
                    break;
                default:
                    ywrr_request_number.hide();
                    ywrr_request_criteria.hide();
            }

        }).change();

        $('select#ywrr_mail_item_link').change(function () {

            var option = $('option:selected', this).val(),
                ywrr_mail_item_link_hash = $('#ywrr_mail_item_link_hash').parent().parent();

            switch (option) {
                case 'custom':
                    ywrr_mail_item_link_hash.show();
                    break;
                default:
                    ywrr_mail_item_link_hash.hide();
            }

        }).change();

        $('#ywrr_mail_template_enable').change(function () {

            if ($(this).is(':checked')) {

                $('#ywrr_mail_template').val('base').prop("disabled",true);

            } else {

                $('#ywrr_mail_template').prop("disabled",false);

            }

        }).change();

    })
});

