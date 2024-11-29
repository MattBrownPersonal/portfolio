<template>
  <div class="container-fluid">
    <v-row>
      <v-col>
        <v-card>
          <v-card-text>
            <div class="text-h6 mb-1">
              Articles
            </div>
            Manage the set of global articles.
          </v-card-text>
          <v-card-actions>
            <v-btn small outlined color="primary" to="/articles">Manage</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
      <v-col>
        <v-card>
          <v-card-text>
            <div class="text-h6 mb-1">
              FAQs
            </div>
            Manage the set of FAQs.
          </v-card-text>
          <v-card-actions>
            <v-btn small outlined color="primary" to="/allFaqs">Manage</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
      <v-col>
        <v-card>
          <v-card-text>
            <div class="text-h6 mb-1">
              Categories
            </div>
            Manage the set of categories.
          </v-card-text>
          <v-card-actions>
            <v-btn small outlined color="primary" to="/memorialisations">Manage</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <v-card>
          <v-card-text>
            <div class="text-h6 mb-1">
              Deceased
            </div>
            Manage deceased.
          </v-card-text>
          <v-card-actions>
            <v-btn small outlined color="primary" to="/deceased">Manage</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
      <v-col>
        <v-card>
          <v-card-text>
            <div class="text-h6 mb-1">
              Orders
            </div>
            View and manage orders.
          </v-card-text>
          <v-card-actions>
            <v-btn small outlined color="primary" to="/orders">Manage</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
      <v-col>
        <v-card>
          <v-card-text>
            <div class="text-h6 mb-1">
              Settings
            </div>
            Manage system settings.
          </v-card-text>
          <v-card-actions>
            <v-btn small outlined color="primary" to="/settings">Manage</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <v-card>
          <v-card-text>
            <div class="text-h6 mb-1">
              Sites
            </div>
            Manage sites.
          </v-card-text>
          <v-card-actions>
            <v-btn small outlined color="primary" to="/sites">Manage</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
      <v-col>
        <v-card>
          <v-card-text>
            <div class="text-h6 mb-1">
              Suppliers
            </div>
            Manage suppliers.
          </v-card-text>
          <v-card-actions>
            <v-btn small outlined color="primary" to="/suppliers">Manage</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
      <v-col>
        <v-card>
          <v-card-text>
            <div class="text-h6 mb-1">
              Users
            </div>
            Manage system users.
          </v-card-text>
          <v-card-actions>
            <v-btn small outlined color="primary" to="/users">Manage</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>