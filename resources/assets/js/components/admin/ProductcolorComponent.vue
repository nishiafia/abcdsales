<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Color List</h3>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New  <i class="fa fa-creative-commons" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Size</th>
                        <th>Status</th>
                        <th>Modify</th>
                  </tr> 
                  <tr v-for="prcolor in productcolors.data" :key="prcolor.id">
                    <td>{{ prcolor.id }}</td>
                    <td>{{ prcolor.colorname }}</td>
                    <td v-if="prcolor.isactive === 1" class="useractive">Active</td>
										<td class="userinactive" v-else>In Active</td>
                    <td>
                        <a href="/#/productcolor" data-id="prcolor.id" @click="editModalWindow(prcolor)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                       <a title="Do Inactive" v-if="prcolor.isactive === 1" href="/#/productcolor" @click="inactiveColor(prcolor.id)">
                            <i class="fa fa-ban red" aria-hidden="true"></i>
                        </a>
                         <a title="Do Active" v-else href="/#/productcolor" @click="activeColor(prcolor.id)">
                            <i class="fa fa-undo green" aria-hidden="true"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
                <pagination :data="productcolors" @pagination-change-page="loadColors"></pagination>
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
                <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Color</h5>
                <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Color</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>

                <form @submit.prevent="editMode ? updateColor() : createColor()">
                  <div class="modal-body">
                    <div class="form-group">
                    <input v-model="form.colorname" type="text" name="colorname"
                    placeholder="ColorName"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('colorname') }">
                    <has-error :form="form" field="colorname"></has-error>
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
                productcolors: {},
                form: new Form({
                    id: '',
                    colorname : '',
                })
            }
        },
        methods: {
        editModalWindow(prcolor){
           this.form.clear();
           this.editMode = true
           this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(prcolor)
        },
        updateColor(){
           this.form.put('api/productcolor/'+this.form.id)
               .then((response)=>{
                  console.log("response:",response.data);
                 if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedColorLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'This Color Already Exists!!'
                        })
                    }
                    else{
                   Toast.fire({
                      icon: 'success',
                      title: 'Color updated successfully'
                    })
                    Fire.$emit('AfterCreatedColorLoadIt');
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
        loadColors(page) {
          if (typeof page === 'undefined') {
             page = 1;
             }
          axios.get('api/productcolor?page=' + page).then( data => (this.productcolors = data.data));
          //console.log("data",this.categories);
        },
        createColor(){
          
            this.$Progress.start()
            this.form.post('api/productcolor')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedColorLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'This Color Already Exists!!'
                        })
                    }
                    else{
                    Fire.$emit('AfterCreatedColorLoadIt'); //custom events
                        Toast.fire({
                          icon: 'success',
                          title: 'Color created successfully'
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
          inactiveColor(id) {
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
                this.form.delete('api/productcolor/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Inactive!',
                              'Color inactive successfully',
                              'success'
                            )
                    this.loadColors();
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
          activeColor(id) {
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
                this.form.delete('api/productcolor/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Active!',
                              'Color active successfully',
                              'success'
                            )
                    this.loadColors();
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
            this.loadColors();
            Fire.$on('AfterCreatedColorLoadIt',()=>{ //custom events fire on
                this.loadColors();
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
