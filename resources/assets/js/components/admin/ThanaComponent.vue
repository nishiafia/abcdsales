<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Thana List</h3>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New  <i class="fa fa-creative-commons" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>ID</th>
                        <th>District</th>
                        <th>Thana</th>
                        <th>Status</th>
                        <th>Modify</th>
                  </tr>
                  <tr v-for="thana in thanalists.data" :key="thana.id">
                    <td>{{ thana.id }}</td>
                    <td>{{ thana.districtdata.districtname}}</td>
                    <td>{{ thana.thananame }}</td>
                    <td v-if="thana.isactive === 1" class="useractive">Active</td>
										<td class="userinactive" v-else>In Active</td>
                    <td>
                        <a href="/#/thanalist" data-id="thana.id" @click="editModalWindow(thana)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                        <a title=" Do Inactive" v-if="thana.isactive === 1" href="/#/thanalist" @click="inactiveThana(thana.id)">
                            <i class="fa fa-ban red" aria-hidden="true"></i>
                        </a>
                         <a title="Do Active" v-else href="/#/thanalist" @click="activeThana(thana.id)">
                            <i class="fa fa-undo green" aria-hidden="true"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
                <pagination :data="thanalists" @pagination-change-page="loadThanas"></pagination>
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
                <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Thana</h5>
                <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Thana</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>

                <form @submit.prevent="editMode ? updateThana() : createThana()">
                  <div class="modal-body">
                  <div class="form-group">
                    <label for="unittype" class="rowlabel">District</label>
                    <select name="districtid" v-model="form.districtid" id="districtid" class="form-control districtid" required>
                    <option v-for="vl in dislists" v-bind:value="vl.id" :key="vl.id">
                    {{ vl.districtname }}
                    </option>
                    </select>
                    </div>
                    <div class="form-group">
                    <input v-model="form.thananame" type="text" name="thananame"
                    placeholder="Thana Name"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('thananame') }" required>
                    <has-error :form="form" field="thananame"></has-error>
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
                thanalists: {},
                dislists: {},
                form: new Form({
                    id: '',
                    thananame : '',
                    districtid : 1,
                })
            }
        },
        methods: {
        editModalWindow(thana){
           this.form.clear();
           this.editMode = true
           this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(thana)
        },
        updateThana(){
           this.form.put('api/thanalist/'+this.form.id)
               .then((response)=>{
                  console.log("response:",response.data);
                 if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedThanaLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'This Thana Already Exists!!'
                        })
                    }
                    else{
                   Toast.fire({
                      icon: 'success',
                      title: 'Thana updated successfully'
                    })
                    Fire.$emit('AfterCreatedThanaLoadIt');
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
        loadThanas(page) {
          if (typeof page === 'undefined') {
             page = 1;
             }
          axios.get('api/thanalist?page=' + page).then( data => (this.thanalists = data.data));
          console.log("data=",this.thanalists);
        },
         loadDistricts() {
            axios.get("/getdis")
          .then( data =>{
          this.dislists = data.data;
          //this.productcode=1000;
          });
        },
        createThana(){
            this.$Progress.start()
            this.form.post('api/thanalist')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedThanaLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Thana Already Exists!!'
                        })
                    }
                    else{
                    Fire.$emit('AfterCreatedThanaLoadIt'); //custom events
                        Toast.fire({
                          icon: 'success',
                          title: 'Thana created successfully'
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
          inactiveThana(id) {
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
                this.form.delete('api/thanalist/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Inactive!',
                              'Thana inactive successfully',
                              'success'
                            )
                    this.loadThanas();
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
          activeThana(id) {
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
                this.form.delete('api/thanalist/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Active!',
                              'Thana active successfully',
                              'success'
                            )
                    this.loadThanas();
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
            this.loadThanas();
             this.loadDistricts();
            Fire.$on('AfterCreatedThanaLoadIt',()=>{ //custom events fire on
                this.loadThanas();
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
