import axios from "axios";
import { setTimezoneOffsetHeader } from "./utils";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.headers.common["X-Timezone-Offset"] =
    new Date().getTimezoneOffset() * -1;
window.axios.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response && error.response.status === 400) {
            // Catch the missing timezone offset error and set the new header.
            setTimezoneOffsetHeader();
            // Retry the last request with the new header.
            return axios.request(error.config);
        }
        return Promise.reject(error);
    }
);
