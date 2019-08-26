<template>
<div class="register-box">

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Register a new account</p>

      <form  @submit.prevent="createUser()">
        <div class="input-group mb-3">
          <input v-model="form.name" type="text" name="name"  class="form-control"
            :class="{ 'is-invalid': form.errors.has('name') }" placeholder="Full name">
             <has-error :form="form" field="name"></has-error>
          <div class="input-group-append input-group-text">
              <span class="fas fa-user"></span>
          </div>
        </div>
        <div class="input-group mb-3">
          <input v-model="form.email" type="email" name="email" class="form-control"
                :class="{ 'is-invalid': form.errors.has('email') }" placeholder="Email">
            <has-error :form="form" field="email"></has-error>
          <div class="input-group-append input-group-text">
              <span class="fas fa-envelope"></span>
          </div>
        </div>
        <div class="input-group mb-3">
          <input v-model="form.password" type="password" name="password"  class="form-control" placeholder="Password">
          <div class="input-group-append input-group-text">
              <span class="fas fa-lock"></span>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <router-link to="/login" class="text-center">I already have an account</router-link>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->
</template>
<script>
export default {
    data(){
    return {
        users: {}, //user object
            form: new Form({ 
            name: '',
            email: '',
            password: '',
            })
        }
    },
    methods: {
           createUser(){
                this.$Progress.start();
                let name = this.form.name 
                let email = this.form.email
                let password = this.form.password 

                this.$store.dispatch('register', { name, email, password })
                .then(() => { 
                    Fire.$emit('AfterCreate');

                    toast.fire({
                        type: 'success',
                        title: 'User created successfully'
                    });

                    this.$Progress.finish();
                    this.$router.push('/login')
                })

                .catch(err => {
                    
                })       
                
            },
    },

    
}
</script>