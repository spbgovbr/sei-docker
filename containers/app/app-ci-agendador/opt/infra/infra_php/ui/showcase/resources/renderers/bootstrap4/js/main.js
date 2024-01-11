import IMask from "imask";
import UI from './UI';

window._ = require('lodash');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.$ = window.jQuery = require('jquery');
window.moment = require('moment');
window.moment.locale('pt-br');
window.IMask = IMask;

window.UI = UI;

///bootstrap4
require('bootstrap');
require('./_tempusdominus');
require('./_bootstrap-select');
require('./_range');
require('bootstrap-fileinput/js/plugins/piexif.js');
require('bootstrap-fileinput/js/plugins/sortable.js');
require('bootstrap-fileinput');
require('bootstrap-fileinput/themes/explorer-fas/theme.js');
require('bootstrap-fileinput/themes/fas/theme.js');
require('bootstrap-fileinput/js/locales/pt-BR.js');


// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    'use strict';
    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

    }, false);
})();

