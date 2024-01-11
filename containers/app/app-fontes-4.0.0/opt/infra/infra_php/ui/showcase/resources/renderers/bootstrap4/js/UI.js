import axios from 'axios';

"use strict";
//todo refatorar isso para algo mais elegante
export const UI = {
    defaults: {
        locale: 'pt-br',
        dateHourMinFormat: 'DD/MM/YYYY HH:mm',
        dateFormat: 'DD/MM/YYYY'
    },
    PHPHelper: {
        dateInterval: {
            init(startId, endId, withTime) {
                UI.PHPHelper.date.init(startId, withTime);
                UI.PHPHelper.date.init(endId, withTime);

                const dateStart = $('#' + startId);
                const dateEnd = $('#' + endId);

                dateStart.on("change.datetimepicker", function (e) {
                    dateEnd.datetimepicker('minDate', e.date);
                });

                dateEnd.on("change.datetimepicker", function (e) {
                    dateStart.datetimepicker('maxDate', e.date);
                });
            }
        },
        date: {
            /**
             *
             * @param id string
             * @param withTime boolean
             * @param value = null string
             */
            init(id, withTime, value) {

                const input = $('#' + id);

                let options = withTime ? {} : {format: 'L'};

                const format = withTime ? UI.defaults.dateHourMinFormat : UI.defaults.dateFormat;

                if (value) {
                    options.defaultDate = moment(value, format);
                }

                input.datetimepicker(options);

                const blocks = {
                    DD: {
                        mask: IMask.MaskedRange,
                        from: 1,
                        to: 31
                    },
                    MM: {
                        mask: IMask.MaskedRange,
                        from: 1,
                        to: 12
                    },
                    YYYY: {
                        mask: IMask.MaskedRange,
                        from: '0000',
                        to: '9999'
                    }
                };

                if (withTime) {
                    blocks.HH = {
                        mask: IMask.MaskedRange,
                        from: 0,
                        to: 23
                    };

                    blocks.mm = {
                        mask: IMask.MaskedRange,
                        from: 0,
                        to: 59
                    };
                }
                
                const placeholder = input.attr('placeholder');

                function newMask($lazy){
                    return IMask(document.getElementById(id), {
                        mask: Date,
                        pattern: format,
                        lazy: $lazy,
                        format: function (date) {
                            return moment(date).format(format);
                        },
                        parse: function (str) {
                            return moment(str, format);
                        },
                        blocks: blocks
                    });
                }

                var momentMask = newMask(true); 
                const formatPlaceholder = format.replace(/[A-Z]/g,"_");

                input.on('click', function(e) {
                    momentMask.destroy();
                    momentMask = newMask(false);
                });

                input.on('focusout', function(e) {
                    momentMask.destroy();
                    momentMask = newMask(true);
                    input.attr('placeholder', placeholder);
                });

                input.on('change.datetimepicker', function (e) {
                    if(input.val() === formatPlaceholder) {
                        input.datetimepicker('clear');
                        input.val('')
                    }
                    momentMask.updateValue();
                    return false;
                });
            }
        },
        addActivatedBy: (targetId, activatorId) => {
            const activator = document.getElementById(activatorId);
            const target = document.getElementById(targetId);
            const dataAttr = 'uiActivates';
            const isChecked = activator.checked;
            activator.dataset[dataAttr] = targetId;


            const handleChange = (e) => {
                const activator = e.target;
                const targetId = activator.dataset[dataAttr];
                const isChecked = activator.checked;
                const target = $('#' + targetId);
                checkActivation(target, isChecked);
            }

            const checkActivation = (target, isChecked) => {
                target.prop('disabled', !isChecked);
                target.selectpicker('refresh');
            }

            activator.addEventListener('change', handleChange);
            checkActivation($(target), isChecked);
        },

        select: {
            init: (id, feedbackContainerHtml) => {
                const select = $('#' + id);
                select.selectpicker();
                select.parent().append(feedbackContainerHtml);
            },

            addDependency: (dependencyName,
                            inputId,
                            spinnerPlaceholder,
                            childId,
                            callbackMethod,
                            url,
                            params,
                            placeholder,
                            valueAttr,
                            innerHTMLAttr,
                            dataMap) => {

                //todo formatar o resultado (nao posso garantir que vai ser sempre innerHTML, value e data


                var dependencyName = document.getElementById(inputId)

                //todo quem gera isso é o pai né... assim vai ter duplicatas no caso de mais de um elemento dependente
                function getSelectCountriesData(el) {
                    var selectedOption = el.selectedOptions[0];

                    return {
                        innerHTML: selectedOption.innerHTML,
                        value: selectedOption.value,
                        data: selectedOption.dataset
                    };
                }

                //tratamento do evento por parte do states
                dependencyName.addEventListener('change', function (e) {
                    var select = this;
                    var data = getSelectCountriesData(select);
                    var param = data.innerHTML;
                    var placeholder = "Select a state";
                    var spinnerPlaceholder = document.getElementById(spinnerPlaceholder);
                    const childEl = document.getElementById(childId);

                    this.clearSelect(childEl);

                    if (select.value) {

                        this.showSpinner(spinnerPlaceholder);

                        axios[callbackMethod](url, params)
                            .then(function (data) {
                                this.addOptionsToSelect(placeholder, childEl, data.data, dataMap, valueAttr, innerHTMLAttr);
                            })
                            .catch(function (error) {
                                console.error(error); //todo exibir algo personalizado
                            })
                            .finally(function () {
                                this.hideSpinner(spinnerPlaceholder);
                            });
                    }
                });
            },
            hideSpinner(spinnerPlaceholder) {
                spinnerPlaceholder.style = 'display:none;';
            },
            showSpinner(spinnerPlaceholder) {
                spinnerPlaceholder.style = '';
            },
            clearSelect(el) {
                el.disabled = true;
                el.innerHTML = '';
                $("#" + el.id).selectpicker('refresh');
            },
            addOptionsToSelect(placeholder, select, setOptions, dataMap, value, innerHTML) {
                var optionsHTML = `<option>${placeholder}</option>`;

                setOptions.forEach(function (v, k) {
                    var dataset = [];
                    Object.getOwnPropertyNames(dataMap).forEach(function (returnAttr, optionAttr) {
                        var returnValue = o[returnAttr];
                        dataset.push(optionAttr + '="' + returnValue + '"');
                    });

                    var optionAttrsString = dataset.join(' ');

                    optionsHTML += `<option value="${value}" ${optionAttrsString}>${innerHTML}</option>`;
                });
                select.innerHTML = optionsHTML;
                select.disabled = false;
                $("#" + select.id).selectpicker('refresh');

            }
        }

    }
}
export default UI;
