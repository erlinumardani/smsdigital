$('input.form-control,textarea.form-control,select.form-control').bind('keyup blur', function () {
    var input_type = $(this).data('input_type');

    if (input_type == 'alphabet') {
        alphabet_correction(this.id);
    } else if (input_type == 'numeric') {
        numeric_correction(this.id);
    } else if (input_type == 'email') {
        email_correction(this.id);
    } else if (input_type == 'address') {
        address_correction(this.id);
    } else if (input_type == 'date') {
        date_correction(this.id);
    } else if (input_type == 'currency') {
        currency_correction(this.id);
    } else if (input_type == 'custom_numeric') {
        custom_numeric_correction(this.id);
    } else if (input_type == 'username') {
        username_correction(this.id);
    }

    if ($(this).val() != '') {
        $(this).parent().parent().find('.has-danger').removeClass('has-danger');
    }

});
function validation_to_form(data) {
    Object.keys(data).forEach(function (key) {
        var val = data[key];
        $('[name="' + key + '"]').closest('.form-group').addClass('has-danger');
        var html = "<span class='help-block text-danger'>" + val + "</span>";
        $('[name="' + key + '"]').closest('.form-group').empty()
        $('[name="' + key + '"]').closest('.form-group').append(html);
    });
}
function mandatory_check(form_id) {

    var IR = 0;

    $('form#' + form_id).find('input,select,textarea').each(function () {

        if ($(this).prop('required')) {
            //console.log(this.id);
            if ($(this).val() == '') {
                $(this).parent().addClass('has-danger');
                IR = IR + 1;
            } else {
                $('#' + form_id).parent().find('.has-danger').removeClass('has-danger');
            }

        } else {

            // console.log(this.id);

        }

    });

    //console.log(IR)
    return IR;

}

// Auto Corretion

function currency_correction(id) {
    var node = $('#' + id);
    var number_string = node.val().replace(/[^,\d]/g, '').toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    node.val(split[1] != undefined ? rupiah + ',' + split[1] : rupiah);
}

function currency_format(value) {
    var number_string = value.toString(),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    return split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
}

function username_correction(id) {
    var node = $('#' + id);
    node.val(node.val().replace(/[^a-zA-Z0-9!@#$%]/g, ''));
}

function alphabet_correction(id) {
    var node = $('#' + id);
    node.val(node.val().replace(/[^a-z A-Z]/g, ''));
}

function numeric_correction(id) {
    var node = $('#' + id);
    node.val(node.val().replace(/[^0-9]/g, ''));
}

function custom_numeric_correction(id) {
    var node = $('#' + id);
    node.val(node.val().replace(/[^0-9.-]/g, ''));
}

function email_correction(id) {
    var node = $('#' + id);
    node.val(node.val().replace(/[^0-9 a-z A-Z@.-_]/g, ''));
}

function address_correction(id) {
    var node = $('#' + id);
    node.val(node.val().replace(/[^0-9 a-z A-Z,.]/g, ''));
}

function date_correction(id) {
    var node = $('#' + id);
    node.val(node.val().replace(/[^0-9-/]/g, ''));
}
