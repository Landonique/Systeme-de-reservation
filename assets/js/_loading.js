export function addSpinner(elem, type = 'prepend', options) {
    if (!elem.find('.fa-spinner').length) {
        if (type == 'replace') {
            elem.find('i').addClass('d-none');
            elem.prepend('<i class="fa fa-spinner fa-spin ' + options + '"></i>');
        } else {
            elem.prepend('<i class="fa fa-spinner fa-spin ' + options + '"></i>');
        }
    }
}

export function removeSpinner(elem) {
    elem.find('.fa-spinner').remove();
    elem.find('i').removeClass('d-none');
}
