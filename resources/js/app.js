// IMPORT BOOTSTRAP
import 'bootstrap';

// IMPORT AXOS
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// IMPORT BOOTSTRAP (jika ada file bootstrap.js)
import './bootstrap';