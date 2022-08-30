<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Size List</h3>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New  <i class="fa fa-creative-commons" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                       <th>Company</th> 
                        <th>Size</th>
                        <th>Status</th>
                        <th>Modify</th>
                  </tr> 
                  <tr v-for="prsize in productsizes.data" :key="prsize.id">
                   <td>{{ prsize.companydata.companyname }}</td>
                    <td>{{ prsize.psize }}</td>
                    <td v-if="prsize.isactive === 1" class="useractive">Active</td>
										<td class="userinactive" v-else>In Active</td>
                    <td>
                        <a href="/#/productsize" data-id="prsize.id" @click="editModalWindow(prsize)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                        <a title=" Do Inactive" v-if="prsize.isactive === 1" href="/#/productsize" @click="inactiveSize(prsize.id)">
                            <i class="fa fa-ban red" aria-hidden="true"></i>
                        </a>
                         <a title="Do Active" v-else href="/#/productsize" @click="activeSize(prsize.id)">
                            <i class="fa fa-undo green" aria-hidden="true"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
                <pagination :data="productsizes" @pagination-change-page="loadSizes"></pagination>
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
                <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Size</h5>
                <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Size</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>

                <form @submit.prevent="editMode ? updateSize() : createSize()">
                  <div class="modal-body">
                    <div class="form-group">
                    <input v-model="form.psize" type="text" name="psize"
                    placeholder="Size"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('psize') }">
                    <has-error :form="form" field="psize"></has-error>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <input type="hidden" v-model="form.systemid" name="systemid" />
                     <input type="hidden" v-model="form.entryid" name="entryid" />
                     <input type="hidden"   name="companyid" v-model="form.companyid">
                      <input type="hidden"   name="branchid" v-model="form.branchid">
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
       props: ['userData'],
        data() {
            return {
                editMode: false,
                productsizes: {},
                form: new Form({
                    id: '',
                    psize : '',
                    systemid: this.userData.systemid,
                    entryid: this.userData.id,
                    companyid: this.userData.companyid,
                    branchid: this.userData.branchid, 
                })
            }
        },
        methods: {
        editModalWindow(prsize){
           this.form.clear();
           this.editMode = true
           this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(prsize)
        },
        updateSize(){
           this.form.put('/productsize/'+this.form.id)
               .then((response)=>{
                  console.log("response:",response.data);
                 if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedSizeLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'This Size Already Exists!!'
                        })
                    }
                    else{
                   Toast.fire({
                      icon: 'success',
                      title: 'Size updated successfully'
                    })
                    Fire.$emit('AfterCreatedSizeLoadIt');
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
        loadSizes(page) {
          if (typeof page === 'undefined') {
             page = 1;
             }
              let headers = {
            "Sessionkey": this.userData.remember_user,
          }
              //console.log("props=",this.userData.remember_user);
          axios.get('/productsize?page=' + page,{headers}).then( data => (this.productsizes = data.data));
          //console.log("data",this.categories);
        },
        createSize(){
          
            this.$Progress.start()
            this.form.post('/productsize')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedSizeLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Size Already Exists!!'
                        })
                    }
                    else{
                    Fire.$emit('AfterCreatedSizeLoadIt'); //custom events
                        Toast.fire({
                          icon: 'success',
                          title: 'Size created successfully'
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
          inactiveSize(id) {
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
                this.form.delete('/productsize/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Inactive!',
                              'Size inactive successfully',
                              'success'
                            )
                    this.loadSizes();
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
          activeSize(id) {
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
                this.form.delete('/productsize/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Active!',
                              'Size active successfully',
                              'success'
                            )
                    this.loadSizes();
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
            this.loadSizes();
            Fire.$on('AfterCreatedSizeLoadIt',()=>{ //custom events fire on
                this.loadSizes();
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
