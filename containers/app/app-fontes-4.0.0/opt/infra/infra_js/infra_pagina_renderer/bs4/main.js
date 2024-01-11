/*
    Para permitir formato de paginação do bs-select.
    Ver issue #2476: https://github.com/snapappointments/bootstrap-select/issues/2476
    Não será mais necessário quando for incorporado ao core (exigirá atualização da dependência)
*/

function infraBS4SelectAtivarPaginacao(seletor) {
    $(seletor).on('changed.bs.select loaded.bs.select', function (e, clickedIndex, isSelected, previousValue) {
        var $title = $(this).parent().find('.filter-option-inner-inner');
        var selectedText = $title.text();
        var paginationFormat = $(this).data('custom-title-format');
        var isMultiple = $(this).prop('multiple');
        var hasPaginationTitleFormat = paginationFormat !== '' && paginationFormat !== undefined;

        if (isMultiple && hasPaginationTitleFormat) {
            console.error("Pagination format can not be used in selects with attr 'multiple' ");
        } else if (hasPaginationTitleFormat) {
            var SELECTED = '{0}';
            var TOTAL = '{1}';

            var formattedPaginationText = paginationFormat;
            if (formattedPaginationText.indexOf(SELECTED) !== -1) {
                formattedPaginationText = formattedPaginationText.replace(SELECTED, selectedText);
            }

            if (formattedPaginationText.indexOf(TOTAL) !== -1) {
                var totalOptions = $(this).find('option').length;
                formattedPaginationText = formattedPaginationText.replace(TOTAL, totalOptions);
            }

            $title.text(formattedPaginationText);
        }
    });
}