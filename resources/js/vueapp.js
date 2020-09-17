// --------------------------------------------------------------------------
// Vue components
// --------------------------------------------------------------------------
import {mask} from 'vue-the-mask';
import {VMoney} from 'v-money';
import DatePicker from 'vue2-datepicker';
import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'
// --------------------------------------------------------------------------
import 'vue2-datepicker/index.css';
import 'vue2-datepicker/locale/ja';
// --------------------------------------------------------------------------
import 'jquery-toast-plugin/dist/jquery.toast.min.js';
import 'jquery-toast-plugin/dist/jquery.toast.min.css';
// --------------------------------------------------------------------------
import draggable from 'vuedraggable';
// --------------------------------------------------------------------------
import { CurrencyInput } from 'vue-currency-input';
import { CurrencyDirective } from 'vue-currency-input';
// --------------------------------------------------------------------------
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
// --------------------------------------------------------------------------
import Multiselect from 'vue-multiselect';
import 'vue-multiselect/dist/vue-multiselect.min.css';
// --------------------------------------------------------------------------
import VueMoment from 'vue-moment';
Vue.use( VueMoment );
// --------------------------------------------------------------------------
import VueRouter from 'vue-router';
Vue.use( VueRouter );
// --------------------------------------------------------------------------
import Vuex from 'vuex';
Vue.use( Vuex );
// --------------------------------------------------------------------------
import animateScrollTo from 'animated-scroll-to';
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
// Vue init options
// --------------------------------------------------------------------------
var vueOptions = {
    el: '#app',
    delimiters: ['{{', '}}'],
    mixins: [mixin],
    components: { DatePicker, draggable, CurrencyInput, VueBootstrapTypeahead, vSelect, Multiselect },
    directives: { mask, money: VMoney, currency: CurrencyDirective }
};
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// If router is defined, add it to Vue init options
// --------------------------------------------------------------------------
if( !_.isUndefined( router ) && !_.isEmpty( router )){
    vueOptions.router = new VueRouter( router );
}
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// If store is defined, add it to Vue init options
// --------------------------------------------------------------------------
if( !_.isUndefined( store ) && !_.isEmpty( store )){
    vueOptions.store = new Vuex.Store( store );
}
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
const app = new Vue( vueOptions ); // Initiate Vue
// --------------------------------------------------------------------------
