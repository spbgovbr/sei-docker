<h3>Teste com js puro </h3>


<div id='myapp2'>
    <div class="form-group">
        <label for="id_select">Country</label>
        <select id="select-countries" class="form-control">
            <option value='0'>Select Country</option>
            <option value="1">1</option>
            <option value="2">2</option>
        </select>
    </div>

    <div class="form-group">
        <label for="id_select" class="d-flex">
            <span class="d-flex">State</span>
            <span id='select-countriesspinner' style="display:none;">
            <div class="d-flex text-primary ml-2 badge badge-light align-content-center badge-pill">
               <span class="mr-2">Carregando...</span>
               <div class="spinner-border spinner-border-sm" role="status">
                    <span class="sr-only">Loading...</span>
               </div>
            </div>
        </label>

        <select id="select-states" class="form-control">
            <option value='0'>Select a country</option>
        </select>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function (event) {
        // lançamento do evento do select-countries
        var parentEl = document.getElementById('select-countries');

        function getSelectCountriesData(el) {
            var selectedOption = el.selectedOptions[0];
            var data = {
                innerHTML: selectedOption.innerHTML,
                value: selectedOption.value,
                data: selectedOption.dataset
            };
            return data;
        }

        function clearSelect(el) {
            el.disabled = true;
            el.innerHTML = '';
        }

        function addSpinner(spinnerPlaceholder, spinner) {
            spinnerPlaceholder.innerHTML = spinner;
        }


        function hideSpinner(spinnerPlaceholder) {
            spinnerPlaceholder.style = 'display:none;';
        }

        //tratamento do evento por parte do states
        parentEl.addEventListener('change', function (e) {
            var select = this;
            var data = getSelectCountriesData(select);
            var param = data.innerHTML;
            var placeholder = "Select a state";
            var spinnerPlaceholderId = select.id + 'spinner';
            var spinnerPlaceholder = document.getElementById(spinnerPlaceholderId);

            var childEl = document.getElementById('select-states');
            clearSelect(childEl);

            var data = getCountryStates(param); //todo tornar assíncrono

            function showSpinner(spinnerPlaceholder) {
                spinnerPlaceholder.style = '';
            }

            showSpinner(spinnerPlaceholder);

            window.setTimeout(() => {
                hideSpinner(spinnerPlaceholder);
                addOptionsToSelect(placeholder, childEl, data);
            }, 1000);
        });

        function addOptionsToSelect(placeholder, select, options) {
            var innerHTML = `<option>${placeholder}</option>`;
            for (var o of options) {
                //var attrs = todo o.data
                innerHTML += `<option value='${o.id}'>${o.name}</option>`;
            }
            select.innerHTML = innerHTML;
            select.disabled = false;
        }
    });

</script>
