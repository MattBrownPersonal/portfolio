require('./bootstrap');
import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuex from 'vuex'
import { store } from './store/store'
import moment from 'moment'

Vue.prototype.moment = moment

Vue.use(Vuex)

Vue.use(VueRouter)

export const bus = new Vue();

window.Vue = require('vue').default;
import Vuetify from 'vuetify';
Vue.use(Vuetify);


Vue.component('main-applicant-component', require('./components/MainApplicantComponent.vue').default);

import Home from './components/applicant/shop/HomeComponent'
import Categories from './components/applicant/shop/CategoriesComponent'
import ProductListing from './components/applicant/shop/ProductListingComponent.vue'
import ProductPage from './components/applicant/shop/ProductPageComponent.vue'
import BereavementSupport from './components/applicant/shop/BereavementSupportComponent.vue'
import FaqComponent from './components/applicant/shop/FaqComponent.vue'
import ViewArticle from './components/applicant/shop/ViewArticleComponent.vue'
import PaymentConfirmed from "./components/applicant/shop/PaymentConfirmedComponent.vue";
import Welcome from "./components/applicant/shop/WelcomeComponent.vue";
import ToS from "./components/applicant/shop/ToSComponent.vue";
import VerifyCustomerAddress from "./components/applicant/shop/VerifyCustomerAddressComponent.vue";

Vue.use(VueRouter);

const router = new VueRouter({
	scrollBehavior(
		to,
		from,
		savedPosition
	) {
		if (to.hash) {
			return document
				.querySelector(to.hash)
				.scrollIntoView({
					behavior: "smooth",
				});
		} else {
			return { x: 0, y: 0 };
		}
	},
	mode: "history",
	routes: [
		{
			path: "/",
			name: "welcome",
			component: Welcome,
		},
		{
			path:
				"/m/:code/:categoryid/productpage/:productid",
			name: "productpage",
			component: ProductPage,
		},
		{
			path:
				"/m/:code/productpage/:productid",
			name: "singleproductpage",
			component: ProductPage,
		},
		{
			path: "/m/:code/categories/",
			name: "categories",
			component: Categories,
		},
		{
			path:
				"/m/:code/productlisting/:categoryid",
			name: "productlisting",
			component: ProductListing,
		},
		{
			path: "/m/:code/",
			name: "memorials",
			component: Home,
		},
		{
			path:
				"/paymentConfirmed/:code/:productid",
			name: "paymentConfirmed",
			component: PaymentConfirmed,
		},
		{
			path:
				"/m/:code/bereavementsupport",
			name: "bereavementsupport",
			component: BereavementSupport,
		},
		{
			path: "/m/:code/faqs",
			name: "faqs",
			component: FaqComponent,
		},
		{
			path:
				"/m/:code/viewarticle/:articleid",
			name: "viewarticle",
			component: ViewArticle,
		},
		{
			path: "/m/:code/termsofservice",
			name: "tos",
			component: ToS,
		},
		{
			path: "/m/:code/verifyaddress/:token",
			name: "verifyaddress",
			component: VerifyCustomerAddress,
		},
	],
});
		
const App = new Vue({
    store: store,
    el: '#app',
    router,
    vuetify: new Vuetify(),
});

