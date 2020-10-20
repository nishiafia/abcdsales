<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Business Category</h3>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New  <i class="fa fa-creative-commons" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Modify</th>
                  </tr> 
                  <tr v-for="category in categories.data" :key="category.id">
                    <td>{{ category.id }}</td>
                    <td>{{ category.categoryname }}</td>
                    <td v-if="category.isactive === 1" class="useractive">Active</td>
										<td class="userinactive" v-else>In Active</td>
                    <td>
                        <a href="/#/businesscategory" data="category.id" @click="editModalWindow(category)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                        <a title="Do Inactive" v-if="category.isactive === 1" href="/#/businesscategory" @click="inactiveCategory(category.id)">
                            <i class="fa fa-ban red" aria-hidden="true"></i>
                        </a>
                         <a title="Do Active" v-else href="/#/businesscategory" @click="activeCategory(category.id)">
                            <i class="fa fa-undo green" aria-hidden="true"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
                <pagination :data="categories" @pagination-change-page="loadCategories"></pagination>
              </div>
              <div class="card-footer">
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Business Category</h5>
                <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Business Category</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>

                <form @submit.prevent="editMode ? updateCategory() : createCategory()">
                  <div class="modal-body">
                    <div class="form-group">
                    <input v-model="form.categoryname" type="text" name="categoryname"
                    placeholder="Business Category"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('categoryname') }">
                    <has-error :form="form" field="categoryname"></has-error>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button v-show="editMode" type="submit" class="btn btn-primary">Update</button>
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
        data() {
            return {
                editMode: false,
                categories: {},
                form: new Form({
                    id: '',
                    categoryname : '',
                })
            }
        },
        methods: {
        editModalWindow(businesscategory){
           this.form.clear();
           this.editMode = true
           this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(businesscategory)
        },
        updateCategory(){
           this.form.put('api/category/'+this.form.id)
               .then((response)=>{
                  console.log("response:",response.data);
                 if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedCategoryLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Category  Already Exists!!'
                        })
                    }
                    else{
                   Toast.fire({
                      icon: 'success',
                      title: 'Category updated successfully'
                    })
                    Fire.$emit('AfterCreatedCategoryLoadIt');
                    $('#addNew').modal('hide');
               }})
               .catch(()=>{
                  console.log("Error.....")
               })
        },
        openModalWindow(){
           this.editMode = false
           this.form.reset();
           $('#addNew').modal('show');
        },
        loadCategories(page) {
          if (typeof page === 'undefined') {
             page = 1;
             }
          axios.get('api/category?page=' + page).then( data => (this.categories = data.data));
          //console.log("data",this.categories);
        },
        createCategory(){
          
            this.$Progress.start()
            this.form.post('api/category')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedCategoryLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Category Name Already Exists!!'
                        })
                    }
                    else{
                    Fire.$emit('AfterCreatedCategoryLoadIt'); //custom events
                        Toast.fire({
                          icon: 'success',
                          title: 'Category created successfully'
                        })
                        this.$Progress.finish()
                        $('#addNew').modal('hide');
                         //location.reload();
                    }
                })
                .catch(() => {
                   console.log("Error......")
                })
            //this.loadUsers();
          },
          inactiveCategory(id) {
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, inactive it!'
            }).then((result) => {
              if (result.value) {
                //Send Request to server
                this.form.delete('api/category/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Inactive!',
                              'Category Inactive successfully',
                              'success'
                            )
                    this.loadCategories();
                    }).catch(() => {
                        Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Something went wrong!',
                          footer: '<a href>Why do I have this issue?</a>'
                        })
                    })
                }
            })
          },
          activeCategory(id) {
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, active it!'
            }).then((result) => {
              if (result.value) {
                //Send Request to server
                this.form.delete('api/category/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Active!',
                              'Category active successfully',
                              'success'
                            )
                    this.loadCategories();
                    }).catch(() => {
                        Swal.fire({
                          icon: 'error',
                          title: 'Oops...',
                          text: 'Something went wrong!',
                          footer: '<a href>Why do I have this issue?</a>'
                        })
                    })
                }
            })
          }
        },
        created() { //Like Mounted this method
            this.loadCategories();
            Fire.$on('AfterCreatedCategoryLoadIt',()=>{ //custom events fire on
                this.loadCategories();
            });
            // setInterval(() => 
            //     this.loadUsers()
            // ,3000); //Every 3 seconds loadUsers call
        }
    }
</script> 
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.mt-5{
margin-top: 1rem !important;
}
.pagination{
  margin:15px;
}
</style>
