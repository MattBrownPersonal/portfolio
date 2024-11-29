<template>
 <v-app id="inspire" :style="{background: $vuetify.theme.themes.light.background}">
        <v-app-bar
         app 
         class="pr-5 show-deceased"
         color="primary">
        <v-app-bar-nav-icon @click="drawer = !drawer" :style="burgerMenu"></v-app-bar-nav-icon>

            <v-toolbar-title class="secondary--text">Memorialisation</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-menu
                bottom
                left
                transition="scale-transition"
                :offset-y="offset"
                min-width="150px"                
            >
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                dark
                icon
                v-bind="attrs"
                v-on="on"
                color="primary"
              >
                <v-icon x-large class="secondary--text">mdi-account-circle</v-icon>                
              </v-btn>
            </template>

            <v-list>
              <v-list-item-group>
                <v-list-item>
                    <router-link to="profile" style="text-decoration: none">
                        <h5><v-icon class="secondary--text">mdi-account</v-icon> Profile</h5>
                    </router-link>
                </v-list-item>
                <v-list-item v-if="currentUser.isImpersonating">
                    <h5><v-icon class="secondary--text">mdi-logout</v-icon> <a href="/impersonate/leave">Sign out of {{ currentUser.email }}</a></h5>
                </v-list-item>
                <v-list-item>
                    <h5><v-icon class="secondary--text">mdi-logout</v-icon> <a href="/logout" style="text-decoration: none">Logout</a></h5>
                </v-list-item>
              </v-list-item-group>
            </v-list>
          </v-menu>
            
      </v-app-bar>
      <v-navigation-drawer
        v-model="drawer"
        class="show-deceased"
        app            
        :permanent="drawer"
        :temporary="$vuetify.breakpoint.md"
        color="primary">
        <h4 class="py-5 text-center secondary--text">Obitus</h4>
        <v-list>
            <v-list-item to="/index" @click="closeBurgerMenu()" style="text-decoration: none" class="secondary--text"> 
                <v-list-item-icon>
                    <v-icon class="secondary--text">mdi-home </v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title class="secondary--text">
                        Home
                    </v-list-item-title>
                </v-list-item-content> 
            </v-list-item>
            <v-list-item v-if="canViewArticles" @click="closeBurgerMenu()" to="/articles" style="text-decoration: none" class="secondary--text"> 
                <v-list-item-icon>
                    <v-icon class="secondary--text">mdi-marker</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title class="secondary--text">
                        Articles
                    </v-list-item-title>
                </v-list-item-content> 
            </v-list-item>
            <v-list-item v-if="canViewFaqs" @click="closeBurgerMenu()" to="/allFaqs" style="text-decoration: none" class="secondary--text"> 
                <v-list-item-icon>
                    <v-icon class="secondary--text">mdi-help</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title class="secondary--text">
                        FAQs
                    </v-list-item-title>
                </v-list-item-content> 
            </v-list-item>
            <v-list-item v-if="canViewMemorialisations" @click="closeBurgerMenu()" to="/memorialisations" style="text-decoration: none;" class="secondary--text"> 
                <v-list-item-icon>
                    <v-icon class="secondary--text">mdi-clipboard-edit-outline</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title class="secondary--text">
                        Categories
                    </v-list-item-title>
                </v-list-item-content> 
            </v-list-item>
            <v-list-item v-if="canViewDeceased" @click="closeBurgerMenu()" to="/deceased" style="text-decoration: none;" class="secondary--text"> 
                <v-list-item-icon>
                    <v-icon class="secondary--text">mdi-account-multiple</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title class="secondary--text">
                        Deceased
                    </v-list-item-title>
                </v-list-item-content> 
            </v-list-item>
            <v-list-item v-if="canViewOrder" @click="closeBurgerMenu()" to="/orders" style="text-decoration: none" class="secondary--text"> 
                <v-list-item-icon>
                    <v-icon class="secondary--text">mdi-cart-outline</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title class="secondary--text">
                        Orders
                    </v-list-item-title>
                </v-list-item-content> 
            </v-list-item>
            <v-list-item v-if="canViewSettings" @click="closeBurgerMenu()" to="/settings" style="text-decoration: none" class="secondary--text"> 
                <v-list-item-icon>
                    <v-icon class="secondary--text">mdi-folder-cog</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title class="secondary--text">
                        Settings
                    </v-list-item-title>
                </v-list-item-content> 
            </v-list-item>
            <v-list-item v-if="canViewSites" @click="closeBurgerMenu()" to="/sites" style="text-decoration: none" class="secondary--text"> 
                <v-list-item-icon>
                    <v-badge
                        :content="productCount"
                        :value="productCount"
                        color="red"
                        overlap
                    >
                        <v-icon class="secondary--text">mdi-office-building</v-icon>
                    </v-badge>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title class="secondary--text">
                        Sites
                    </v-list-item-title>                    
                </v-list-item-content> 
            </v-list-item> 
            <v-list-item v-if="canViewSuppliers" @click="closeBurgerMenu()" to="/suppliers" style="text-decoration: none" class="secondary--text"> 
                <v-list-item-icon>
                    <v-icon class="secondary--text">mdi-offer</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title class="secondary--text">
                        Suppliers
                    </v-list-item-title>
                </v-list-item-content> 
            </v-list-item>
            <v-list-item v-if="canViewUsers" @click="closeBurgerMenu()" to="/users" style="text-decoration: none" class="secondary--text"> 
                <v-list-item-icon>
                    <v-icon class="secondary--text">mdi-account-group</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title class="secondary--text">
                        Users
                    </v-list-item-title>
                </v-list-item-content> 
            </v-list-item>
        </v-list>
      </v-navigation-drawer>
      <v-main>
          <router-view class="py-5"></router-view>
      </v-main>
   </v-app>
</template>

<script>
import axios from 'axios';
import { bus } from '../admin'
export default {
    props: ['currentUser'],
    provide() {
        return {
            currentUser: this.currentUser,
        }
    },
    data () { 
        return {
            drawer: true,
            offset: true,
            canViewDeceased: null,
            canViewOrder: null,
            canViewProductCategories: null,
            canViewSettings: null,
            canViewSites: null,
            canViewSuppliers: null,
            canViewUsers: null,
            canViewArticles: null,
            canViewAttributes: null,
            canViewFaqs: null,
            canViewMemorialisations: null,
            productCount: 0
        }
    },
    methods: {
        checkCategories() {
            axios.get('/api/checkCategories')
                .then(res => {
                    this.productCount = res.data;
                })
                .catch((err) => {
                    console.log(':MainAdminComponent:checkCategories:', err);
                    alert(err)
                });
        },
        closeBurgerMenu() {
          if( (["xs", "sm", "md"].includes(this.$vuetify.breakpoint.name)) && this.drawer) {
            this.drawer = false;
          }
        },
    },
    computed: {
        burgerMenu() {
            if (["xs", "sm", "md"].includes(this.$vuetify.breakpoint.name)) {
                this.drawer = false;
                return { "display": "block", "color": "#FFFFFF" };
            }
            this.drawer = true;
            return { "display": "none" };
        }
    },
    created() {
        this.checkCategories();
        axios.get('/api/menuPermissions')
        .then( res => {
            this.canViewAttributes = res.data.canViewAttributes;
            this.canViewDeceased = res.data.canViewDeceased;
            this.canViewOrder = res.data.canViewOrders;
            this.canViewProductCategories = res.data.canViewProductCategories;
            this.canViewSettings = res.data.canViewSettings;
            this.canViewSites = res.data.canViewSites;
            this.canViewSuppliers = res.data.canViewSuppliers;
            this.canViewUsers = res.data.canViewUsers;
            this.canViewArticles = res.data.canViewArticles;
            this.canViewFaqs = res.data.canViewFaqs;
            this.canViewMemorialisations = res.data.canViewMemorialisations;
        })
        .catch((err) => {
            console.log(':MainAdminComponent:created:', err);
            alert(err);
        });

        bus.$on('updatePage', () => {
            this.checkCategories();
        });
    }
}
</script>
<style>
.menuColour {
    color: white;
}
.v-card {
    border-top: 10px solid #1c836f !important;
}
</style>