<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Products Table</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add New Product<i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Date Created</th>
                  </tr>
                  <tr v-for="product in products.data" :key="product.id">
                    <td>{{ product.id }}</td>
                    <td>{{ product.name }}</td>
                    <td>{{ product.quantity }}</td>
                    <td>{{ product.created_at | myDate }}</td>
                    <td>
                        <a href="#" @click="editModal(product)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                         /
                        <a href="#" @click="deleteproduct(product.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                   <pagination :data="products" @pagination-change-page="getResults">
                        <span slot="prev-nav">&lt; Previous</span>
	                    <span slot="next-nav">Next &gt;</span>
                   </pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 v-show="!editMode" class="modal-title" id="addNewTitle">Add New</h5>
                <h5 v-show="editMode" class="modal-title" id="addNewTitle">Update product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form @submit.prevent="editMode ? updateproduct() : createproduct()">
                <div class="modal-body">               
                    <div class="form-group">
                        <input v-model="form.name" type="text" name="name" placeholder="Name"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                    <div class="form-group">
                        <input v-model="form.quantity" type="text" name="quantity" placeholder="Quantity"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('quantity') }">
                        <has-error :form="form" field="quantity"></has-error>
                    </div>
                    <div class="form-group">
                        <textarea v-model="form.description" type="text" name="description" placeholder="Description"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                        <has-error :form="form" field="description"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Suppliers</label>
                        <div v-for="supplier in suppliers.data" :key="supplier.id" class="form-check">
                            <input class="form-check-input" type="checkbox" name="supply_id[supplier.id]" v-model="form.checkedSuppliers" :value="supplier.id">
                            <label class="form-check-label">{{ supplier.name }}</label>
                        </div>
                  </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button v-show="editMode" type="submit" class="btn btn-success">Update</button>
                    <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
                </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {
        data(){
           return {
              editMode: false,
              suppliers: {},
              products: {}, //user object
              form: new Form({
                id: '',  
                name: '',
                quantity: '',
                description: '',
                checkedSuppliers: [],
              })

           }

        },
        methods: {
            getResults(page = 1) {
                axios.get('api/product?page=' + page)
                     .then(response => {
                           this.products = response.data;
                     });
	    	},
            newModal(){
                this.editMode = false;
                this.form.reset();
                $('#addNew').modal('show');

            },
            editModal(user){
                this.editMode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);

            },
            deleteUser(id){
                swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        //send request to the server
                        if (result.value) {
                            this.form.delete('api/user/' +id).then(() => {
                                swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                )
                                
                                Fire.$emit('AfterCreate');  

                            }).catch(() => {
                            swal.fire('Failed!','There was something wrong.','warning');

                            });
                        }
                   
                    });

            },
            loadproducts(){
                axios.get('supplier').then(({ data }) => (this.suppliers = data));
                axios.get('product').then(({ data }) => (this.products = data));
            },
            createproduct(){
                this.$Progress.start();
                this.form.post('product')
                .then(() => {
                    Fire.$emit('AfterCreate');

                    $('#addNew').modal('hide');

                    toast.fire({
                    type: 'success',
                    title: 'product created successfully'
                    });

                    this.$Progress.finish();
                })
                .catch(() => {


                })

                
            },
            updateproduct(){
                this.$Progress.start();
                //send to server
                this.form.put('product/' +this.form.id)
                .then(() => {
                    //success
                    $('#addNew').modal('hide');
                    swal.fire(
                        'Updated!',
                        'User information updated successfully.',
                        'success'
                    )
                    this.$Progress.finish();    
                    Fire.$emit('AfterCreate'); 
                })
                .catch(() =>{
                  this.$Progress.fail();
                });
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created(){          
            this.loadproducts();
            Fire.$on('AfterCreate', () => {
                this.loadproducts();
            });     
        }
    }
</script>
