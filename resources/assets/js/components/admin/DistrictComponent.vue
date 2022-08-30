<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">District List</h3>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New  <i class="fa fa-creative-commons" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Thana</th>
                        <th>Status</th>
                        <th>Modify</th>
                  </tr>
                  <tr v-for="dis in districtlists.data" :key="dis.id">
                    <td>{{ dis.id }}</td>
                    <td>{{ dis.districtname }}</td>
                    <td v-if="dis.isactive === 1" class="useractive">Active</td>
										<td class="userinactive" v-else>In Active</td>
                    <td>
                        <a href="/#/districtlist" data-id="dis.id" @click="editModalWindow(dis)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                        <a title=" Do Inactive" v-if="dis.isactive === 1" href="/#/districtlist" @click="inactiveThana(dis.id)">
                            <i class="fa fa-ban red" aria-hidden="true"></i>
                        </a>
                         <a title="Do Active" v-else href="/#/districtlist" @click="activeThana(dis.id)">
                            <i class="fa fa-undo green" aria-hidden="true"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
                <pagination :data="districtlists" @pagination-change-page="loadDistricts"></pagination>
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
                <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New District</h5>
                <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update District</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>

                <form @submit.prevent="editMode ? updateDistrict() : createDistrict()">
                  <div class="modal-body">
                    <div class="form-group">
                    <input v-model="form.districtname" type="text" name="districtname"
                    placeholder="District Name"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('districtname') }" required>
                    <has-error :form="form" field="districtname"></has-error>
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
                districtlists: {},
                form: new Form({
                    id: '',
                    districtname : '',
                })
            }
        },
        methods: {
        editModalWindow(dis){
           this.form.clear();
           this.editMode = true
           this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(dis)
        },
        updateDistrict(){
           this.form.put('api/districtlist/'+this.form.id)
               .then((response)=>{
                  console.log("response:",response.data);
                 if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedDistrictLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'This District Already Exists!!'
                        })
                    }
                    else{
                   Toast.fire({
                      icon: 'success',
                      title: 'District updated successfully'
                    })
                    Fire.$emit('AfterCreatedDistrictLoadIt');
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
        loadDistricts(page) {
          if (typeof page === 'undefined') {
             page = 1;
             }
          axios.get('api/districtlist?page=' + page).then( data => (this.districtlists = data.data));
          //console.log("data",this.categories);
        },
        createDistrict(){
            this.$Progress.start()
            this.form.post('api/districtlist')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedDistrictLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'District Already Exists!!'
                        })
                    }
                    else{
                    Fire.$emit('AfterCreatedDistrictLoadIt'); //custom events
                        Toast.fire({
                          icon: 'success',
                          title: 'District created successfully'
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
          inactiveDistrict(id) {
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
                this.form.delete('api/districtlist/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Inactive!',
                              'District inactive successfully',
                              'success'
                            )
                    this.loaddistricts();
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
          activeDistrict(id) {
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
                this.form.delete('api/districtlist/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Active!',
                              'District active successfully',
                              'success'
                            )
                    this.loadDistricts();
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
            this.loadDistricts();
            Fire.$on('AfterCreatedDistrictLoadIt',()=>{ //custom events fire on
                this.loadDistricts();
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
