<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Suppliers Table</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add New<i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>ID</th>
                    <th>Name</th>
                  </tr>
                  <tr v-for="supplier in suppliers.data" :key="supplier.id">
                    <td>{{ supplier.id }}</td>
                    <td>{{ supplier.name }}</td>
                    <td>{{ supplier.created_at | myDate }}</td>
                    <td>
                        <a href="#" @click="editModal(supplier)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                         /
                        <a href="#" @click="deleteSupplier(supplier.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                   <pagination :data="suppliers" @pagination-change-page="getResults">
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
                <h5 v-show="editMode" class="modal-title" id="addNewTitle">Update Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form @submit.prevent="editMode ? updateSupplier() : createSupplier()">
                <div class="modal-body">               
                    <div class="form-group">
                        <input v-model="form.name" type="text" name="name" placeholder="Name"
                            class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button v-show="editMode" type="submit" class="btn btn-success">Update</button>
                    <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
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
              suppliers: {}, //user object
              form: new Form({
                id: '',  
                name: '',
              })

           }

        },
        methods: {
            getResults(page = 1) {
                axios.get('api/supplier?page=' + page)
                     .then(response => {
                           this.suppliers = response.data;
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
            loadSuppliers(){
                axios.get('supplier').then(({ data }) => (this.suppliers = data));
            },
            createSupplier(){
                this.$Progress.start();
                this.form.post('supplier')
                .then(() => {
                    Fire.$emit('AfterCreate');

                    $('#addNew').modal('hide');

                    toast.fire({
                    type: 'success',
                    title: 'Supplier created successfully'
                    });

                    this.$Progress.finish();
                })
                .catch(() => {


                })

                
            },
            updateSupplier(){
                this.$Progress.start();
                //send to server
                this.form.put('supplier/' +this.form.id)
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
            this.loadSuppliers();
            Fire.$on('AfterCreate', () => {
                this.loadSuppliers();
            });     
        }
    }
</script>
