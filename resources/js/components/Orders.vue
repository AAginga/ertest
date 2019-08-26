<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">orders Table</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add New order<i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>ID</th>
                    <th>Order Number</th>
                    <th>Date Created</th>
                  </tr>
                  <tr v-for="order in orders.data" :key="order.id">
                    <td>{{ order.id }}</td>
                    <td>{{ order.order_number }}</td>
                    <td>{{ order.created_at | myDate }}</td>
                    <td>
                        <a href="#" @click="editModal(order)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                         /
                        <a href="#" @click="deleteorder(order.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                   <pagination :data="orders" @pagination-change-page="getResults">
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
                <h5 v-show="!editMode" class="modal-title" id="addNewTitle">Add New Order</h5>
                <h5 v-show="editMode" class="modal-title" id="addNewTitle">Update order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form @submit.prevent="editMode ? updateorder() : createorder()">
                <div class="modal-body">               
                    <div class="form-group">
                        <input v-model="form.order_number" type="text" name="order_number" placeholder="Order Number"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('order_number') }">
                        <has-error :form="form" field="order_number"></has-error>
                    </div>
                   
                    <div class="form-group">
                        <label>Products</label>
                        <div v-for="product in products.data" :key="product.id" class="form-check">
                            <input class="form-check-input" type="checkbox" name="product_id[product.id]" v-model="form.checkedproducts" :value="product.id">
                            <label class="form-check-label">{{ product.name }}</label>
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
              products: {},
              orders: {}, //user object
              form: new Form({
                id: '',  
                order_number: '',
                checkedproducts: [],
              })

           }

        },
        methods: {
            getResults(page = 1) {
                axios.get('api/order?page=' + page)
                     .then(response => {
                           this.orders = response.data;
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
            loadorders(){
                axios.get('product').then(({ data }) => (this.products = data));
                axios.get('order').then(({ data }) => (this.orders = data));
            },
            createorder(){
                this.$Progress.start();
                this.form.post('order')
                .then(() => {
                    Fire.$emit('AfterCreate');

                    $('#addNew').modal('hide');

                    toast.fire({
                    type: 'success',
                    title: 'order created successfully'
                    });

                    this.$Progress.finish();
                })
                .catch(() => {


                })

                
            },
            updateorder(){
                axios.get('product').then(({ data }) => (this.products = data));
                this.$Progress.start();
                //send to server
                this.form.put('order/' +this.form.id)
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
            this.loadorders();
            Fire.$on('AfterCreate', () => {
                this.loadorders();
            });     
        }
    }
</script>
