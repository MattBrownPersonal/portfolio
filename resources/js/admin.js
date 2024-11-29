require("./bootstrap");
import Vue from "vue";
import VueRouter from "vue-router";
import { store } from "./store/store";
import moment from "moment";
import wysiwyg from "vue-wysiwyg";
import Multiselect from 'vue-multiselect'

Vue.prototype.moment = moment;

Vue.use(VueRouter);

export const bus = new Vue();

Vue.use(wysiwyg, {});
window.Vue = require('vue').default;
import Vuetify from 'vuetify';

Vue.use(Vuetify); 

Vue.component('main-admin-component', require('./components/MainAdminComponent.vue').default);
Vue.component('login-component', require('./components/LoginComponent.vue').default);
Vue.component('vue-multiselect', Multiselect)

import Index from './components/admin/Index/IndexComponent'
import Users from './components/admin/Users/UsersComponent'
import ViewUser from './components/admin/Users/ViewUserComponent'
import Sites from './components/admin/Site/SiteComponent'
import Suppliers from './components/admin/Suppliers/SupplierComponent'
import ViewSupplier from './components/admin/Suppliers/ViewSupplierComponent'
import ShowCategoryProducts from './components/admin/Memorialisations/ShowCategoryProductsCompontent'
import Products from './components/admin/Products/ProductsComponent'
import ShowSiteProducts from './components/admin/Products/ShowSiteProductsComponent'
import ShowSingleSiteProducts from './components/admin/Products/ShowSingleSiteProductComponent'
import ShowSiteMemorialisations from './components/admin/Memorialisations/ShowSiteMemorialsationsComponent.vue'
import Settings from './components/admin/SettingsComponent'
import Deceased from './components/admin/Deceased/DeceasedComponent'
import ShowDeceased from './components/admin/Deceased/ShowDeceasedComponent'
import Orders from './components/admin/Orders/OrdersComponent'
import ShowOrders from './components/admin/Orders/ShowOrdersComponent'
import ShowStaff from './components/admin/Site/ViewSiteInformation'
import SiteUser from './components/admin/Site/ViewSingleSiteStaffMember'
import Profile from './components/admin/Profile/IndexComponent'
import Images from './components/admin/Images/ImageComponent'
import Articles from './components/admin/Articles/ArticlesComponent'
import ViewArticle from './components/admin/Articles/ViewArticleComponent'
import ViewSiteArticle from "./components/admin/Articles/ViewSiteArticleComponent";


import MemorialisationsBySite from './components/admin/Memorialisations/MemorialisationsBySiteComponent'
import AllMemorialisations from "./components/admin/Memorialisations/AllMemorialisationsComponent";
import StaffBySite from './components/admin/SiteStaff/StaffBySiteComponent'
import ArticlesBySite from './components/admin/Articles/ArticlesBySiteComponent'
import AllSiteStaffComponent from './components/admin/SiteStaff/AllSiteStaffComponent'
import SiteArticles from './components/admin/Articles/SiteArticlesComponent'
import AllFaqs from "./components/admin/Faqs/AllFaqsComponent"
import SiteFaqs from "./components/admin/Faqs/SiteFaqsComponent"
import AllMaterials from "./components/admin/Materials/AllMaterialsComponent"
import ShowAllSiteProducts from "./components/admin/Products/ShowAllSiteProductsComponent";

Vue.use(VueRouter);

const router = new VueRouter({
	scrollBehavior() {
		return { x: 0, y: 0 };
	},
	mode: "history",
	routes: [
		{
			path: "/index",
			name: "index",
			component: Index,
		},
		{
			path: "/users",
			name: "users",
			component: Users,
		},
		{
			path: "/users/:id",
			name: "viewuser",
			component: ViewUser,
		},
		{
			path: "/profile",
			name: "profile",
			component: Profile,
		},
		{
			path: "/sites",
			name: "sites",
			component: Sites,
		},
		{
			path: "/sites/:id",
			name: "showstaff",
			component: ShowStaff,
		},
		{
			path: "/staffmember/:id",
			name: "siteuser",
			component: SiteUser,
		},
		{
			path: "/suppliers",
			name: "suppliers",
			component: Suppliers,
		},
		{
			path: "/suppliers/:id",
			name: "viewsupplier",
			component: ViewSupplier,
		},
		{
			path: "/productcategories/:id",
			name: "showcategoryproducts",
			component: ShowCategoryProducts,
		},
		{
			path: "/products",
			name: "product",
			component: Products,
		},
		{
			path: "/products/:product_id/:id",
			name: "showproduct",
			component: ShowSingleSiteProducts,
		},
		{
			path:
				"/siteproducts/:id/:category",
			name: "showsiteproducts",
			component: ShowSiteProducts,
		},
		{
			path: "/allsiteproducts/:id",
			name: "showallsiteproducts",
			component: ShowAllSiteProducts,
		},
		{
			path: "/sitememorialisations/:id",
			name: "showsitemorialisations",
			component: ShowSiteMemorialisations,
		},
		{
			path: "/settings",
			name: "settings",
			component: Settings,
		},
		{
			path: "/deceased",
			name: "deceased",
			component: Deceased,
		},
		{
			path: "/deceased/:id",
			name: "showdeceased",
			component: ShowDeceased,
		},
		{
			path: "/orders",
			name: "orders",
			component: Orders,
		},
		{
			path: "/orders/:id",
			name: "showorders",
			component: ShowOrders,
		},
		{
			path: "/imageuploads",
			name: "imageuploads",
			component: Images,
		},
		{
			path: "/articles",
			name: "articles",
			component: Articles,
		},
		{
			path: "/memorialisations",
			name: "memorialisations",
			component: AllMemorialisations,
		},
		{
			path: "/articles/:id/:article_id",
			name: "viewsitearticle",
			component: ViewSiteArticle,
		},
		{
			path: "/articles/:article_id",
			name: "viewarticle",
			component: ViewArticle,
		},
		{
			path: "/memorialisationsbysite",
			name: "memorialisationsbysite",
			component: MemorialisationsBySite,
		},
		{
			path: "/articlesbysite",
			name: "articlesbysite",
			component: ArticlesBySite,
		},
		{
			path: "/sitearticles/:id",
			name: "sitearticles",
			component: SiteArticles,
		},
		{
			path: "/staffbysite",
			name: "staffbysite",
			component: StaffBySite,
		},
		{
			path: "/allstitestaff/:id",
			name: "allstitestaff",
			component: AllSiteStaffComponent,
		},
		{
			path: "/allFaqs",
			name: "allFaqs",
			component: AllFaqs,
		},
		{
			path: "/siteFaqs/:id",
			name: "siteFaqs",
			component: SiteFaqs,
		},
		{
			path: "/allMaterials",
			name: "allMaterials",
			component: AllMaterials,
		},
	],
});

export default new Vuetify({
  theme: {
    themes: {
      light: {
        primary: "#1C836F",
        secondary: "#F8FAF1",
        accent: "#8c9eff",
        error: "#E51A2F",
        background: "#eff5f4",
      },
    },
  },
});

const adminApp = new Vue({
  store: store,
  el: "#adminApp",
  router,
  vuetify: new Vuetify(),
});
