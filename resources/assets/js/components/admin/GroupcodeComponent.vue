<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Group Code & Title</h3>
                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNew" @click="openModalWindow">Add New  <i class="fa fa-creative-commons" aria-hidden="true"></i></button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Modify</th>
                  </tr> 
                  <tr v-for="groupcode in groupcodes.data" :key="groupcode.id">
                    <td>{{ groupcode.id }}</td>
                    <td>{{ groupcode.gcode }}</td>
                    <td>{{ groupcode.gtitle }}</td>
                    <td v-if="groupcode.isactive === 1" class="useractive">Active</td>
										<td class="userinactive" v-else>In Active</td>
                    <td>
                        <a href="/#/groupcode" data="groupcode.id" @click="editModalWindow(groupcode)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        |
                        <a  title="Do Inactive" v-if="groupcode.isactive === 1" href="/#/groupcode" @click="inactiveCode(groupcode.id)">
                            <i class="fa fa-ban red" aria-hidden="true"></i>
                        </a>
                         <a title="Do Active" v-else href="/#/groupcode" @click="activeCode(groupcode.id)">
                            <i class="fa fa-undo green" aria-hidden="true"></i>
                        </a>

                    </td>
                  </tr>
                </tbody></table>
                <pagination :data="groupcodes" @pagination-change-page="loadGroupcodes"></pagination>
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
                <h5 v-show="!editMode" class="modal-title" id="addNewLabel">Add New Group Code & Title</h5>
                <h5 v-show="editMode" class="modal-title" id="addNewLabel">Update Group Code & Title</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>

                <form @submit.prevent="editMode ? updateGroupcode() : createGroupcode()">
                  <div class="modal-body">
                    <div class="form-group">
                    <input v-model="form.gcode" type="text" name="gcode"
                    placeholder="Group Code"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('gcode') }">
                    <has-error :form="form" field="gcode"></has-error>
                    </div>
                     <div class="form-group">
                    <input v-model="form.gtitle" type="text" name="gtitle"
                    placeholder="Group Title"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('gtitle') }">
                    <has-error :form="form" field="gtitle"></has-error>
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
                groupcodes: {},
                form: new Form({
                    id: '',
                    gcode : '',
                    gtitle : '',
                })
            }
        },
        methods: {
        editModalWindow(groupcode){
           this.form.clear();
           this.editMode = true
           this.form.reset();
           $('#addNew').modal('show');
           this.form.fill(groupcode)
        },
        updateGroupcode(){
           this.form.put('api/groupcode/'+this.form.id)
               .then((response)=>{
                  console.log("response:",response.data);
                 if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedGroupcodeLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Code or Title Already Exists!!'
                        })
                    }
                    else{
                   Toast.fire({
                      icon: 'success',
                      title: 'Updated successfully'
                    })
                    Fire.$emit('AfterCreatedGroupcodeLoadIt');
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
        loadGroupcodes(page) {
          if (typeof page === 'undefined') {
             page = 1;
             }
          axios.get('api/groupcode?page=' + page).then( data => (this.groupcodes = data.data));
          //console.log("data",this.categories);
        },
        createGroupcode(){
          
            this.$Progress.start()
            this.form.post('api/groupcode')
                .then((response) => {
                    console.log("response:",response.data);
                    if(response.data === '')
                    {    // alert("Wrong");
                          Fire.$emit('AfterCreatedGroupcodeLoadIt'); //custom events
                         Toast.fire({
                          icon: 'error',
                          title: 'Code or Title Already Exists!!'
                        })
                    }
                    else{
                    Fire.$emit('AfterCreatedGroupcodeLoadIt'); //custom events
                        Toast.fire({
                          icon: 'success',
                          title: 'Code and Title created successfully'
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
          inactiveCode(id) {
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Inactive it!'
            }).then((result) => {
              if (result.value) {
                //Send Request to server
                this.form.delete('api/groupcode/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Inactive!',
                              'Code Inactive successfully',
                              'success'
                            )
                    this.loadGroupcodes();
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
          activeCode(id) {
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, Active it!'
            }).then((result) => {
              if (result.value) {
                //Send Request to server
                this.form.delete('api/groupcode/'+id)
                    .then((response)=> {
                            Swal.fire(
                              'Active!',
                              'Code Active successfully',
                              'success'
                            )
                    this.loadGroupcodes();
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
            this.loadGroupcodes();
            Fire.$on('AfterCreatedGroupcodeLoadIt',()=>{ //custom events fire on
                this.loadGroupcodes();
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
