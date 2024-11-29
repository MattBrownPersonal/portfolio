import axios from 'axios';
import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        styles: [],
        deceased: null,
        memorialisations: [],
        products: [],
        cart: [],
    },
    mutations: {
        getStyles(state, payload) {
            axios.get(`/api/applicantshop/${payload}`)
                .then(res => {
                    if (res.data.isGenericSite === false) {
                        state.styles = res.data.deceased.site.sitestyle;
                    } else {
                        state.styles = res.data.siteStyle.sitestyle
                    }
                
            });
        },
        addToCart(state, payload) {
            state.cart.push(payload);
            localStorage.setItem('cartItems', JSON.stringify(payload));
        },
        fetchDeceased(state, payload) {
            axios.get(`/api/fetchDeceased/${payload}`).then(res => {
                state.deceased = res.data
            });
        },
        fetchMemorialisationInfo(state) {
            axios.get('/api/fetchMemorialisations').then(res => {
                state.memorialisations = res.data
            });
        },
        fetchProducts(state) {
            axios.get('/api/customerproducts').then(res => {
                state.products = res.data
            });
        },
        
    },
    actions: {
        fetchMemorialisationInfo: context => {
            context.commit('fetchMemorialisationInfo')
        },
        fetchDeceased: (context, payload) => {
            context.commit('fetchDeceased', payload)
        },
        addToCart: (context, payload) => {
            context.commit('addToCart', payload)
        },
        getStyles: (context, payload) => {
            context.commit('getStyles', payload)
        },
        fetchProducts: context => {
            context.commit('fetchProducts')
        }
    }
});