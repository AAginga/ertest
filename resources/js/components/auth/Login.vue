<template>
<div class="hold-transition login-page">
<div class="login-box">
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form @submit.prevent="createUser()">
        <div class="input-group mb-3">
          <input class="form-control" v-model="form.username" type="text" name="username" placeholder="Email">
          <div class="input-group-append input-group-text">
              <span class="fas fa-envelope"></span>
          </div>
        </div>
        <div class="input-group mb-3">
          <input v-model="form.password" type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append input-group-text">
              <span class="fas fa-lock"></span>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mb-0">
        <router-link to="/register" class="text-center">Register an account</router-link>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
</div>
</template>
<script>
export default {
data(){
    return {
        users: {}, //user object
            form: new Form({ 
            username: '',
            password: '',
            })
        }
    },
    methods: {
           createUser(){
                this.$Progress.start();
                let username = this.form.username 
                let password = this.form.password 

                this.$store.dispatch('login', { username, password })
                .then(() => { 
                    Fire.$emit('AfterCreate');

                    toast.fire({
                        type: 'success',
                        title: 'User logged in successfully'
                    });

                    this.$Progress.finish();
                    this.$router.push('/')
                })

                .catch(err => {
                    
                })       
                
            },
    },

}
</script>
<style lang="scss" scoped>

</style>