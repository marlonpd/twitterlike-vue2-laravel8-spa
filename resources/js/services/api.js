import Vue from "vue";
import axios from "axios";
import VueAxios from "vue-axios";
import API_URL from "./config";
import JwtService from "./jwt";

const ApiService = {
    init() {
      Vue.use(VueAxios, axios);
      Vue.axios.defaults.baseURL = API_URL;
      //Vue.axios.defaults.headers.common['Content-type'] = "application/json";  
    },

    setHeader() {
      Vue.axios.defaults.headers.common[
        "Authorization"
      ] = `Bearer ${JwtService.getToken()}`;
    },
  
    query(resource, params) {
      return Vue.axios.get(resource, params).catch(error => {
        throw new Error(`[RWV] ApiService ${error}`);
      });
    },
    
    getAll(resource) {
      return Vue.axios.get(`${resource}`).catch(error => {
        throw new Error(`[RWV] ApiService ${error}`);
      });
    },

    get(resource) {
      return Vue.axios.get(`${resource}`).catch(error => {
        throw new Error(`[RWV] ApiService ${error}`);
      });
    },
  
    post(resource, params) {
      return Vue.axios.post(`${resource}`, params);
    },
  
    update(resource, slug, params) {
      return Vue.axios.put(`${resource}/${slug}`, params);
    },
  
    put(resource, params) {
      return Vue.axios.put(`${resource}`, params);
    },
  
    delete(resource) {
      return Vue.axios.delete(resource).catch(error => {
        throw new Error(`[RWV] ApiService ${error}`);
      });
    }
  };
  
  export default ApiService;
  