<h2>Select - Vue - eventos (n funciona)</h2>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<div id='myapp2'>
    <div class="form-group">
        <label for="id_select">Country</label>
        <select @change="changedCountry(country)" class="form-control" v-model='country'>
            <option value='0'>Select Country</option>
            <option v-for='data in countries' :value='data.id'>{{ data.name }}</option>
        </select>
    </div>

    <div class="form-group">
        <label for="id_select">State</label>

        <select class="form-control" v-model='state'>
            <option value='0'>Select State</option>
            <option v-for='data in states' :value='data.id'>{{ data.name }}</option>
        </select>
    </div>
</div>

<script>
    function r(id, name) {
        return {
            id: id,
            name: name
        }
    }

    var eventHub = new Vue();

    var app = new Vue({
        el: '#myapp2',
        data: {
            country: 0,
            countries: [
                r('1', '1'),
                r('2', '2'),
            ],
            state: 0,
            states: '',
        },
        methods: {
            changedCountry: (country) => {
                eventHub.$emit('changed-country', country);
            }
        },
        created: function () {
            eventHub.$on('changed-country', getCountryStates)
        }
    })
</script>
