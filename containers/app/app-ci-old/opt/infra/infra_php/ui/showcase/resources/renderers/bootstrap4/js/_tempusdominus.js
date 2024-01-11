require('tempusdominus-bootstrap-4');

$.fn.datetimepicker.Constructor.Default = $.extend({}, $.fn.datetimepicker.Constructor.Default, {
    locale: 'pt-br',
    icons: {
        time: 'material-icons time',
        date: 'material-icons date',
        up: 'material-icons up',
        down: 'material-icons down',
        previous: 'material-icons previous',
        next: 'material-icons next',
        today: 'material-icons today',
        clear: 'material-icons clear',
        close: 'material-icons close'
    },
    tooltips: {
        today: 'Ir para hoje',
        clear: 'Limpar seleção',
        close: 'Fechar calendário',
        selectMonth: 'Selecionar mês',
        prevMonth: 'Mês anterior',
        nextMonth: 'Próximo mês',
        selectYear: 'Selecionar ano',
        prevYear: 'Ano anterior',
        nextYear: 'Próximo ano',
        selectDecade: 'Selecionar década',
        prevDecade: 'Década anterior',
        nextDecade: 'Próximo decada',
        prevCentury: 'Século anterior',
        nextCentury: 'Próximo século',
        pickHour: 'Escolher hora',
        incrementHour: 'Incrementar hora',
        decrementHour: 'Decrementar hora',
        pickMinute: 'Escolher minuto',
        incrementMinute: 'Incrementar minuto',
        decrementMinute: 'Decrementar minuto',
        pickSecond: 'Escolher segundo',
        incrementSecond: 'Incrementar segundo',
        decrementSecond: 'Decrementar segundo',
        togglePeriod: 'Alterar período',
        selectTime: 'Selecionar tempo',
        selectDate: 'Selecionar data'
    },
    useCurrent: false,
});

//fix https://github.com/tempusdominus/bootstrap-4/issues/227 (evento de show)
$.fn.datetimepicker.Constructor.prototype._notifyEvent = function _notifyEvent(e) {
    if (e.type === $.fn.datetimepicker.Constructor.Event.CHANGE && (e.date && e.date.isSame(e.oldDate) || !e.date && !e.oldDate)) {
        return;
    }
    this._element.trigger(e);
};

//todo corrigir i18n do Default.tooltips

/*
correção temporária para a issue #34 que acontece ao clicar no botão "Clear"

https://github.com/tempusdominus/bootstrap-4/issues/34

TypeError: Cannot read property 'isSame' of undefined
TypeError: Cannot read property 'clone' of undefined
It seems that it read the empty value as an error



TODO enviar um PR para o repo deles para incluir esta alteração que, após integrada, deverá ser removida deste arquivo
 */

$.fn.datetimepicker.Constructor.prototype._origSetValue = $.fn.datetimepicker.Constructor.prototype._setValue;
$.fn.datetimepicker.Constructor.prototype._setValue = function _setValue(targetMoment, index) {
    var oldDate = this.unset ? null : this._dates[index];
    var outpValue = '';
    if (!targetMoment && (!this._options.allowMultidate || this._dates.length === 1)) {
        this.unset = true;
        this._dates = [this.getMoment()];
        this._datesFormatted = [];
        this._viewDate = this.getMoment().locale(this._options.locale).clone();
        if (this.input !== undefined) {
            this.input.val('');
            this.input.trigger('input');
        }
        this._element.data('date', outpValue);
        this._notifyEvent({
            type: $.fn.datetimepicker.Constructor.Event.CHANGE,
            date: false,
            oldDate: oldDate
        });
        this._update();
    } else {
        this._origSetValue(targetMoment, index);
    }
};