<template>
  <div class="card">
    <div class="card-header">Login</div>
    <div class="card-body">
        <div class="form-group row">
          <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
          <div class="col-md-6">
            <input id="email" type="email" class="form-control" name="email" value="" required autofocus v-model="email">
            <span class="invalid-feedback" role="alert">
              <strong>{{ warning }}</strong>
            </span>
          </div>
        </div>
        <div class="form-group row">
          <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
          <div class="col-md-6">
            <input id="password" type="password" class="form-control" name="password" required v-model="password" v-on:keyup.enter="login">
            <span class="invalid-feedback" role="alert">
              <strong>{{ warning }}</strong>
            </span>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-6 offset-md-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">
                  Remember me
              </label>
            </div>
          </div>
        </div>
        <div class="form-group row mb-0">
          <div class="col-md-8 offset-md-4">
            <button type="submit" class="btn btn-primary" @click="login">
              Login
            </button>
            <a class="btn btn-link" href="#">
              Forgot Your Password?
            </a>
          </div>
        </div>
    </div>
  </div>
</template>
<script>
  export default {
    data () {
      return {
        email: '',
        password: '',
        warning: ''
      }
    },
    methods: {
      login() { 
        axios.get('/sanctum/csrf-cookie').then(response => {
          axios.post('/login', {email: this.email, password: this.password}).then(response => {
            window.location.href = "/dashboard";
          })
        });
      },
    },
  }
</script>